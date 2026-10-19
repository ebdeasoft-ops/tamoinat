<?php

namespace App\Http\Controllers;

use App\Models\Avt;
use App\Models\invoices;
use App\Models\sales;
use App\Models\system_setting;
use App\Models\products;
use App\Models\products_group;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * شاشة المبيعات السريعة (POS).
 * الحفظ يتم على نفس مسار الفواتير الحالي (save_invoice_sale) بنفس القيود وإرسال زاتكا،
 * هذا الكنترولر فقط لعرض الشاشة وجلب المنتجات والفئات.
 */
class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Sales products');
    }

    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avt = Avt::find(1);
        $vatRate = $avt ? (float) $avt->AVT : 0.15;

        // الفئات التي لها منتجات في فرع المستخدم فقط
        $groupIds = products::where('branchs_id', auth()->user()->branchs_id)
            ->whereNotNull('product_group')
            ->distinct()
            ->pluck('product_group');
        $groups = products_group::whereIn('id', $groupIds)->get();

        return view('sales.pos.index', compact('vatRate', 'groups'));
    }

    /** قائمة المنتجات (بحث بالاسم أو الباركود + فلترة بالفئة) */
    public function products(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $group = $request->input('group');

        $query = products::where('branchs_id', auth()->user()->branchs_id);

        if ($group !== null && $group !== '' && $group !== 'all') {
            $query->where('product_group', $group);
        }
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('product_name', 'like', "%{$q}%")
                  ->orWhere('name_en', 'like', "%{$q}%")
                  ->orWhere('Product_Code', 'like', "%{$q}%")
                  ->orWhere('refnumber', 'like', "%{$q}%");
            });
        }

        // المنتجات اللي أسماؤها ناقصة (زي "-" أو "????") تيجي في آخر القائمة
        $page = $query->orderByRaw("CASE WHEN product_name REGEXP '^[-.;?\\'\" ]' OR product_name = '' THEN 1 ELSE 0 END")
            ->orderBy('product_name')
            ->paginate(40, ['id', 'product_name', 'name_en', 'Product_Code', 'sale_price', 'numberofpice', 'unit']);

        $isAr = LaravelLocalization::getCurrentLocale() === 'ar';
        // اسم صالح = فيه حرفين أو رقمين على الأقل ومش علامات استفهام (بيانات مستوردة تالفة)
        $valid = fn ($n) => $n && !str_contains($n, '??') && preg_match('/[\p{L}\p{N}]{2,}/u', $n);
        $label = function ($p) use ($isAr, $valid) {
            $first = $isAr ? $p->product_name : ($p->name_en ?: $p->product_name);
            $second = $isAr ? $p->name_en : $p->product_name;
            if ($valid($first)) return $first;
            if ($valid($second)) return $second;
            return ($isAr ? 'منتج بدون اسم' : 'Unnamed product') . ' (' . $p->Product_Code . ')';
        };

        return response()->json([
            'data' => collect($page->items())->map(fn ($p) => [
                'id'    => $p->id,
                'name'  => $label($p),
                'code'  => $p->Product_Code,
                'price' => (float) $p->sale_price,
                'qty'   => (float) $p->numberofpice,
                'unit'  => $p->unit,
            ]),
            'next_page' => $page->hasMorePages() ? $page->currentPage() + 1 : null,
        ]);
    }

    /** إيصال حراري 80mm للفاتورة (بديل ملف الـ PDF في شاشة البيع السريع) */
    public function receipt($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $invoice = invoices::with('customer', 'user')->findOrFail($id);
        abort_unless($invoice->branchs_id == auth()->user()->branchs_id || auth()->user()->can('Users permissions'), 403);

        $items = sales::where('invoice_id', $invoice->id)->where('quantity', '!=', 0)->get();
        $setting = system_setting::find(1);
        $avt = Avt::find(1);
        $vatRate = $avt ? (float) $avt->AVT : 0.15;

        $total = round($invoice->cashamount + $invoice->bankamount + $invoice->Bank_transfer + $invoice->creaditamount, 2);
        $tax = round((float) $invoice->Added_Value, 2);
        $discount = round((float) $invoice->discount, 2);
        $subtotal = round((float) $invoice->Price, 2);

        // QR: لو الفاتورة اتبعتت لزاتكا (المرحلة الثانية) نستخدم الـ QR الرسمي، وإلا QR المرحلة الأولى (TLV)
        $qr = trim((string) ($invoice->qr_zatca ?? ''));
        if ($qr === '' || $qr === '0') {
            $date = $invoice->issue_date && $invoice->issue_time
                ? $invoice->issue_date . 'T' . $invoice->issue_time
                : \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d\TH:i:s');
            $tlv = '';
            foreach ([
                1 => (string) ($setting->name_ar ?? ''),
                2 => (string) ($setting->Tax ?? ''),
                3 => $date,
                4 => number_format($total, 2, '.', ''),
                5 => number_format($tax, 2, '.', ''),
            ] as $tag => $value) {
                $tlv .= chr($tag) . chr(strlen($value)) . $value;
            }
            $qr = base64_encode($tlv);
        }

        return view('sales.pos.receipt', compact('invoice', 'items', 'setting', 'vatRate', 'total', 'tax', 'discount', 'subtotal', 'qr'));
    }
}
