@extends('layouts.master')

@php
    $isAr = app()->getLocale() == 'ar';
    $t = fn ($ar, $en) => $isAr ? $ar : $en;
    $cur = $t('ر.س', 'SAR');
@endphp

@section('title')
    {{ $t('شاشة المبيعات السريعة', 'Quick sales screen') }}
@endsection

@section('css')
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    .pos { --navy:#16213e; --blue:#2563eb; --green:#059669; --line:#e5e7eb; --muted:#64748b; --soft:#f8fafc; font-family:'Cairo', sans-serif; padding-top:24px; }
    .pos * { box-sizing:border-box; }

    /* القائمة الجانبية مقفولة في شاشة البيع السريع (تفتح بزر القائمة) */
    body.pos-mode .app-sidebar { transition: transform .25s ease; transform: translateX(100%); }
    body.pos-mode.pos-sb-left .app-sidebar { transform: translateX(-100%); }
    body.pos-mode.pos-sidebar-open .app-sidebar { transform: none !important; }
    body.pos-mode .app-sidebar { z-index: 1050 !important; visibility: visible !important; }
    body.pos-mode .app-sidebar .app-sidebar { transform: none !important; }   /* القائمة متداخلة مرتين في القالب */
    body.pos-mode .app-sidebar__toggle .close-toggle { display: none !important; }
    body.pos-mode .app-sidebar__toggle .open-toggle { display: inline-flex !important; }
    body.pos-mode.pos-sidebar-open .app-sidebar__toggle .close-toggle { display: inline-flex !important; }
    body.pos-mode.pos-sidebar-open .app-sidebar__toggle .open-toggle { display: none !important; }
    body.pos-mode .main-content.app-content, body.pos-mode .app-content { margin-right: 0 !important; margin-left: 0 !important; }
    .pos-menu-btn { display:inline-flex; align-items:center; gap:6px; height:38px; padding:0 12px; border-radius:9px; border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.08); color:#fff; font-weight:700; font-size:13px; cursor:pointer; font-family:inherit; white-space:nowrap; }
    .pos-menu-btn:hover { background:rgba(255,255,255,.18); }
    .pos-backdrop { display:none; position:fixed; inset:0; background:rgba(15,23,42,.35); z-index:1040; }
    /* الشاشات الصغيرة: القائمة فوق المحتوى مع خلفية داكنة */
    @media (max-width: 991px) { body.pos-sidebar-open .pos-backdrop { display:block; } }
    /* الشاشات الكبيرة: المحتوى يتزحزح جنب القائمة بدل ما تغطيه */
    @media (min-width: 992px) {
        body.pos-mode .main-content.app-content, body.pos-mode .sb-header { transition: margin .25s ease, padding .25s ease; }
        html body.pos-mode.pos-sidebar-open:not(.pos-sb-left) .main-content.app-content { margin-right: 250px !important; margin-left: 0 !important; }
        html body.pos-mode.pos-sidebar-open.pos-sb-left .main-content.app-content { margin-left: 250px !important; margin-right: 0 !important; }
        html body.pos-mode.pos-sidebar-open:not(.pos-sb-left) .sb-header { padding-right: 250px !important; padding-left: 0 !important; }
        html body.pos-mode.pos-sidebar-open.pos-sb-left .sb-header { padding-left: 250px !important; padding-right: 0 !important; }
    }

    /* الشريط العلوي */
    .pos-bar { background:var(--navy); border-radius:14px; padding:16px 20px; display:flex; align-items:center; gap:16px; margin-bottom:24px; flex-wrap:wrap; }
    .pos-bar h2 { color:#fff; font-size:19px; font-weight:800; margin:0; white-space:nowrap; }
    .pos-bar .pos-link { color:#93c5fd; font-size:12.5px; text-decoration:underline; white-space:nowrap; }
    .pos-bar .pos-link:hover { color:#fff; }
    .pos-scan { flex:1; min-width:260px; position:relative; }
    .pos-scan input { width:100%; height:52px; border-radius:10px; border:3px solid #3b82f6; padding:0 18px; font-size:19px; background:#fff; outline:none; }
    .pos-scan input:focus { border-color:#60a5fa; box-shadow:0 0 0 4px rgba(96,165,250,.25); }
    .pos-scan .pos-scan-msg { position:absolute; top:100%; inset-inline-start:0; margin-top:6px; font-size:12.5px; font-weight:700; padding:4px 10px; border-radius:6px; display:none; z-index:5; }
    .pos-scan .pos-scan-msg.err { display:block; background:#fee2e2; color:#b91c1c; }
    .pos-scan .pos-scan-msg.ok { display:block; background:#dcfce7; color:#166534; }

    /* الأعمدة */
    .pos-grid { display:grid; grid-template-columns:200px minmax(0,1fr) minmax(0,1.3fr); gap:20px; align-items:start; }
    @media (max-width:1199px) { .pos-grid { grid-template-columns:170px minmax(0,1fr) minmax(0,1.2fr); } }
    @media (max-width:991px)  { .pos-grid { grid-template-columns:1fr; } }
    .pos-card { background:#fff; border:1px solid var(--line); border-radius:12px; padding:16px; }

    /* الفئات */
    .pos-cats h6 { font-weight:800; font-size:15px; margin:0 0 10px; color:#0f172a; }
    .pos-cat { display:block; width:100%; text-align:center; border:0; background:transparent; padding:9px 8px; border-radius:8px; font-size:14px; color:#334155; cursor:pointer; margin-bottom:4px; font-family:inherit; }
    .pos-cat:hover { background:var(--soft); }
    .pos-cat.active { background:#eff6ff; color:var(--blue); font-weight:800; }
    @media (max-width:991px) { .pos-cats-list { display:flex; flex-wrap:wrap; gap:6px; } .pos-cat { width:auto; margin:0; } }

    /* المنتجات */
    .pos-prod-head { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
    .pos-prod-head input { flex:1; height:42px; border:1px solid var(--line); border-radius:8px; padding:0 12px; font-size:14px; outline:none; }
    .pos-prod-head input:focus { border-color:#93c5fd; }
    .pos-prod-head span { font-size:12.5px; color:var(--muted); width:62px; text-align:center; }
    .pos-products { max-height:calc(100vh - 290px); min-height:420px; overflow-y:auto; margin:0 -8px; padding:0 8px; }
    .pos-prod { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:11px 8px; border-bottom:1px solid #f1f5f9; cursor:pointer; border-radius:6px; }
    .pos-prod:hover { background:var(--soft); }
    .pos-prod .nm { font-weight:700; font-size:14px; color:#0f172a; line-height:1.4; }
    .pos-prod .meta { font-size:12px; color:var(--muted); direction:ltr; text-align:end; }
    [dir=rtl] .pos-prod .meta { text-align:right; direction:rtl; }
    .pos-prod .meta .neg { color:#dc2626; }
    .pos-price { white-space:nowrap; font-size:12.5px; color:var(--blue); background:#eff6ff; border:1px solid #dbeafe; border-radius:999px; padding:3px 10px; font-weight:600; }
    .pos-loading, .pos-none { text-align:center; color:var(--muted); padding:20px; font-size:13px; }

    /* السلة */
    .pos-cust label { font-size:13px; font-weight:700; color:#0f172a; }
    .pos-cust .row-flex { display:flex; gap:8px; }
    .pos-cust .select2-container { flex:1; }
    .pos-cust .select2-container .select2-selection--single { height:40px; border:1px solid var(--line); border-radius:8px; }
    .pos-cust .select2-container .select2-selection__rendered { line-height:38px; }
    .pos-cust .select2-container .select2-selection__arrow { height:38px; }
    .pos-btn-soft { background:var(--soft); border:1px solid var(--line); border-radius:8px; padding:0 12px; font-size:12.5px; font-weight:700; white-space:nowrap; cursor:pointer; color:#0f172a; font-family:inherit; }
    .pos-btn-soft:hover { background:#eef2f7; }
    .pos-cart { border:1px solid var(--line); border-radius:10px; margin:14px 0; overflow:hidden; }
    .pos-cart table { width:100%; border-collapse:collapse; font-size:13px; }
    .pos-cart thead th { position:sticky; top:0; z-index:1; background:var(--soft); font-weight:700; color:#334155; padding:10px; text-align:start; }
    .pos-cart thead th.c, .pos-cart td.c { text-align:center; }
    .pos-cart tbody td { padding:8px 10px; border-top:1px solid #f1f5f9; vertical-align:middle; }
    .pos-cart-scroll { max-height:340px; overflow-y:auto; }
    .pos-cart .pn { font-weight:700; color:#0f172a; }
    .pos-cart .pp { font-size:11.5px; color:var(--muted); display:inline-flex; align-items:center; gap:3px; white-space:nowrap; }
    .pos-cart .pp input { width:70px; border:1px solid transparent; border-radius:4px; font-size:11.5px; color:var(--muted); padding:0 3px; background:transparent; }
    .pos-cart .pp input:hover, .pos-cart .pp input:focus { border-color:var(--line); background:#fff; outline:none; }
    .pos-qty { display:inline-flex; align-items:center; gap:4px; }
    .pos-qty button { width:26px; height:26px; border-radius:50%; border:1px solid var(--line); background:#fff; cursor:pointer; line-height:1; font-size:15px; }
    .pos-qty button:hover { background:var(--soft); }
    .pos-qty input { width:48px; text-align:center; border:1px solid var(--line); border-radius:6px; height:28px; font-size:13px; }
    .pos-del { border:0; background:transparent; color:#dc2626; cursor:pointer; font-size:14px; }
    .pos-empty { text-align:center; color:#94a3b8; padding:30px 10px; font-size:13px; }
    .pos-totals .ln { display:flex; justify-content:space-between; font-size:13px; color:#334155; padding:2px 4px; }
    .pos-totals .grand { display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--line); margin-top:8px; padding:10px 4px 0; }
    .pos-totals .grand b { font-size:15px; }
    .pos-totals .grand span { font-size:20px; font-weight:800; color:var(--blue); }
    .pos-paid { border:1px solid var(--line); border-radius:10px; padding:14px; margin:14px 0; }
    .pos-paid .ttl { font-size:13px; font-weight:800; margin-bottom:8px; }
    .pos-paid .ttl small { color:var(--muted); font-weight:500; }
    .pos-paid .row-flex { display:flex; gap:8px; }
    .pos-paid input { flex:1; height:40px; border:1px solid var(--line); border-radius:8px; padding:0 12px; font-size:15px; outline:none; }
    .pos-quick { display:grid; grid-template-columns:repeat(6,1fr); gap:8px; margin-top:10px; }
    .pos-quick button { height:38px; border:1px solid var(--line); background:#fff; border-radius:8px; font-weight:700; cursor:pointer; font-family:inherit; }
    .pos-quick button:hover { background:var(--soft); }
    .pos-change { margin-top:10px; display:none; justify-content:space-between; font-weight:800; font-size:15px; padding:8px 12px; border-radius:8px; }
    .pos-change.pos { display:flex; background:#ecfdf5; color:#047857; }
    .pos-change.neg { display:flex; background:#fef2f2; color:#b91c1c; }
    .pos-pay { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .pos-pay button { height:46px; border:0; border-radius:10px; color:#fff; font-size:17px; font-weight:800; cursor:pointer; font-family:inherit; }
    .pos-pay .cash { background:var(--green); } .pos-pay .cash:hover { background:#047857; }
    .pos-pay .pay-card { background:var(--blue); } .pos-pay .pay-card:hover { background:#1d4ed8; }
    .pos-pay button[disabled], .pos-save[disabled] { opacity:.6; cursor:wait; }
    .pos-save { width:100%; height:42px; margin-top:12px; border:0; border-radius:10px; background:#f1f5f9; font-size:16px; font-weight:800; color:#0f172a; cursor:pointer; font-family:inherit; }
    .pos-save:hover { background:#e2e8f0; }
    .pos-save small { color:var(--muted); font-weight:600; font-size:12px; }
    .pos-hint { text-align:center; font-size:11.5px; color:var(--muted); margin-top:10px; }

    /* النوافذ */
    .pos-modal { position:fixed; inset:0; background:rgba(15,23,42,.45); display:none; align-items:center; justify-content:center; z-index:2000; padding:16px; }
    .pos-modal.show { display:flex; }
    .pos-modal .box { background:#fff; border-radius:14px; width:100%; max-width:420px; padding:22px; box-shadow:0 20px 50px rgba(0,0,0,.2); }
    .pos-modal h5 { font-weight:800; margin:0 0 14px; font-size:17px; }
    .pos-modal .sum { background:var(--soft); border-radius:10px; padding:12px 14px; margin-bottom:16px; }
    .pos-modal .sum div { display:flex; justify-content:space-between; font-size:14px; padding:2px 0; }
    .pos-modal .sum .big { font-size:18px; font-weight:800; color:var(--blue); }
    .pos-modal .acts { display:flex; gap:10px; }
    .pos-modal .acts button, .pos-modal .acts a { flex:1; height:42px; border-radius:10px; border:0; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; text-decoration:none; font-family:inherit; font-size:14px; }
    .pos-modal .b-ok { background:var(--green); color:#fff; } .pos-modal .b-blue { background:var(--blue); color:#fff !important; }
    .pos-modal .b-cancel { background:#f1f5f9; color:#0f172a; }
    .pos-modal .fld { margin-bottom:10px; } .pos-modal .fld label { font-size:13px; font-weight:700; display:block; margin-bottom:4px; }
    .pos-modal .fld input { width:100%; height:40px; border:1px solid var(--line); border-radius:8px; padding:0 12px; }
    .pos-modal .ok-icon { width:56px; height:56px; border-radius:50%; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; font-size:26px; margin:0 auto 10px; }
    .pos-modal .zatca { text-align:center; font-size:12.5px; color:var(--muted); margin-bottom:14px; }

    /* خط أتقل وأوضح في الشاشة كلها */
    .pos { font-weight: 600; color: #0f172a; }
    .pos-prod .nm { font-weight: 800; font-size: 15px; }
    .pos-prod .meta { color: #475569; font-weight: 600; font-size: 12.5px; }
    .pos-price { font-weight: 800; font-size: 13px; }
    .pos-cat { font-weight: 700; font-size: 14.5px; color: #1e293b; }
    .pos-cart table { font-size: 14px; }
    .pos-cart .pn { font-weight: 800; font-size: 14px; }
    .pos-cart .pp, .pos-cart .pp input { font-weight: 700; color: #475569; }
    .pos-cart thead th { font-weight: 800; color: #1e293b; }
    .pos-qty input { font-weight: 800; }
    .pos-totals .ln { font-weight: 700; font-size: 14px; color: #1e293b; }
    .pos-totals .grand b { font-size: 17px; font-weight: 800; }
    .pos-totals .grand span { font-size: 22px; font-weight: 900; }
    .pos-paid .ttl, .pos-cust label { font-weight: 800; }
    .pos-quick button, .pos-btn-soft { font-weight: 800; font-size: 14px; }
    .pos-hint { font-weight: 600; color: #475569; }
    .pos-empty { font-weight: 600; color: #64748b; }
    .pos-scan input { font-weight: 700; }
    .pos-prod-head input { font-weight: 600; }

    /* الوردية */
    .pos-shift { display:inline-flex; align-items:center; gap:8px; height:38px; padding:0 14px; border-radius:999px; border:0; font-weight:700; font-size:13px; cursor:pointer; white-space:nowrap; font-family:inherit; }
    .pos-shift.off { background:#f59e0b; color:#1f2937; }
    .pos-shift.off:hover { background:#fbbf24; }
    .pos-shift.on { background:rgba(16,185,129,.18); color:#a7f3d0; border:1px solid rgba(16,185,129,.45); }
    .pos-shift.on:hover { background:rgba(16,185,129,.3); color:#fff; }
    .pos-shift.on::before { content:''; width:8px; height:8px; border-radius:50%; background:#34d399; box-shadow:0 0 0 3px rgba(52,211,153,.25); }
    .pos-shift.loading { background:rgba(255,255,255,.1); color:#cbd5e1; cursor:default; }
    .pos-modal .box.wide { max-width:480px; }
    .pos-modal .note { font-size:12.5px; color:var(--muted); margin:-6px 0 14px; }
    .pos-modal .sum .sep { border-top:1px dashed #cbd5e1; margin:6px 0; padding:0; }
    .pos-modal .diff { font-weight:800; font-size:14px; min-height:20px; margin:-4px 0 10px; }
    .pos-modal .fld textarea { width:100%; border:1px solid var(--line); border-radius:8px; padding:8px 12px; font-family:inherit; }
    .pos-modal .b-red { background:#dc2626; color:#fff; }
    .pos-modal .lnk { display:block; text-align:center; margin-top:12px; font-size:12.5px; }

    /* تثبيت شكل الحقول والأزرار داخل النوافذ حتى لا تغيّرها تنسيقات القالب */
    .pos-modal .fld input, .pos-modal .fld textarea { display:block !important; width:100% !important; box-sizing:border-box !important; height:42px !important; margin:0 !important; padding:6px 12px !important; line-height:1.5 !important;
        border:1px solid #cbd5e1 !important; border-radius:8px !important; background:#fff !important; color:#0f172a !important; font-size:14px !important;
        opacity:1 !important; visibility:visible !important; box-shadow:none; outline:none; }
    .pos-modal .fld textarea { height:auto !important; min-height:60px; }
    .pos-modal .fld input:focus, .pos-modal .fld textarea:focus { border-color:#3b82f6 !important; box-shadow:0 0 0 3px rgba(59,130,246,.18) !important; }
    .pos-modal .acts > button, .pos-modal .acts > a { display:flex !important; opacity:1 !important; visibility:visible !important; min-height:42px; }
    .pos-modal .acts > .b-ok { background:#059669 !important; color:#fff !important; }
    .pos-modal .acts > .b-ok:hover { background:#047857 !important; }
    .pos-modal .acts > .b-blue { background:#2563eb !important; color:#fff !important; }
    .pos-modal .acts > .b-red { background:#dc2626 !important; color:#fff !important; }
    .pos-modal .acts > .b-cancel { background:#f1f5f9 !important; color:#0f172a !important; }
    .pos-modal .acts > button[disabled] { opacity:.6 !important; cursor:wait; }
</style>
@endsection

@section('content')
<div class="pos" id="pos">
    {{-- الشريط العلوي: العنوان + رابط الفواتير العادية + الباركود --}}
    <div class="pos-backdrop" id="pos-backdrop"></div>
    <div class="pos-bar">
        <button type="button" class="pos-menu-btn" id="pos-menu-btn" title="{{ $t('القائمة الجانبية', 'Sidebar') }}">☰ {{ $t('القائمة', 'Menu') }}</button>
        <h2>{{ $t('شاشة المبيعات السريعة', 'Quick sales screen') }}</h2>
        <a class="pos-link" href="{{ url('/goToSale') }}">{{ $t('الفواتير العادية', 'Standard invoices') }}</a>
        <button type="button" class="pos-shift loading" id="pos-shift">{{ $t('الوردية...', 'Shift...') }}</button>
        <div class="pos-scan">
            <input type="text" id="pos-barcode" autocomplete="off" autofocus
                   placeholder="{{ $t('امسح الباركود أو اكتبه هنا واضغط Enter...', 'Scan or type a barcode and press Enter...') }}">
            <div class="pos-scan-msg" id="pos-scan-msg"></div>
        </div>
    </div>

    <div class="pos-grid">
        {{-- الفئات --}}
        <div class="pos-card pos-cats">
            <h6>{{ $t('الفئات', 'Categories') }}</h6>
            <div class="pos-cats-list">
                <button type="button" class="pos-cat active" data-group="all">{{ $t('كل المنتجات', 'All products') }}</button>
                @foreach ($groups as $g)
                    <button type="button" class="pos-cat" data-group="{{ $g->id }}">{{ $isAr ? ($g->group_ar ?: $g->group_en) : ($g->group_en ?: $g->group_ar) }}</button>
                @endforeach
            </div>
        </div>

        {{-- المنتجات --}}
        <div class="pos-card">
            <div class="pos-prod-head">
                <input type="text" id="pos-search" autocomplete="off" placeholder="{{ $t('ابحث بالاسم أو الرقم...', 'Search by name or code...') }}">
                <span>{{ $t('الكمية', 'Qty') }}</span>
                <span>{{ $t('السعر', 'Price') }}</span>
            </div>
            <div class="pos-products" id="pos-products"></div>
        </div>

        {{-- السلة والدفع --}}
        <div class="pos-card">
            <div class="pos-cust">
                <label>{{ $t('العميل', 'Customer') }} <span class="text-danger">*</span></label>
                <div class="row-flex">
                    <select id="pos-customer" style="width:100%">
                        <option value="1" selected>{{ $t('عميل نقدي', 'Cash customer') }}</option>
                    </select>
                    <button type="button" class="pos-btn-soft" id="pos-add-customer">+ {{ $t('إضافة عميل جديد', 'New customer') }}</button>
                </div>
            </div>

            <div class="pos-cart">
                <div class="pos-cart-scroll">
                    <table>
                        <thead><tr>
                            <th>{{ $t('المنتج', 'Product') }}</th>
                            <th class="c" style="width:130px">{{ $t('الكمية', 'Qty') }}</th>
                            <th class="c" style="width:90px">{{ $t('الإجمالي', 'Total') }}</th>
                            <th style="width:34px"></th>
                        </tr></thead>
                        <tbody id="pos-cart-body"></tbody>
                    </table>
                    <div class="pos-empty" id="pos-cart-empty">{{ $t('السلة فارغة - امسح باركود أو اختر منتج من القائمة', 'Cart is empty — scan a barcode or pick a product') }}</div>
                </div>
            </div>

            <div class="pos-totals">
                <div class="ln"><span>{{ $t('الإجمالي قبل الضريبة', 'Subtotal') }}</span><span><b id="pos-sub">0.00</b> {{ $cur }}</span></div>
                <div class="ln"><span>{{ $t('الضريبة', 'VAT') }} ({{ rtrim(rtrim(number_format($vatRate * 100, 2), '0'), '.') }}%)</span><span><b id="pos-tax">0.00</b> {{ $cur }}</span></div>
                <div class="grand"><b>{{ $t('الإجمالي الكلي', 'Grand total') }}</b><span><span id="pos-grand">0.00</span> <small style="font-size:13px">{{ $cur }}</small></span></div>
            </div>

            <div class="pos-paid">
                <div class="ttl">💵 {{ $t('المبلغ المستلم', 'Amount received') }} <small>({{ $t('حساب الباقي - اختياري', 'change calculator - optional') }})</small></div>
                <div class="row-flex">
                    <input type="number" step="0.01" min="0" id="pos-paid" placeholder="{{ $t('المبلغ المستلم', 'Amount received') }} ({{ $cur }})...">
                    <button type="button" class="pos-btn-soft" id="pos-exact">{{ $t('بالضبط', 'Exact') }}</button>
                </div>
                <div class="pos-quick">
                    @foreach ([10, 20, 50, 100, 200, 500] as $v)
                        <button type="button" data-v="{{ $v }}">{{ $v }}</button>
                    @endforeach
                </div>
                <div class="pos-change" id="pos-change"><span id="pos-change-label"></span><span id="pos-change-val"></span></div>
            </div>

            <div class="pos-pay">
                <button type="button" class="cash" data-pay="Cash">{{ $t('نقدي', 'Cash') }} (F1)</button>
                <button type="button" class="pay-card" data-pay="Shabka">{{ $t('شبكة', 'Card') }} (F2)</button>
            </div>
            <button type="button" class="pos-save" id="pos-save">{{ $t('حفظ', 'Save') }} (F9) <small id="pos-save-mode"></small></button>
            <div class="pos-hint">{{ $t('F1 = دفع نقدي وحفظ فوري، F2 = دفع شبكة وحفظ فوري، F9 أو Ctrl+S = حفظ بطريقة الدفع الحالية', 'F1 = cash & save, F2 = card & save, F9 or Ctrl+S = save with current payment') }}</div>
        </div>
    </div>
</div>

{{-- تأكيد الحفظ --}}
<div class="pos-modal" id="pos-confirm">
    <div class="box">
        <h5>{{ $t('تأكيد حفظ الفاتورة', 'Confirm invoice') }}</h5>
        <div class="sum">
            <div><span>{{ $t('العميل', 'Customer') }}</span><b id="cf-customer"></b></div>
            <div><span>{{ $t('طريقة الدفع', 'Payment') }}</span><b id="cf-pay"></b></div>
            <div><span>{{ $t('عدد الأصناف', 'Items') }}</span><b id="cf-items"></b></div>
            <div class="big"><span>{{ $t('الإجمالي', 'Total') }}</span><span id="cf-total"></span></div>
            <div id="cf-change-row" style="display:none"><span>{{ $t('الباقي للعميل', 'Change') }}</span><b id="cf-change"></b></div>
        </div>
        <div class="acts">
            <button type="button" class="b-ok" id="cf-yes">{{ $t('حفظ', 'Save') }}</button>
            <button type="button" class="b-cancel" id="cf-no">{{ $t('إلغاء (Esc)', 'Cancel (Esc)') }}</button>
        </div>
    </div>
</div>

{{-- تم الحفظ --}}
<div class="pos-modal" id="pos-done">
    <div class="box" style="text-align:center">
        <div class="ok-icon">✓</div>
        <h5>{{ $t('تم حفظ الفاتورة', 'Invoice saved') }} <span id="done-no"></span></h5>
        <div class="zatca" id="done-zatca"></div>
        <div class="acts">
            <a class="b-blue" id="done-print" target="_blank" href="#">{{ $t('طباعة الفاتورة (P)', 'Print receipt (P)') }}</a>
            <button type="button" class="b-ok" id="done-new">{{ $t('فاتورة جديدة (Esc)', 'New invoice (Esc)') }}</button>
        </div>
    </div>
</div>

{{-- فتح وردية --}}
<div class="pos-modal" id="pos-shift-open">
    <div class="box">
        <h5>{{ $t('فتح وردية جديدة', 'Open a new shift') }}</h5>
        <div class="note">{{ $t('لا يمكن البيع بدون وردية مفتوحة. أدخل النقدية الموجودة في الصندوق الآن.', 'You need an open shift to sell. Enter the cash currently in the drawer.') }}</div>
        <div class="fld"><label>{{ $t('النقدية الافتتاحية', 'Opening cash') }} ({{ $cur }})</label><input type="number" step="0.01" min="0" id="so-cash" value="0"></div>
        <div class="acts">
            <button type="button" class="b-ok" id="so-save">{{ $t('فتح الوردية', 'Open shift') }}</button>
            <button type="button" class="b-cancel" id="so-cancel">{{ $t('لاحقاً', 'Later') }}</button>
        </div>
        <a class="lnk" href="{{ url('/shifts') }}">{{ $t('سجل الورديات', 'Shifts history') }}</a>
    </div>
</div>

{{-- إغلاق وردية --}}
<div class="pos-modal" id="pos-shift-close">
    <div class="box wide">
        <h5>{{ $t('إغلاق الوردية', 'Close shift') }} <span id="sc-no"></span></h5>
        <div class="sum">
            <div><span>{{ $t('بداية الوردية', 'Opened at') }}</span><b id="sc-opened" dir="ltr"></b></div>
            <div><span>{{ $t('عدد الفواتير', 'Invoices') }}</span><b id="sc-count"></b></div>
            <div><span>{{ $t('مبيعات نقدي', 'Cash sales') }}</span><b id="sc-cash"></b></div>
            <div><span>{{ $t('مبيعات شبكة', 'Card sales') }}</span><b id="sc-card"></b></div>
            <div><span>{{ $t('مبيعات أخرى (تحويل / آجل)', 'Other (transfer / credit)') }}</span><b id="sc-other"></b></div>
            <div><span>{{ $t('مرتجع نقدي', 'Cash refunds') }}</span><b id="sc-ret"></b></div>
            <div class="sep"></div>
            <div><span>{{ $t('النقدية الافتتاحية', 'Opening cash') }}</span><b id="sc-opening"></b></div>
            <div class="big"><span>{{ $t('النقدية المتوقعة', 'Expected cash') }}</span><span id="sc-expected"></span></div>
        </div>
        <div class="fld"><label>{{ $t('النقدية الفعلية في الصندوق (بعد العد)', 'Counted cash in drawer') }} *</label><input type="number" step="0.01" min="0" id="sc-counted"></div>
        <div class="diff" id="sc-diff"></div>
        <div class="fld"><label>{{ $t('ملاحظات', 'Notes') }}</label><textarea id="sc-note" rows="2"></textarea></div>
        <div class="acts">
            <button type="button" class="b-red" id="sc-save">{{ $t('إغلاق وطباعة التقرير', 'Close & print report') }}</button>
            <button type="button" class="b-cancel" id="sc-cancel">{{ $t('إلغاء (Esc)', 'Cancel (Esc)') }}</button>
        </div>
        <a class="lnk" id="sc-report" target="_blank" href="#">{{ $t('عرض تقرير الوردية الحالي', 'View current shift report') }}</a>
    </div>
</div>

{{-- عميل جديد --}}
<div class="pos-modal" id="pos-cust-modal">
    <div class="box">
        <h5>{{ $t('إضافة عميل جديد', 'New customer') }}</h5>
        <div class="fld"><label>{{ $t('الاسم', 'Name') }} *</label><input type="text" id="nc-name"></div>
        <div class="fld"><label>{{ $t('الجوال', 'Phone') }}</label><input type="text" id="nc-phone" dir="ltr"></div>
        <div class="fld"><label>{{ $t('الرقم الضريبي', 'VAT number') }}</label><input type="text" id="nc-tax" dir="ltr"></div>
        <div class="acts">
            <button type="button" class="b-ok" id="nc-save">{{ $t('حفظ', 'Save') }}</button>
            <button type="button" class="b-cancel" id="nc-cancel">{{ $t('إلغاء', 'Cancel') }}</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
(function () {
    'use strict';
    var CFG = {
        vat: {{ json_encode((float) $vatRate) }},
        inclusive: @json((bool) config('pos.prices_include_vat', true)),
        csrf: @json(csrf_token()),
        branch: @json(auth()->user()->branchs_id),
        cur: @json($cur),
        isAr: @json($isAr),
        urls: {
            products: @json(url('/pos/products')),
            barcode:  @json(url('/getByCodenew')),
            save:     @json(url('/save_invoice_sale')),
            zatca:    @json(url('/sendzatca_fromsale')),
            pdf:      @json(url('/pos/receipt')),
            customers:@json(route('clientnamesearch.search')),
            newCust:  @json(url('/createnewcustomerajax')),
            shiftStatus: @json(url('/shifts/status')),
            shiftOpen:   @json(url('/shifts/open')),
            shifts:      @json(url('/shifts'))
        },
        txt: {
            notFound:  @json($t('المنتج غير مسجل بالنظام', 'Product not registered')),
            added:     @json($t('تمت الإضافة', 'Added')),
            empty:     @json($t('السلة فارغة', 'Cart is empty')),
            badRow:    @json($t('تأكد من السعر والكمية للمنتج', 'Check price and quantity for')),
            cash:      @json($t('نقدي', 'Cash')),
            card:      @json($t('شبكة', 'Card')),
            avail:     @json($t('المتاح', 'Stock')),
            none:      @json($t('لا توجد منتجات', 'No products')),
            loading:   @json($t('جاري التحميل...', 'Loading...')),
            change:    @json($t('الباقي للعميل', 'Change due')),
            short:     @json($t('المبلغ ناقص', 'Amount short')),
            saveErr:   @json($t('تعذر حفظ الفاتورة، حاول مرة أخرى', 'Could not save the invoice, please try again')),
            zatcaWait: @json($t('جاري الإرسال لهيئة الزكاة...', 'Sending to ZATCA...')),
            zatcaOk:   @json($t('تم الإرسال لهيئة الزكاة ✓', 'Sent to ZATCA ✓')),
            zatcaErr:  @json($t('لم يتم الإرسال لهيئة الزكاة — يمكن إرسالها لاحقاً من الفواتير غير المرسلة', 'Not sent to ZATCA — you can send it later from unsent invoices')),
            custName:  @json($t('اكتب اسم العميل', 'Enter customer name')),
            piece:     'piece',
            shiftNone: @json($t('تفعيل وردية', 'Start shift')),
            shiftOn:   @json($t('وردية', 'Shift')),
            shiftNeed: @json($t('افتح وردية أولاً قبل البيع', 'Open a shift before selling')),
            shiftErr:  @json($t('تعذر تنفيذ العملية على الوردية، حاول مرة أخرى', 'Shift operation failed, please try again')),
            countReq:  @json($t('أدخل النقدية الفعلية بعد العد', 'Enter the counted cash')),
            short2:    @json($t('عجز', 'Short')),
            over:      @json($t('زيادة', 'Over')),
            balanced:  @json($t('مطابق ✓', 'Balanced ✓')),
            closeOpen: @json($t('في أصناف بالسلة لم تُحفظ — احفظ الفاتورة أو احذفها قبل إغلاق الوردية', 'The cart has unsaved items — save or clear it before closing the shift'))
        }
    };

    var $ = window.jQuery;

    // القائمة الجانبية مقفولة في هذه الشاشة، وتفتح/تقفل بزر القائمة
    // نحدد مكان القائمة الفعلي (يمين أو شمال) قبل إخفائها، علشان تتحرك للاتجاه الصحيح
    var sbEl = document.querySelector('.app-sidebar');
    if (sbEl) {
        var r = sbEl.getBoundingClientRect();
        document.body.classList.toggle('pos-sb-left', (r.left + r.right) / 2 < window.innerWidth / 2);
    }
    document.body.classList.add('pos-mode');
    var toggleSidebar = function (open) { document.body.classList.toggle('pos-sidebar-open', open); };
    document.getElementById('pos-menu-btn').addEventListener('click', function () { toggleSidebar(!document.body.classList.contains('pos-sidebar-open')); });
    document.getElementById('pos-backdrop').addEventListener('click', function () { toggleSidebar(false); });
    // زر القائمة في الهيدر (والخلفية الخاصة بالقالب) يفتح/يقفل نفس القائمة هنا بدل سلوك القالب
    document.addEventListener('click', function (e) {
        var t = e.target.closest('.app-sidebar__toggle, .app-sidebar__overlay');
        if (!t) return;
        e.preventDefault(); e.stopImmediatePropagation();
        toggleSidebar(t.classList.contains('app-sidebar__overlay') ? false : !document.body.classList.contains('pos-sidebar-open'));
    }, true);
    document.body.classList.remove('sidenav-toggled');
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.body.classList.contains('pos-sidebar-open')) toggleSidebar(false);
    });
    var cart = [];            // {key, id, name, code, price, qty, scale}
    var payType = 'Cash';
    var saving = false;
    var el = function (id) { return document.getElementById(id); };
    var money = function (n) { return (Math.round((Number(n) || 0) * 100) / 100).toFixed(2); };
    var esc = function (s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
    var beep = function (ok) { try { new Audio(ok ? '/sounds/done.mp3' : '/sounds/error.mp3').play().catch(function(){}); } catch (e) {} };

    // ------------------------------------------------ كشف إدخال قارئ الباركود
    // القارئ يكتب بسرعة كبيرة (أقل من 40ms بين الضغطات). نستخدم ذلك لمنع أي مفتاح زائد
    // يرسله القارئ بعد الكود (Enter ثاني / Tab / F-key) من فتح نافذة الحفظ أو تأكيدها.
    var lastKeyT = 0, fastKey = false, confirmOpenedAt = 0;
    document.addEventListener('keydown', function (e) {
        var now = Date.now();
        fastKey = (now - lastKeyT) < 40;
        lastKeyT = now;
    }, true);

    // ------------------------------------------------ إلغاء استجابة Enter نهائياً
    // Enter لا يضغط أي زر ولا يؤكد أي نافذة. الاستثناء الوحيد: خانة الباركود (لإدخال الكود فقط).
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        var t = e.target;
        if (t && t.id === 'pos-barcode') return;
        if (t && (t.tagName === 'BUTTON' || t.tagName === 'A' || (t.closest && t.closest('.pos-modal')))) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    }, true);
    // نفس الشيء للمسطرة (Space) على الأزرار عشان ما تضغطش زر بالغلط
    document.addEventListener('keyup', function (e) {
        if (e.key === ' ' && e.target && e.target.tagName === 'BUTTON') { e.preventDefault(); e.stopImmediatePropagation(); }
    }, true);

    // ------------------------------------------------ الإجماليات (نفس حساب شاشة الفواتير)
    var r2 = function (n) { return Math.round((Number(n) || 0) * 100) / 100; };
    // حساب السطر: لو الأسعار شاملة الضريبة، سعر المنتج هو اللي العميل بيدفعه مهما كانت نسبة الضريبة
    // (مثال: منتج بريال → ضريبة 15%: 0.87 + 0.13 = 1.00 ، ضريبة 0%: 1.00 + 0 = 1.00)
    function lineCalc(r) {
        if (CFG.inclusive) {
            var incl = r2(r.price * r.qty);
            var netUnit = r2(r.price / (1 + CFG.vat));
            var net = r2(netUnit * r.qty);
            return { unit: netUnit, net: net, tax: r2(incl - net), total: incl };
        }
        var n = r2(r.price * r.qty), tx = r2(n * CFG.vat);
        return { unit: r2(r.price), net: n, tax: tx, total: r2(n + tx) };
    }
    function totals() {
        var sub = 0, tax = 0;
        cart.forEach(function (r) { var c = lineCalc(r); sub += c.net; tax += c.tax; });
        return { sub: r2(sub), tax: r2(tax), grand: r2(sub + tax) };
    }

    function renderCart() {
        var body = el('pos-cart-body');
        body.innerHTML = cart.map(function (r, i) {
            var line = lineCalc(r).total;
            return '<tr data-i="' + i + '">' +
                '<td><div class="pn">' + esc(r.name) + '</div>' +
                '<div class="pp"><input type="number" step="0.01" min="0" class="js-price" value="' + money(r.price) + '"> ' + CFG.cur + '</div></td>' +
                '<td class="c"><span class="pos-qty"><button type="button" class="js-plus">+</button>' +
                '<input type="number" step="any" min="0" class="js-qty" value="' + r.qty + '">' +
                '<button type="button" class="js-minus">−</button></span></td>' +
                '<td class="c"><b>' + money(line) + '</b></td>' +
                '<td class="c"><button type="button" class="pos-del js-del" title="×">✕</button></td></tr>';
        }).join('');
        el('pos-cart-empty').style.display = cart.length ? 'none' : 'block';
        renderTotals();
    }

    function renderTotals() {
        var t = totals();
        el('pos-sub').textContent = money(t.sub);
        el('pos-tax').textContent = money(t.tax);
        el('pos-grand').textContent = money(t.grand);
        // تحديث إجمالي كل سطر بدون إعادة رسم (حتى لا يضيع التركيز من حقل الكمية)
        Array.prototype.forEach.call(el('pos-cart-body').rows, function (tr) {
            var r = cart[tr.dataset.i]; if (r) tr.cells[2].innerHTML = '<b>' + money(lineCalc(r).total) + '</b>';
        });
        renderChange();
        el('pos-save-mode').textContent = '(' + (payType === 'Cash' ? CFG.txt.cash : CFG.txt.card) + ')';
    }

    function renderChange() {
        var paid = parseFloat(el('pos-paid').value), box = el('pos-change');
        if (isNaN(paid) || paid <= 0 || !cart.length) { box.className = 'pos-change'; return; }
        var diff = paid - Math.round(totals().grand * 100) / 100;
        box.className = 'pos-change ' + (diff >= 0 ? 'pos' : 'neg');
        el('pos-change-label').textContent = diff >= 0 ? CFG.txt.change : CFG.txt.short;
        el('pos-change-val').textContent = money(Math.abs(diff)) + ' ' + CFG.cur;
    }

    function addToCart(p, scalePrice) {
        if (scalePrice === undefined) {
            var ex = cart.find(function (r) { return r.id == p.id && !r.scale; });
            if (ex) { ex.qty = +(ex.qty + 1).toFixed(3); renderCart(); flashRow(cart.indexOf(ex)); return; }
        }
        cart.push({ id: p.id, name: p.name, code: p.code, price: scalePrice !== undefined ? scalePrice : (Number(p.price) || 0), qty: 1, scale: scalePrice !== undefined });
        renderCart();
        flashRow(cart.length - 1);
    }

    function flashRow(i) {
        var tr = el('pos-cart-body').rows[i]; if (!tr) return;
        tr.style.background = '#ecfdf5'; tr.scrollIntoView({ block: 'nearest' });
        setTimeout(function () { tr.style.background = ''; }, 600);
    }

    // أحداث السلة
    el('pos-cart-body').addEventListener('click', function (e) {
        var tr = e.target.closest('tr'); if (!tr) return;
        var r = cart[tr.dataset.i];
        if (e.target.closest('.js-plus')) { r.qty = +(r.qty + 1).toFixed(3); renderCart(); }
        else if (e.target.closest('.js-minus')) { r.qty = +(r.qty - 1).toFixed(3); if (r.qty <= 0) cart.splice(tr.dataset.i, 1); renderCart(); }
        else if (e.target.closest('.js-del')) { cart.splice(tr.dataset.i, 1); renderCart(); focusScan(); }
    });
    el('pos-cart-body').addEventListener('input', function (e) {
        var tr = e.target.closest('tr'); if (!tr) return;
        var r = cart[tr.dataset.i];
        if (e.target.classList.contains('js-qty')) r.qty = parseFloat(e.target.value) || 0;
        if (e.target.classList.contains('js-price')) r.price = parseFloat(e.target.value) || 0;
        renderTotals();
    });
    el('pos-cart-body').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); e.target.blur(); focusScan(); }
    });

    // ------------------------------------------------ الباركود (يدعم باركود الميزان مثل الشاشة الحالية)
    function parseScaleBarcode(code) {
        if (!/^\d{13}$/.test(code)) return null;
        var prefix = code.substring(0, 2);
        if (prefix < '20' || prefix > '29') return null;
        var plu = code.substring(2, 7).replace(/^0+/, '') || '0';
        var price = parseInt(code.substring(7, 12), 10) / 100;
        return isNaN(price) ? null : { plu: plu, price: price };
    }

    function scanMsg(text, ok) {
        var m = el('pos-scan-msg'); m.textContent = text; m.className = 'pos-scan-msg ' + (ok ? 'ok' : 'err');
        clearTimeout(scanMsg._t); scanMsg._t = setTimeout(function () { m.className = 'pos-scan-msg'; }, ok ? 1200 : 3500);
    }

    function handleBarcode(code) {
        code = String(code).trim(); if (!code) return;
        var scale = parseScaleBarcode(code);
        $.ajax({ url: CFG.urls.barcode + '/' + encodeURIComponent(scale ? scale.plu : code), type: 'GET', dataType: 'json', cache: false })
            .done(function (d) {
                if (!d || !d.id) { scanMsg(CFG.txt.notFound + ': ' + code, false); beep(false); return; }
                var p = { id: d.id, name: (CFG.isAr ? d.product_name : (d.name_en || d.product_name)) || '-', code: d.Product_Code, price: Number(d.sale_price) || 0 };
                addToCart(p, scale ? scale.price : undefined);
                scanMsg(CFG.txt.added + ': ' + p.name, true); beep(true);
            })
            .fail(function () { scanMsg(CFG.txt.notFound + ': ' + code, false); beep(false); });
    }

    var scan = el('pos-barcode');
    function focusScan() { setTimeout(function () { scan.focus(); }, 0); }
    // Enter أو Tab (بعض القارئات ترسل Tab في آخر الكود) = إدخال الباركود
    scan.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === 'Tab') { e.preventDefault(); var v = scan.value; scan.value = ''; handleBarcode(v); }
    });

    // ------------------------------------------------ قائمة المنتجات
    var list = el('pos-products');
    var state = { q: '', group: 'all', page: 1, loading: false, done: false, req: 0 };

    function loadProducts(reset) {
        if (reset) { state.page = 1; state.done = false; list.innerHTML = ''; }
        if (state.loading || state.done) return;
        state.loading = true;
        var my = ++state.req;
        var loader = document.createElement('div'); loader.className = 'pos-loading'; loader.textContent = CFG.txt.loading; list.appendChild(loader);
        $.getJSON(CFG.urls.products, { q: state.q, group: state.group, page: state.page })
            .done(function (res) {
                if (my !== state.req) return;
                loader.remove();
                if (state.page === 1 && !res.data.length) { list.innerHTML = '<div class="pos-none">' + CFG.txt.none + '</div>'; }
                res.data.forEach(function (p) {
                    var row = document.createElement('div'); row.className = 'pos-prod';
                    row.innerHTML = '<div><div class="nm">' + esc(p.name) + '</div>' +
                        '<div class="meta">' + esc(p.code || '') + ' — ' + CFG.txt.avail + ': <span class="' + (p.qty < 0 ? 'neg' : '') + '">' + (+p.qty) + '</span></div></div>' +
                        '<span class="pos-price">' + esc(p.unit || CFG.txt.piece) + ' · ' + money(p.price) + ' ' + CFG.cur + '</span>';
                    row.addEventListener('click', function () { addToCart(p); beep(true); focusScan(); });
                    list.appendChild(row);
                });
                state.page = res.next_page || state.page;
                state.done = !res.next_page;
            })
            .fail(function () { loader.remove(); })
            .always(function () { if (my === state.req) state.loading = false; });
    }
    list.addEventListener('scroll', function () {
        if (list.scrollTop + list.clientHeight >= list.scrollHeight - 80) loadProducts(false);
    });
    var searchTimer;
    el('pos-search').addEventListener('input', function () {
        clearTimeout(searchTimer); var v = this.value.trim();
        searchTimer = setTimeout(function () { state.q = v; state.loading = false; loadProducts(true); }, 300);
    });
    document.querySelectorAll('.pos-cat').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('.pos-cat').forEach(function (x) { x.classList.remove('active'); });
            b.classList.add('active'); state.group = b.dataset.group; state.loading = false; loadProducts(true);
        });
    });

    // ------------------------------------------------ العميل
    var $cust = $('#pos-customer');
    if ($.fn.select2) {
        $cust.select2({
            dir: CFG.isAr ? 'rtl' : 'ltr',
            minimumInputLength: 1,
            ajax: {
                url: CFG.urls.customers, dataType: 'json', delay: 250,
                data: function (p) { return { q: p.term }; },
                processResults: function (d) { return { results: d.map(function (c) { return { id: c.id, text: c.name + (c.tax_no && c.tax_no != 0 ? ' — ' + c.tax_no : '') }; }) }; }
            }
        });
        $cust.on('select2:close', focusScan);
    }
    function customerName() { return $cust.find('option:selected').text().trim(); }

    el('pos-add-customer').addEventListener('click', function () { openModal('pos-cust-modal'); setTimeout(function () { el('nc-name').focus(); }, 50); });
    el('nc-cancel').addEventListener('click', function () { closeModal('pos-cust-modal'); focusScan(); });
    el('nc-save').addEventListener('click', function () {
        var name = el('nc-name').value.trim(); if (!name) { el('nc-name').focus(); el('nc-name').placeholder = CFG.txt.custName; return; }
        var btn = this; btn.disabled = true;
        $.post(CFG.urls.newCust, { _token: CFG.csrf, name: name, phone: el('nc-phone').value.trim() || undefined, tax_no: el('nc-tax').value.trim() || undefined })
            .done(function (c) {
                var opt = new Option(c.name, c.id, true, true); $cust.append(opt).trigger('change');
                ['nc-name', 'nc-phone', 'nc-tax'].forEach(function (i) { el(i).value = ''; });
                closeModal('pos-cust-modal'); focusScan();
            })
            .fail(function () { alert(CFG.txt.saveErr); })
            .always(function () { btn.disabled = false; });
    });

    // ------------------------------------------------ المبلغ المستلم
    el('pos-paid').addEventListener('input', renderChange);
    el('pos-exact').addEventListener('click', function () { el('pos-paid').value = money(totals().grand); renderChange(); });
    document.querySelectorAll('.pos-quick button').forEach(function (b) {
        b.addEventListener('click', function () { el('pos-paid').value = b.dataset.v; renderChange(); });
    });

    // ------------------------------------------------ النوافذ
    function openModal(id) { el(id).classList.add('show'); }
    function closeModal(id) { el(id).classList.remove('show'); }
    function modalOpen(id) { return el(id).classList.contains('show'); }

    // ------------------------------------------------ الوردية
    var shift = null;          // null = جاري التحميل، {open:false} أو {open:true, id, number, ...}
    var shiftBtn = el('pos-shift');

    function renderShift() {
        if (!shift) { shiftBtn.className = 'pos-shift loading'; return; }
        if (shift.open) {
            shiftBtn.className = 'pos-shift on';
            shiftBtn.textContent = CFG.txt.shiftOn + ' #' + shift.number;
        } else {
            shiftBtn.className = 'pos-shift off';
            shiftBtn.textContent = '⏻ ' + CFG.txt.shiftNone;
        }
    }

    function loadShift(cb) {
        $.ajax({ url: CFG.urls.shiftStatus, dataType: 'json', cache: false })
            .done(function (d) { shift = d; renderShift(); if (cb) cb(); })
            .fail(function () { shift = { open: false, error: true }; renderShift(); });
    }

    function openShiftModal() {
        el('so-cash').value = '0';
        openModal('pos-shift-open');
        setTimeout(function () { el('so-cash').select(); }, 50);
    }

    function closeShiftModal() {
        if (cart.length) { scanMsg(CFG.txt.closeOpen, false); beep(false); return; }
        // تحديث الأرقام لحظياً قبل العرض
        loadShift(function () {
            if (!shift.open) { openShiftModal(); return; }
            var tt = shift.totals;
            el('sc-no').textContent = '#' + shift.number;
            el('sc-opened').textContent = shift.opened_at;
            el('sc-count').textContent = tt.invoices_count;
            el('sc-cash').textContent = money(tt.cash_sales);
            el('sc-card').textContent = money(tt.card_sales);
            el('sc-other').textContent = money(tt.transfer_sales + tt.credit_sales);
            el('sc-ret').textContent = money(tt.cash_returns);
            el('sc-opening').textContent = money(shift.opening_cash);
            el('sc-expected').textContent = money(tt.expected_cash) + ' ' + CFG.cur;
            el('sc-report').href = shift.report_url;
            el('sc-counted').value = ''; el('sc-note').value = ''; el('sc-diff').textContent = '';
            openModal('pos-shift-close');
            setTimeout(function () { el('sc-counted').focus(); }, 50);
        });
    }

    shiftBtn.addEventListener('click', function () {
        if (!shift) return;
        shift.open ? closeShiftModal() : openShiftModal();
    });

    el('so-cancel').addEventListener('click', function () { closeModal('pos-shift-open'); focusScan(); });
    el('so-save').addEventListener('click', function () {
        var btn = this; btn.disabled = true;
        $.ajax({ url: CFG.urls.shiftOpen, type: 'POST', dataType: 'json', headers: { Accept: 'application/json' },
                 data: { _token: CFG.csrf, opening_cash: parseFloat(el('so-cash').value) || 0 } })
            .done(function (d) { shift = d; renderShift(); closeModal('pos-shift-open'); beep(true); focusScan(); })
            .fail(function () { alert(CFG.txt.shiftErr); })
            .always(function () { btn.disabled = false; });
    });

    el('sc-counted').addEventListener('input', function () {
        var v = parseFloat(this.value), out = el('sc-diff');
        if (isNaN(v) || !shift || !shift.open) { out.textContent = ''; return; }
        var d = Math.round((v - shift.totals.expected_cash) * 100) / 100;
        out.style.color = d < 0 ? '#dc2626' : '#059669';
        out.textContent = d === 0 ? CFG.txt.balanced : ((d < 0 ? CFG.txt.short2 : CFG.txt.over) + ': ' + money(Math.abs(d)) + ' ' + CFG.cur);
    });
    el('sc-cancel').addEventListener('click', function () { closeModal('pos-shift-close'); focusScan(); });
    el('sc-save').addEventListener('click', function () {
        var v = el('sc-counted').value.trim();
        if (v === '' || isNaN(parseFloat(v))) { el('sc-counted').focus(); el('sc-diff').style.color = '#dc2626'; el('sc-diff').textContent = CFG.txt.countReq; return; }
        var btn = this; btn.disabled = true;
        var win = window.open('about:blank', '_blank');      // تُفتح الآن حتى لا يحجبها المتصفح
        $.ajax({ url: CFG.urls.shifts + '/' + shift.id + '/close', type: 'POST', dataType: 'json', headers: { Accept: 'application/json' },
                 data: { _token: CFG.csrf, counted_cash: parseFloat(v), note: el('sc-note').value } })
            .done(function (d) {
                if (win) win.location = d.report_url; else window.open(d.report_url, '_blank');
                shift = { open: false }; renderShift(); closeModal('pos-shift-close');
                openShiftModal();
            })
            .fail(function () { if (win) win.close(); alert(CFG.txt.shiftErr); })
            .always(function () { btn.disabled = false; });
    });

    // ------------------------------------------------ الحفظ (نفس حقول شاشة الفواتير → save_invoice_sale)
    function validate() {
        if (!cart.length) { scanMsg(CFG.txt.empty, false); beep(false); return false; }
        var bad = cart.find(function (r) { return !(r.price > 0) || !(r.qty > 0); });
        if (bad) { scanMsg(CFG.txt.badRow + ': ' + bad.name, false); beep(false); return false; }
        return true;
    }

    function askSave(type) {
        if (saving || modalOpen('pos-confirm') || modalOpen('pos-done')) return;
        if (!shift || !shift.open) { scanMsg(CFG.txt.shiftNeed, false); beep(false); if (shift) openShiftModal(); return; }
        if (type) payType = type;
        renderTotals();
        if (!validate()) return;
        var t = totals(), paid = parseFloat(el('pos-paid').value);
        el('cf-customer').textContent = customerName();
        el('cf-pay').textContent = payType === 'Cash' ? CFG.txt.cash : CFG.txt.card;
        el('cf-items').textContent = cart.length;
        el('cf-total').textContent = money(t.grand) + ' ' + CFG.cur;
        var showChange = payType === 'Cash' && !isNaN(paid) && paid > 0;
        el('cf-change-row').style.display = showChange ? 'flex' : 'none';
        if (showChange) el('cf-change').textContent = money(paid - Math.round(t.grand * 100) / 100) + ' ' + CFG.cur;
        confirmOpenedAt = Date.now();
        openModal('pos-confirm');
        el('cf-yes').focus();
    }

    function doSave() {
        if (saving) return;
        saving = true;
        closeModal('pos-confirm');
        var btns = document.querySelectorAll('.pos-pay button, #pos-save'); btns.forEach(function (b) { b.disabled = true; });

        var t = totals(), fd = new FormData();
        fd.append('_token', CFG.csrf);
        fd.append('action', 'save');
        fd.append('clientnamesearch', $cust.val() || 1);
        fd.append('payment_type', payType);
        fd.append('totalSum', money(t.sub));
        fd.append('totalTax', money(t.tax));
        fd.append('grandTotal', money(t.grand));
        fd.append('totaldiscound', '0');
        fd.append('discound_on_invoice', '0');
        fd.append('show_invoice_number_update', '0');
        fd.append('branchs_id', CFG.branch);
        fd.append('notes', '-');
        fd.append('p_o', '-');
        fd.append('date', '0');
        fd.append('shownumberproduct', '1');
        cart.forEach(function (r, i) {
            var p = 'products[' + i + ']';
            fd.append(p + '[product_id]', r.id);
            fd.append(p + '[product_name]', r.name);
            var c = lineCalc(r);
            fd.append(p + '[price]', money(c.unit));          // السعر قبل الضريبة (كما يتوقعه الحفظ)
            fd.append(p + '[quentity]', r.qty);
            fd.append(p + '[discound]', '0');
            fd.append(p + '[tax]', money(c.tax));
            fd.append(p + '[tax_rate]', CFG.vat);
        });

        $.ajax({ url: CFG.urls.save, type: 'POST', data: fd, processData: false, contentType: false, cache: false })
            .done(function (id) {
                id = parseInt(id, 10);
                if (!(id >= 1)) { alert(CFG.txt.saveErr); return; }
                cart = []; renderCart();      // الفاتورة اتحفظت — تفريغ السلة فوراً
                el('done-no').textContent = '#' + id;
                el('done-print').href = CFG.urls.pdf + '/' + id + '?print=1';
                el('done-zatca').textContent = CFG.txt.zatcaWait;
                openModal('pos-done');
                el('done-new').focus();
                beep(true);
                // الإرسال لهيئة الزكاة (نفس شاشة الفواتير)
                $.get(CFG.urls.zatca + '/' + id)
                    .done(function (res) { el('done-zatca').textContent = (res == 1) ? CFG.txt.zatcaOk : CFG.txt.zatcaErr; })
                    .fail(function () { el('done-zatca').textContent = CFG.txt.zatcaErr; });
            })
            .fail(function () { alert(CFG.txt.saveErr); })
            .always(function () { saving = false; btns.forEach(function (b) { b.disabled = false; }); });
    }

    function newInvoice() {
        cart = []; el('pos-paid').value = ''; payType = 'Cash';
        $cust.empty().append(new Option(CFG.isAr ? 'عميل نقدي' : 'Cash customer', 1, true, true)).trigger('change');
        renderCart(); closeModal('pos-done'); focusScan();
    }

    document.querySelectorAll('.pos-pay button').forEach(function (b) { b.addEventListener('click', function () { askSave(b.dataset.pay); }); });
    el('pos-save').addEventListener('click', function () { askSave(); });
    // منع الضغط المزدوج / الضغط التلقائي فور فتح نافذة التأكيد
    el('cf-yes').addEventListener('click', function () {
        if (Date.now() - confirmOpenedAt < 600) return;
        doSave();
    });
    el('cf-no').addEventListener('click', function () { closeModal('pos-confirm'); focusScan(); });
    el('done-new').addEventListener('click', newInvoice);

    // ------------------------------------------------ اختصارات لوحة المفاتيح
    document.addEventListener('keydown', function (e) {
        if (modalOpen('pos-confirm')) {
            if (e.key === 'Escape') { e.preventDefault(); closeModal('pos-confirm'); focusScan(); }
            return;
        }
        if (modalOpen('pos-done')) {
            if (e.key === 'Escape') { e.preventDefault(); newInvoice(); }
            else if (e.key === 'p' || e.key === 'P' || e.key === 'F9') { e.preventDefault(); el('done-print').click(); }
            return;
        }
        if (modalOpen('pos-shift-open')) {
            if (e.key === 'Escape') { e.preventDefault(); el('so-cancel').click(); }
            return;
        }
        if (modalOpen('pos-shift-close')) {
            if (e.key === 'Escape') { e.preventDefault(); el('sc-cancel').click(); }
            return;
        }
        if (modalOpen('pos-cust-modal')) {
            if (e.key === 'Escape') { closeModal('pos-cust-modal'); focusScan(); }
            return;
        }
        if (fastKey) return;   // أي مفتاح وظيفي يأتي بسرعة القارئ يتجاهل
        if (e.key === 'F1') { e.preventDefault(); askSave('Cash'); }
        else if (e.key === 'F2') { e.preventDefault(); askSave('Shabka'); }
        else if (e.key === 'F9' || ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S'))) { e.preventDefault(); askSave(); }
    });
    window.addEventListener('help', function (e) { e.preventDefault(); });   // منع نافذة مساعدة المتصفح مع F1

    // تحذير عند مغادرة الصفحة وفيها أصناف غير محفوظة
    window.addEventListener('beforeunload', function (e) { if (cart.length && !saving) { e.preventDefault(); e.returnValue = ''; } });

    renderCart();
    loadProducts(true);
    focusScan();
    loadShift(function () { if (!shift.open) openShiftModal(); });
    // قارئ الباركود لازم يكتب في خانة الباركود دايماً: لو التركيز على زرار (زي نقدي/شبكة بعد الضغط عليه بالماوس)
    // الحروف بتتحوّل لخانة الباركود، والـ Enter مايضغطش على الزرار (كان بيفتح نافذة الحفظ)
    document.querySelectorAll('.pos-pay button, #pos-save, .pos-cat, .pos-quick button, #pos-exact, #pos-shift, #pos-menu-btn, #pos-add-customer').forEach(function (b) {
        b.addEventListener('mousedown', function (e) { e.preventDefault(); });   // الضغط بالماوس مايسحبش التركيز من خانة الباركود
    });
    document.addEventListener('keydown', function (e) {
        if (document.querySelector('.pos-modal.show') || e.ctrlKey || e.altKey || e.metaKey) return;
        var a = document.activeElement, tag = a ? a.tagName : '';
        if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || (a && a.isContentEditable)) return;
        if ($('.select2-container--open').length) return;
        if (e.key && e.key.length === 1) {
            e.preventDefault();
            scan.focus();
            scan.value += e.key;
        } else if (e.key === 'Enter' || e.key === 'Tab') {
            e.preventDefault();
            if (scan.value.trim()) { var v = scan.value; scan.value = ''; handleBarcode(v); }
            focusScan();
        }
    }, true);

    // لما ترجع للشاشة من تابة تانية (زي إنشاء منتج جديد) التركيز يرجع لحقل الباركود
    window.addEventListener('focus', function () {
        if (!document.querySelector('.pos-modal.show')) focusScan();
    });
})();
</script>
@endsection