<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * تعديل سريع للمنتج من صفحة المخزون: مسح الباركود ← يظهر المنتج ← تعديل بياناته وسعره.
 * الكمية مش بتتعدل من هنا (بتتعدل من الجرد / تسوية المخزون علشان القيود).
 */
class ProductQuickEditController extends Controller
{
    private const FIELDS = [
        'product_name', 'name_en', 'Product_Code', 'refnumber', 'sale_price', 'purchasing_price',
        'Wholesale_price', 'unit', 'product_group', 'Product_Location', 'minmum_quantity_stock_alart', 'notes',
    ];

    /** الحقول اللي بتتطبق على نفس المنتج في باقي الفروع لو اخترت "كل الفروع" (الموقع وحد الطلب خاصين بكل فرع) */
    private const SHARED_FIELDS = [
        'product_name', 'name_en', 'refnumber', 'sale_price', 'purchasing_price', 'Wholesale_price', 'unit', 'product_group', 'notes',
    ];

    private function isAr(): bool
    {
        return LaravelLocalization::getCurrentLocale() === 'ar';
    }

    private function payload(products $p): array
    {
        $data = ['id' => $p->id, 'numberofpice' => (float) $p->numberofpice, 'branch' => optional($p->branch)->name];
        foreach (self::FIELDS as $f) {
            $data[$f] = $p->{$f};
        }
        $data['other_branches'] = products::where('Product_Code', $p->Product_Code)->where('id', '!=', $p->id)->count();
        return $data;
    }

    /** البحث بالباركود / رقم القطعة / الرقم البديل في فرع المستخدم */
    public function lookup(Request $request)
    {
        abort_unless(auth()->user()->can('Produects') || auth()->user()->can('Product data change'), 403);

        $code = trim((string) $request->input('code', ''));
        if ($code === '') {
            return response()->json(['found' => false]);
        }
        $branchId = $request->input('branch_id');
        $branchId = is_numeric($branchId) ? (int) $branchId : auth()->user()->branchs_id;

        $base = products::where('branchs_id', $branchId);
        $p = (clone $base)->where('Product_Code', $code)->first()
            ?: (clone $base)->where('refnumber', $code)->first()
            ?: (clone $base)->where('id', ctype_digit($code) ? (int) $code : 0)->first();

        if (!$p) {
            return response()->json([
                'found' => false,
                'message' => $this->isAr() ? 'المنتج غير مسجل في هذا الفرع: ' . $code : 'Product not found in this branch: ' . $code,
            ]);
        }

        return response()->json(['found' => true, 'product' => $this->payload($p)]);
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->can('Product data change'), 403);

        $p = products::findOrFail($id);
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'Product_Code' => 'required|string|max:255',
            'refnumber' => 'nullable|string|max:255',
            'sale_price' => 'required|numeric|min:0',
            'purchasing_price' => 'required|numeric|min:0',
            'Wholesale_price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:255',
            'product_group' => 'nullable|integer',
            'Product_Location' => 'nullable|string|max:255',
            'minmum_quantity_stock_alart' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'all_branches' => 'nullable|boolean',
        ]);

        $code = trim($data['Product_Code']);
        $duplicate = products::where('branchs_id', $p->branchs_id)->where('Product_Code', $code)->where('id', '!=', $p->id)->exists();
        if ($duplicate) {
            return response()->json([
                'ok' => false,
                'message' => $this->isAr() ? 'رقم القطعة ' . $code . ' مستخدم لمنتج تاني في نفس الفرع' : 'Code ' . $code . ' is used by another product in this branch',
            ], 422);
        }

        $values = [
            'product_name' => trim($data['product_name']),
            'name_en' => $data['name_en'] ?? '',
            'Product_Code' => $code,
            'refnumber' => $data['refnumber'] ?? null,
            'sale_price' => (float) $data['sale_price'],
            'purchasing_price' => (float) $data['purchasing_price'],
            'Wholesale_price' => (float) ($data['Wholesale_price'] ?? 0),
            'unit' => $data['unit'] ?: ($p->unit ?: 'piece'),
            'product_group' => $data['product_group'] ?? $p->product_group,
            'Product_Location' => ($data['Product_Location'] ?? '') !== '' ? $data['Product_Location'] : '-',
            'minmum_quantity_stock_alart' => (int) ($data['minmum_quantity_stock_alart'] ?? $p->minmum_quantity_stock_alart),
            'notes' => ($data['notes'] ?? '') !== '' ? $data['notes'] : '-',
        ];

        $oldCode = $p->Product_Code;
        $updatedOthers = 0;

        DB::transaction(function () use ($p, $values, $oldCode, $request, &$updatedOthers) {
            $p->update($values);

            if ($request->boolean('all_branches')) {
                $shared = array_intersect_key($values, array_flip(self::SHARED_FIELDS));
                $others = products::where('Product_Code', $oldCode)->where('id', '!=', $p->id);
                // تغيير رقم القطعة يتطبق في الفروع التانية بس لو مش متعارض مع منتج تاني هناك
                if ($values['Product_Code'] !== $oldCode) {
                    $others->get()->each(function ($o) use ($values, $shared) {
                        $clash = products::where('branchs_id', $o->branchs_id)->where('Product_Code', $values['Product_Code'])->where('id', '!=', $o->id)->exists();
                        $o->update($shared + ($clash ? [] : ['Product_Code' => $values['Product_Code']]));
                    });
                    $updatedOthers = products::where('Product_Code', $values['Product_Code'])->where('id', '!=', $p->id)->count();
                } else {
                    $updatedOthers = $others->update($shared);
                }
            }
        });

        $msg = $this->isAr() ? 'تم حفظ بيانات المنتج' : 'Product saved';
        if ($updatedOthers) {
            $msg .= $this->isAr() ? ' (وتم تحديث ' . $updatedOthers . ' فرع آخر)' : ' (and ' . $updatedOthers . ' other branch(es))';
        }

        return response()->json(['ok' => true, 'message' => $msg, 'product' => $this->payload($p->fresh())]);
    }
}
