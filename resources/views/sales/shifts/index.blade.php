@extends('layouts.master')

@php
    $isAr = app()->getLocale() == 'ar';
    $t = fn ($ar, $en) => $isAr ? $ar : $en;
    $m = fn ($n) => number_format((float) $n, 2);
@endphp

@section('title')
    {{ $t('ورديات الكاشير', 'Cashier shifts') }}
@endsection

@section('css')
<style>
    .sh { font-family:'Cairo', sans-serif; }
    .sh-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:18px; margin-bottom:18px; }
    .sh-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
    .sh-head h4 { margin:0; font-weight:800; color:#0f172a; }
    .sh-cur { display:flex; align-items:center; flex-wrap:wrap; gap:10px; }
    .sh-badge { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:4px 12px; font-size:12.5px; font-weight:700; }
    .sh-badge.open { background:#dcfce7; color:#166534; }
    .sh-badge.closed { background:#f1f5f9; color:#475569; }
    .sh-badge.open::before { content:''; width:8px; height:8px; border-radius:50%; background:#16a34a; }
    .sh-btn { display:inline-flex; align-items:center; height:38px; padding:0 16px; border-radius:9px; border:0; font-weight:700; font-size:13.5px; cursor:pointer; text-decoration:none !important; font-family:inherit; }
    .sh-btn.green { background:#059669; color:#fff !important; } .sh-btn.green:hover { background:#047857; }
    .sh-btn.blue { background:#2563eb; color:#fff !important; } .sh-btn.blue:hover { background:#1d4ed8; }
    .sh-btn.soft { background:#f1f5f9; color:#0f172a !important; }
    .sh-open-form { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    .sh-open-form input { height:38px; border:1px solid #e5e7eb; border-radius:9px; padding:0 12px; width:170px; }
    .sh-filters { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
    .sh-filters label { display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px; }
    .sh-filters .form-control { height:38px; border-radius:9px; min-width:150px; }
    .sh-table { width:100%; font-size:13px; border-collapse:collapse; }
    .sh-table th { background:#f8fafc; color:#334155; font-weight:700; padding:10px 8px; white-space:nowrap; text-align:start; border-bottom:1px solid #e5e7eb; }
    .sh-table td { padding:9px 8px; border-bottom:1px solid #f1f5f9; white-space:nowrap; }
    .sh-table tr:hover td { background:#fafafa; }
    .sh-diff.neg { color:#dc2626; font-weight:700; } .sh-diff.pos { color:#059669; font-weight:700; }
    .sh-empty { text-align:center; color:#94a3b8; padding:30px; }
</style>
@endsection

@section('content')
<div class="sh" style="padding-top:20px">
    <div class="sh-card">
        <div class="sh-head">
            <h4>{{ $t('ورديات الكاشير', 'Cashier shifts') }}</h4>
            <div class="sh-cur">
                @if ($current)
                    <span class="sh-badge open">{{ $t('ورديتك مفتوحة', 'Your shift is open') }} #{{ $current->number }}</span>
                    <small class="text-muted">{{ $t('منذ', 'since') }} {{ $current->opened_at->format('Y-m-d H:i') }}</small>
                    <a class="sh-btn blue" href="{{ url('/shifts/' . $current->id) }}">{{ $t('التقرير وإغلاق الوردية', 'Report & close shift') }}</a>
                @else
                    <form class="sh-open-form" method="POST" action="{{ url('/shifts/open') }}">
                        @csrf
                        <input type="number" step="0.01" min="0" name="opening_cash" placeholder="{{ $t('النقدية الافتتاحية', 'Opening cash') }}" required>
                        <button type="submit" class="sh-btn green">{{ $t('فتح وردية جديدة', 'Open new shift') }}</button>
                    </form>
                @endif
                <a class="sh-btn soft" href="{{ url('/pos') }}">{{ $t('شاشة البيع السريع', 'Quick sales screen') }}</a>
            </div>
        </div>
    </div>

    <div class="sh-card">
        <form class="sh-filters" method="GET" action="{{ url('/shifts') }}">
            <div><label>{{ $t('من تاريخ', 'From') }}</label><input type="date" name="from" class="form-control" value="{{ request('from') }}"></div>
            <div><label>{{ $t('إلى تاريخ', 'To') }}</label><input type="date" name="to" class="form-control" value="{{ request('to') }}"></div>
            @if ($all)
                <div><label>{{ $t('الكاشير', 'Cashier') }}</label>
                    <select name="user_id" class="form-control">
                        <option value="">{{ $t('الكل', 'All') }}</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div><label>{{ $t('الحالة', 'Status') }}</label>
                <select name="status" class="form-control">
                    <option value="">{{ $t('الكل', 'All') }}</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ $t('مفتوحة', 'Open') }}</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ $t('مغلقة', 'Closed') }}</option>
                </select>
            </div>
            <div><button type="submit" class="sh-btn blue">{{ $t('بحث', 'Search') }}</button></div>
        </form>
    </div>

    <div class="sh-card" style="overflow-x:auto">
        <table class="sh-table">
            <thead><tr>
                <th>{{ $t('رقم الوردية', 'Shift') }}</th>
                <th>{{ $t('الكاشير', 'Cashier') }}</th>
                <th>{{ $t('الفرع', 'Branch') }}</th>
                <th>{{ $t('الفتح', 'Opened') }}</th>
                <th>{{ $t('الإغلاق', 'Closed') }}</th>
                <th>{{ $t('عدد الفواتير', 'Invoices') }}</th>
                <th>{{ $t('إجمالي المبيعات', 'Total sales') }}</th>
                <th>{{ $t('نقدي', 'Cash') }}</th>
                <th>{{ $t('شبكة', 'Card') }}</th>
                <th>{{ $t('النقدية المتوقعة', 'Expected cash') }}</th>
                <th>{{ $t('النقدية الفعلية', 'Counted cash') }}</th>
                <th>{{ $t('العجز / الزيادة', 'Over / short') }}</th>
                <th>{{ $t('الحالة', 'Status') }}</th>
                <th></th>
            </tr></thead>
            <tbody>
            @forelse ($shifts as $s)
                <tr>
                    <td><b>{{ $s->number }}</b></td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>{{ $s->branch->name ?? '-' }}</td>
                    <td>{{ $s->opened_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $s->closed_at ? $s->closed_at->format('Y-m-d H:i') : '-' }}</td>
                    @if ($s->isOpen())
                        <td colspan="6" class="text-muted">{{ $t('تُحسب الأرقام عند الإغلاق — افتح التقرير لرؤية الأرقام الحالية', 'Figures are calculated on close — open the report for live figures') }}</td>
                    @else
                        <td>{{ $s->invoices_count }}</td>
                        <td>{{ $m($s->total_sales) }}</td>
                        <td>{{ $m($s->cash_sales) }}</td>
                        <td>{{ $m($s->card_sales) }}</td>
                        <td>{{ $m($s->expected_cash) }}</td>
                        <td>{{ $m($s->counted_cash) }}</td>
                    @endif
                    <td>
                        @if (!$s->isOpen())
                            <span class="sh-diff {{ $s->difference < 0 ? 'neg' : ($s->difference > 0 ? 'pos' : '') }}">{{ $m($s->difference) }}</span>
                        @endif
                    </td>
                    <td><span class="sh-badge {{ $s->status }}">{{ $s->isOpen() ? $t('مفتوحة', 'Open') : $t('مغلقة', 'Closed') }}</span></td>
                    <td><a class="sh-btn soft" style="height:30px;padding:0 10px;font-size:12px" href="{{ url('/shifts/' . $s->id) }}">{{ $t('التقرير', 'Report') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="14" class="sh-empty">{{ $t('لا توجد ورديات', 'No shifts yet') }}</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $shifts->links() }}</div>
    </div>
</div>
@endsection
