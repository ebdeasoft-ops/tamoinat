<?php

namespace App\Http\Controllers;

use App\Models\CashierShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * ورديات الكاشير: فتح وردية بمبلغ افتتاحي، إغلاقها بجرد النقدية، وتقرير الوردية (Z-Report).
 */
class CashierShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Sales products');
        $this->middleware(function ($request, $next) {
            CashierShift::createTable();      // ينشئ الجدول تلقائياً لو لم يتم تشغيل migrate
            app()->setLocale(LaravelLocalization::getCurrentLocale());
            return $next($request);
        });
    }

    /** المدير (صلاحية المستخدمين) يرى ويغلق ورديات الجميع */
    private function canManageAll(): bool
    {
        $u = auth()->user();
        return $u->can('Users permissions') || $u->can('List of users');
    }

    private function authorizeShift(CashierShift $shift): void
    {
        abort_unless($shift->user_id == auth()->id() || $this->canManageAll(), 403);
    }

    private function payload(?CashierShift $shift): array
    {
        if (!$shift) {
            return ['open' => false];
        }
        $totals = $shift->computeTotals();
        return [
            'open'         => true,
            'id'           => $shift->id,
            'number'       => $shift->number,
            'opened_at'    => $shift->opened_at->format('Y-m-d H:i'),
            'opening_cash' => (float) $shift->opening_cash,
            'totals'       => $totals,
            'report_url'   => url('/shifts/' . $shift->id),
        ];
    }

    /** الوردية المفتوحة للمستخدم الحالي + إجمالياتها الحية */
    public function status()
    {
        return response()->json($this->payload(CashierShift::currentFor(auth()->id())));
    }

    public function open(Request $request)
    {
        $data = $request->validate([
            'opening_cash' => 'nullable|numeric|min:0',
            'note'         => 'nullable|string|max:500',
        ]);

        $shift = CashierShift::currentFor(auth()->id());          // لو مفتوحة بالفعل تُرجع نفسها
        $shift = $shift ?: CashierShift::create([
            'user_id'      => auth()->id(),
            'branchs_id'   => auth()->user()->branchs_id,
            'opened_at'    => Carbon::now('Asia/Riyadh'),
            'status'       => 'open',
            'opening_cash' => round((float) ($data['opening_cash'] ?? 0), 2),
            'open_note'    => $data['note'] ?? null,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($this->payload($shift));
        }
        return redirect('/shifts');
    }

    public function close(Request $request, $id)
    {
        $shift = CashierShift::findOrFail($id);
        $this->authorizeShift($shift);

        $data = $request->validate([
            'counted_cash' => 'required|numeric|min:0',
            'note'         => 'nullable|string|max:1000',
        ]);

        if ($shift->isOpen()) {
            $now = Carbon::now('Asia/Riyadh');
            $totals = $shift->computeTotals($now);
            $counted = round((float) $data['counted_cash'], 2);
            $shift->fill($totals + [
                'closed_at'    => $now,
                'status'       => 'closed',
                'counted_cash' => $counted,
                'difference'   => round($counted - $totals['expected_cash'], 2),
                'close_note'   => $data['note'] ?? null,
            ])->save();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'report_url' => url('/shifts/' . $shift->id)]);
        }
        return redirect('/shifts/' . $shift->id);
    }

    /** سجل الورديات */
    public function index(Request $request)
    {
        $all = $this->canManageAll();

        $query = CashierShift::with('user:id,name', 'branch:id,name')->latest('id');
        if (!$all) {
            $query->where('user_id', auth()->id());
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('opened_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('opened_at', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shifts = $query->paginate(30)->withQueryString();
        $users = $all ? User::orderBy('name')->get(['id', 'name']) : collect();
        $current = CashierShift::currentFor(auth()->id());

        return view('sales.shifts.index', compact('shifts', 'users', 'all', 'current'));
    }

    /** تقرير الوردية (يُطبع) */
    public function show($id)
    {
        $shift = CashierShift::with('user:id,name', 'branch:id,name')->findOrFail($id);
        $this->authorizeShift($shift);

        // الوردية المفتوحة تُعرض بأرقام لحظية
        $t = $shift->isOpen() ? $shift->computeTotals() : $shift->only([
            'invoices_count', 'cash_sales', 'card_sales', 'transfer_sales', 'credit_sales', 'total_sales',
            'tax_total', 'discount_total', 'returns_count', 'returns_total', 'cash_returns', 'card_returns', 'expected_cash',
        ]);

        return view('sales.shifts.show', ['shift' => $shift, 't' => $t]);
    }
}
