@extends('layouts.master')

@php
    $isAr = app()->getLocale() == 'ar';
    $tr = fn ($ar, $en) => $isAr ? $ar : $en;
    $m = fn ($n) => number_format((float) $n, 2);
    $cur = $tr('ر.س', 'SAR');
    $open = $shift->isOpen();
@endphp

@section('title')
    {{ $tr('تقرير الوردية', 'Shift report') }} {{ $shift->number }}
@endsection

@section('css')
<style>
    .zr { font-family:'Cairo', sans-serif; max-width:760px; margin:20px auto; }
    .zr-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:22px; margin-bottom:16px; }
    .zr-top { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; border-bottom:1px dashed #cbd5e1; padding-bottom:14px; margin-bottom:14px; }
    .zr-top h3 { margin:0; font-weight:800; color:#0f172a; }
    .zr-top .st { border-radius:999px; padding:4px 14px; font-weight:700; font-size:13px; }
    .zr-top .st.open { background:#dcfce7; color:#166534; } .zr-top .st.closed { background:#f1f5f9; color:#475569; }
    .zr-meta { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:6px 24px; font-size:13.5px; }
    .zr-meta div { display:flex; justify-content:space-between; gap:10px; }
    .zr-meta span { color:#64748b; }
    .zr h6 { font-weight:800; color:#0f172a; margin:0 0 10px; font-size:15px; }
    .zr-ln { display:flex; justify-content:space-between; padding:7px 2px; border-bottom:1px solid #f1f5f9; font-size:14px; }
    .zr-ln:last-child { border-bottom:0; }
    .zr-ln.total { font-weight:800; font-size:15.5px; border-top:2px solid #e2e8f0; border-bottom:0; margin-top:4px; }
    .zr-ln.neg b { color:#dc2626; } .zr-ln.pos b { color:#059669; }
    .zr-ln.minus b::before { content:'− '; }
    .zr-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    @media (max-width:767px) { .zr-grid, .zr-meta { grid-template-columns:1fr; } }
    .zr-close label { font-weight:700; font-size:13.5px; display:block; margin-bottom:4px; }
    .zr-close input, .zr-close textarea { width:100%; border:1px solid #e5e7eb; border-radius:9px; padding:8px 12px; font-size:15px; }
    .zr-close .exp { background:#eff6ff; border-radius:10px; padding:10px 14px; display:flex; justify-content:space-between; font-weight:700; color:#1e40af; margin-bottom:12px; }
    .zr-close .diff { font-weight:800; margin-top:8px; min-height:22px; }
    .zr-acts { display:flex; gap:10px; flex-wrap:wrap; }
    .zr-btn { display:inline-flex; align-items:center; justify-content:center; height:42px; padding:0 18px; border-radius:10px; border:0; font-weight:800; cursor:pointer; text-decoration:none !important; font-family:inherit; font-size:14px; }
    .zr-btn.red { background:#dc2626; color:#fff !important; } .zr-btn.blue { background:#2563eb; color:#fff !important; } .zr-btn.soft { background:#f1f5f9; color:#0f172a !important; }
    .zr-sign { display:flex; justify-content:space-between; margin-top:40px; font-size:13px; color:#475569; }
    .zr-print-only { display:none; }
    @media print {
        .main-header, .app-sidebar, .main-footer, .no-print, #global-loader, .side-app .breadcrumb-header { display:none !important; }
        .main-content.app-content, .app-content { margin:0 !important; padding:0 !important; }
        .zr { margin:0 auto; max-width:100%; }
        .zr-card { border:0; padding:6px 0; }
        .zr-print-only { display:flex; }
        body { background:#fff !important; }
    }
</style>
@endsection

@section('content')
<div class="zr">
    <div class="zr-card">
        <div class="zr-top">
            <div>
                <h3>{{ $tr('تقرير الوردية', 'Shift report') }} {{ $shift->number }}</h3>
                <small class="text-muted">{{ $open ? $tr('أرقام لحظية — الوردية لم تُغلق بعد', 'Live figures — shift still open') : $tr('تقرير إغلاق (Z)', 'Closing report (Z)') }}</small>
            </div>
            <span class="st {{ $shift->status }}">{{ $open ? $tr('مفتوحة', 'Open') : $tr('مغلقة', 'Closed') }}</span>
        </div>
        <div class="zr-meta">
            <div><span>{{ $tr('الكاشير', 'Cashier') }}</span><b>{{ $shift->user->name ?? '-' }}</b></div>
            <div><span>{{ $tr('الفرع', 'Branch') }}</span><b>{{ $shift->branch->name ?? '-' }}</b></div>
            <div><span>{{ $tr('بداية الوردية', 'Opened at') }}</span><b dir="ltr">{{ $shift->opened_at->format('Y-m-d H:i') }}</b></div>
            <div><span>{{ $tr('نهاية الوردية', 'Closed at') }}</span><b dir="ltr">{{ $shift->closed_at ? $shift->closed_at->format('Y-m-d H:i') : '-' }}</b></div>
            @if ($shift->closed_at)
                <div><span>{{ $tr('المدة', 'Duration') }}</span><b>{{ $shift->opened_at->diff($shift->closed_at)->format('%H:%I') }} {{ $tr('ساعة', 'h') }}</b></div>
            @endif
            <div><span>{{ $tr('عدد الفواتير', 'Invoices') }}</span><b>{{ $t['invoices_count'] }}</b></div>
        </div>
    </div>

    <div class="zr-grid">
        <div class="zr-card">
            <h6>{{ $tr('المبيعات حسب طريقة الدفع', 'Sales by payment method') }}</h6>
            <div class="zr-ln"><span>{{ $tr('نقدي', 'Cash') }}</span><b>{{ $m($t['cash_sales']) }}</b></div>
            <div class="zr-ln"><span>{{ $tr('شبكة', 'Card') }}</span><b>{{ $m($t['card_sales']) }}</b></div>
            <div class="zr-ln"><span>{{ $tr('تحويل بنكي', 'Bank transfer') }}</span><b>{{ $m($t['transfer_sales']) }}</b></div>
            <div class="zr-ln"><span>{{ $tr('آجل', 'Credit') }}</span><b>{{ $m($t['credit_sales']) }}</b></div>
            <div class="zr-ln total"><span>{{ $tr('إجمالي المبيعات', 'Total sales') }}</span><b>{{ $m($t['total_sales']) }} {{ $cur }}</b></div>
            <div class="zr-ln"><span>{{ $tr('منها ضريبة القيمة المضافة', 'Of which VAT') }}</span><b>{{ $m($t['tax_total']) }}</b></div>
            <div class="zr-ln"><span>{{ $tr('الخصومات', 'Discounts') }}</span><b>{{ $m($t['discount_total']) }}</b></div>
        </div>

        <div class="zr-card">
            <h6>{{ $tr('المرتجعات', 'Returns') }}</h6>
            <div class="zr-ln"><span>{{ $tr('عدد فواتير المرتجع', 'Returned invoices') }}</span><b>{{ $t['returns_count'] }}</b></div>
            <div class="zr-ln"><span>{{ $tr('مرتجع نقدي', 'Cash refunds') }}</span><b>{{ $m($t['cash_returns']) }}</b></div>
            <div class="zr-ln"><span>{{ $tr('مرتجع شبكة', 'Card refunds') }}</span><b>{{ $m($t['card_returns']) }}</b></div>
            <div class="zr-ln total"><span>{{ $tr('إجمالي المرتجعات', 'Total returns') }}</span><b>{{ $m($t['returns_total']) }} {{ $cur }}</b></div>
            <div class="zr-ln total" style="margin-top:14px"><span>{{ $tr('صافي المبيعات', 'Net sales') }}</span><b>{{ $m($t['total_sales'] - $t['returns_total']) }} {{ $cur }}</b></div>
        </div>
    </div>

    <div class="zr-card">
        <h6>{{ $tr('حركة الصندوق (النقدية)', 'Cash drawer') }}</h6>
        <div class="zr-ln"><span>{{ $tr('النقدية الافتتاحية', 'Opening cash') }}</span><b>{{ $m($shift->opening_cash) }}</b></div>
        <div class="zr-ln"><span>{{ $tr('+ المبيعات النقدية', '+ Cash sales') }}</span><b>{{ $m($t['cash_sales']) }}</b></div>
        <div class="zr-ln"><span>{{ $tr('− المرتجع النقدي', '− Cash refunds') }}</span><b>{{ $m($t['cash_returns']) }}</b></div>
        <div class="zr-ln total"><span>{{ $tr('النقدية المتوقعة في الصندوق', 'Expected cash in drawer') }}</span><b>{{ $m($t['expected_cash']) }} {{ $cur }}</b></div>
        @unless ($open)
            <div class="zr-ln"><span>{{ $tr('النقدية الفعلية (الجرد)', 'Counted cash') }}</span><b>{{ $m($shift->counted_cash) }}</b></div>
            <div class="zr-ln {{ $shift->difference < 0 ? 'neg' : ($shift->difference > 0 ? 'pos' : '') }}">
                <span>{{ $shift->difference < 0 ? $tr('عجز', 'Short') : ($shift->difference > 0 ? $tr('زيادة', 'Over') : $tr('الفرق', 'Difference')) }}</span>
                <b>{{ $m(abs($shift->difference)) }} {{ $cur }}</b>
            </div>
            @if ($shift->close_note)
                <div class="zr-ln"><span>{{ $tr('ملاحظات', 'Notes') }}</span><b style="white-space:pre-wrap">{{ $shift->close_note }}</b></div>
            @endif
        @endunless
        <div class="zr-sign zr-print-only">
            <span>{{ $tr('توقيع الكاشير', 'Cashier signature') }}: ..................</span>
            <span>{{ $tr('توقيع المسؤول', 'Supervisor signature') }}: ..................</span>
        </div>
    </div>

    @if ($open)
        <div class="zr-card zr-close no-print">
            <h6>{{ $tr('إغلاق الوردية', 'Close shift') }}</h6>
            <form method="POST" action="{{ url('/shifts/' . $shift->id . '/close') }}" id="zr-close-form">
                @csrf
                <div class="exp"><span>{{ $tr('النقدية المتوقعة', 'Expected cash') }}</span><span id="zr-exp" data-v="{{ $t['expected_cash'] }}">{{ $m($t['expected_cash']) }} {{ $cur }}</span></div>
                <label>{{ $tr('النقدية الفعلية في الصندوق (بعد العد)', 'Counted cash in drawer') }} *</label>
                <input type="number" step="0.01" min="0" name="counted_cash" id="zr-counted" required>
                <div class="diff" id="zr-diff"></div>
                <label style="margin-top:8px">{{ $tr('ملاحظات', 'Notes') }}</label>
                <textarea name="note" rows="2"></textarea>
                <div class="zr-acts" style="margin-top:14px">
                    <button type="submit" class="zr-btn red" onclick="return confirm(@json($tr('تأكيد إغلاق الوردية؟ لا يمكن التراجع.', 'Close this shift? This cannot be undone.')))">{{ $tr('إغلاق الوردية', 'Close shift') }}</button>
                </div>
            </form>
        </div>
    @endif

    <div class="zr-acts no-print">
        <button type="button" class="zr-btn blue" onclick="window.print()">{{ $tr('طباعة التقرير', 'Print report') }}</button>
        <a class="zr-btn soft" href="{{ url('/shifts') }}">{{ $tr('سجل الورديات', 'Shifts history') }}</a>
        <a class="zr-btn soft" href="{{ url('/pos') }}">{{ $tr('شاشة البيع السريع', 'Quick sales screen') }}</a>
    </div>
</div>
@endsection

@section('js')
@if ($open)
<script>
(function () {
    var inp = document.getElementById('zr-counted'), out = document.getElementById('zr-diff');
    var exp = parseFloat(document.getElementById('zr-exp').dataset.v) || 0;
    var L = { short: @json($tr('عجز', 'Short')), over: @json($tr('زيادة', 'Over')), ok: @json($tr('مطابق ✓', 'Balanced ✓')), cur: @json($cur) };
    inp.addEventListener('input', function () {
        var v = parseFloat(inp.value);
        if (isNaN(v)) { out.textContent = ''; return; }
        var d = Math.round((v - exp) * 100) / 100;
        out.style.color = d < 0 ? '#dc2626' : (d > 0 ? '#059669' : '#059669');
        out.textContent = d === 0 ? L.ok : ((d < 0 ? L.short : L.over) + ': ' + Math.abs(d).toFixed(2) + ' ' + L.cur);
    });
})();
</script>
@endif
@endsection
