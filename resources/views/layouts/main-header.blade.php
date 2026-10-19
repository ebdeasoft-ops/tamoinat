<!-- main-header opened -->
<style>
    /* =====================================================================
       EBDEA SOFT — Header
       شريط علوي هادئ: تنقّل سريع على شكل تبويبات + أدوات موحّدة المقاس
       ===================================================================== */
    .sb-header {
        --hdr-bg: #ffffff;
        --hdr-border: #e6eaf0;
        --hdr-text: #0f172a;
        --hdr-text-2: #475569;
        --hdr-muted: #94a3b8;
        --hdr-soft: #f6f8fb;
        --hdr-navy-950: #0a0e1a;
        --hdr-navy-900: #0f172a;
        --hdr-navy-800: #1e293b;
        --hdr-primary: #2563eb;
        --hdr-primary-soft: #eff6ff;
        --hdr-ctl: 36px;                 /* مقاس موحّد لكل الأزرار */
        --hdr-radius: 10px;
        --hdr-font: 'Cairo', 'IBM Plex Sans Arabic', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

        background: rgba(255, 255, 255, .96) !important;
        backdrop-filter: saturate(160%) blur(8px);
        box-shadow: 0 1px 0 var(--hdr-border), 0 6px 18px -12px rgba(15, 23, 42, .18) !important;
        border-bottom: 0 !important;
        font-family: var(--hdr-font) !important;
        min-height: 64px !important;
        height: 64px !important;
        padding: 0 !important;
        /* الهيدر fixed بعرض الشاشة: نترك مسافة بعرض القائمة الجانبية (يمين بالعربي / يسار بالإنجليزي) */
        padding-inline-start: 250px !important;
        z-index: 999 !important;
        transition: padding .2s ease;
    }

    .sb-header .the-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 0 20px;
        height: 100%;
        width: 100%;
        max-width: none;
    }

    .sb-header .main-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1 1 auto;
        min-width: 0;            /* يسمح للتبويبات بالانكماش بدل ما تخرج تحت القائمة */
    }

    .sb-header .main-header-left > .d-xl-flex { min-width: 0; }

    .sb-header .main-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    @media (max-width: 991px) {
        .sb-header { padding-inline-start: 0 !important; }
        .sb-header .the-main { padding: 0 12px; }
    }

    /* شاشة البيع السريع: القائمة الجانبية مقفولة فالهيدر ياخد العرض كامل */
    body.pos-mode .sb-header { padding-inline-start: 0 !important; }

    .sb-header .responsive-logo { display: flex; align-items: center; }
    .sb-header .responsive-logo img { max-height: 30px; width: auto; }

    /* ---- زر القائمة: يظهر في الموبايل وشاشة البيع السريع فقط (القائمة ثابتة على الكمبيوتر) ---- */
    .sb-header .app-sidebar__toggle { display: flex; align-items: center; }
    .sb-header .app-sidebar__toggle .close-toggle { display: none !important; }
    @media (min-width: 992px) {
        body:not(.pos-mode) .sb-header .app-sidebar__toggle { display: none !important; }
    }

    .sb-header .app-sidebar__toggle a {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: var(--hdr-ctl);
        height: var(--hdr-ctl);
        border-radius: var(--hdr-radius);
        background: var(--hdr-soft);
        border: 1px solid var(--hdr-border);
        color: var(--hdr-text-2) !important;
        text-decoration: none;
        transition: background .15s ease, color .15s ease;
    }
    .sb-header .app-sidebar__toggle a:hover { background: #eef2f7; color: var(--hdr-primary) !important; }
    .sb-header .app-sidebar__toggle a .header-icon,
    .sb-header .app-sidebar__toggle a .header-icons { font-size: 17px; line-height: 1; }

    /* ---- التنقل السريع: مجموعة تبويبات ---- */
    .sb-header .header-shortcut-nav {
        display: flex;
        align-items: center;
        gap: 2px;
        list-style: none;
        margin: 0;
        padding: 3px;
        background: var(--hdr-soft);
        border: 1px solid var(--hdr-border);
        border-radius: 12px;
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        max-width: 100%;
    }
    .sb-header .header-shortcut-nav::-webkit-scrollbar { display: none; }
    .sb-header .header-shortcut-nav li { flex-shrink: 0; }

    .sb-header .shortcut-item-link {
        display: inline-flex !important;
        align-items: center;
        gap: 7px;
        height: 32px;
        padding: 0 12px;
        border-radius: 9px;
        background: transparent;
        border: 0;
        color: var(--hdr-text-2) !important;
        text-decoration: none !important;
        font-size: 12.5px;
        font-weight: 600;
        white-space: nowrap;
        transition: background .15s ease, color .15s ease, box-shadow .15s ease;
    }
    .sb-header .shortcut-item-link:hover { background: #fff; color: var(--hdr-text) !important; box-shadow: 0 1px 2px rgba(15, 23, 42, .06); }
    .sb-header .shortcut-item-link.is-active {
        background: #fff;
        color: var(--hdr-text) !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .08), 0 0 0 1px var(--hdr-border);
    }

    .sb-header .shortcut-icon-chip {
        width: 22px;
        height: 22px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }
    .sb-header .shortcut-chip-emerald { background: #ecfdf5; color: #059669; }
    .sb-header .shortcut-chip-rose    { background: #fff1f2; color: #e11d48; }
    .sb-header .shortcut-chip-blue    { background: #eff6ff; color: #2563eb; }
    .sb-header .shortcut-chip-amber   { background: #fffbeb; color: #d97706; }
    .sb-header .shortcut-chip-violet  { background: #f5f3ff; color: #7c3aed; }
    /* زر "منتج جديد" (يفتح في تابة جديدة) */
    .sb-header .shortcut-chip-add { background: #2563eb; color: #fff; }
    .sb-header .shortcut-item-link.shortcut-add { color: #1d4ed8 !important; }
    .sb-header .shortcut-item-link.shortcut-add:hover { background: #eff6ff; }

    /* الشاشات الأصغر: التبويبات أيقونات فقط (الاسم يظهر عند المرور) */
    @media (max-width: 1499px) {
        .sb-header .shortcut-item-link .shortcut-label { display: none; }
        .sb-header .shortcut-item-link { padding: 0 8px; }
    }

    /* ---- معلومات الفرع والوقت ---- */
    .sb-header .sb-info-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: var(--hdr-ctl);
        padding: 0 12px;
        border-radius: var(--hdr-radius);
        background: var(--hdr-soft);
        border: 1px solid var(--hdr-border);
        color: var(--hdr-text-2);
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .sb-header .sb-info-chip i { color: var(--hdr-muted); font-size: 12px; }
    .sb-header .sb-info-chip .sb-sep { width: 1px; height: 14px; background: #d5dbe4; }
    .sb-header .sb-clock { font-variant-numeric: tabular-nums; direction: ltr; color: var(--hdr-text); }
    @media (max-width: 1299px) { .sb-header .sb-info-chip { display: none; } }

    .sb-header .sb-hdr-divider { width: 1px; height: 24px; background: var(--hdr-border); margin: 0 4px; }
    @media (max-width: 767px) { .sb-header .sb-hdr-divider { display: none; } }

    /* ---- أزرار الأيقونات (الإشعارات / الشاشة الكاملة) ---- */
    .sb-header .sb-icon-btn,
    .sb-header .sb-bell-btn {
        position: relative;
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: var(--hdr-ctl);
        height: var(--hdr-ctl);
        border-radius: var(--hdr-radius);
        background: transparent;
        border: 1px solid transparent;
        color: var(--hdr-text-2) !important;
        text-decoration: none !important;
        transition: background .15s ease, color .15s ease, border-color .15s ease;
    }
    .sb-header .sb-icon-btn:hover,
    .sb-header .sb-bell-btn:hover,
    .sb-header .sb-bell-btn[aria-expanded="true"] { background: var(--hdr-soft); border-color: var(--hdr-border); color: var(--hdr-primary) !important; }
    .sb-header .sb-bell-btn::after { display: none !important; }

    .sb-header .sb-bell-badge {
        position: absolute;
        top: 1px;
        inset-inline-end: 1px;
        min-width: 17px;
        height: 17px;
        padding: 0 4px;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: 9.5px;
        font-weight: 800;
        line-height: 13px;
        text-align: center;
        border: 2px solid #fff;
    }

    .sb-header .sb-notif-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        color: var(--hdr-text) !important;
        text-decoration: none !important;
        font-size: 12.5px;
    }
    .sb-header .sb-notif-item:hover { background: var(--hdr-soft); }
    .sb-header .sb-notif-icon {
        width: 32px; height: 32px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 13px;
    }
    .sb-header .sb-notif-count { margin-inline-start: auto; font-weight: 800; font-size: 12px; border-radius: 999px; padding: 2px 9px; }

    /* ---- أزرار كبسولة (اللغة) ---- */
    .sb-header .sb-pill-btn {
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        height: var(--hdr-ctl);
        padding: 0 10px;
        border-radius: var(--hdr-radius);
        background: transparent;
        border: 1px solid transparent;
        color: var(--hdr-text-2) !important;
        text-decoration: none !important;
        font-size: 12.5px;
        font-weight: 700;
        transition: background .15s ease, border-color .15s ease;
        cursor: pointer;
    }
    .sb-header .sb-pill-btn:hover,
    .sb-header .sb-pill-btn[aria-expanded="true"] { background: var(--hdr-soft); border-color: var(--hdr-border); color: var(--hdr-text) !important; }
    .sb-header .sb-pill-icon { display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--hdr-muted); }
    .sb-header .sb-caret-icon { font-size: 11px; color: var(--hdr-muted); transition: transform .18s ease; }
    .sb-header .dropdown.show .sb-caret-icon { transform: rotate(180deg); }
    .sb-header .dropdown-toggle::after { display: none !important; }

    /* ---- حالة الربط مع الزكاة ---- */
    .sb-header .sb-zatca-btn {
        display: inline-flex !important;
        align-items: center;
        gap: 8px;
        height: var(--hdr-ctl);
        padding: 0 12px;
        border-radius: var(--hdr-radius);
        background: var(--hdr-navy-900);
        border: 1px solid var(--hdr-navy-800);
        color: #fff !important;
        text-decoration: none !important;
        font-size: 12px;
        font-weight: 700;
        transition: background .15s ease;
        cursor: pointer;
        position: relative;
    }
    .sb-header .sb-zatca-btn:hover,
    .sb-header .sb-zatca-btn[aria-expanded="true"] { background: var(--hdr-navy-800); color: #fff !important; }
    .sb-header .sb-zatca-btn.has-pending { background: #fff7ed; border-color: #fed7aa; color: #9a3412 !important; }
    .sb-header .sb-zatca-btn.has-pending:hover { background: #ffedd5; }
    .sb-header .sb-zatca-btn.has-pending .sb-caret-icon { color: #c2410c !important; }

    .sb-header .sb-zatca-pulse-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #22c55e; display: inline-block;
        animation: zatca-pulse 2s infinite;
    }
    @keyframes zatca-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(34, 197, 94, .7); }
        70%  { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    .sb-header .sb-zatca-count {
        min-width: 20px; height: 20px; padding: 0 6px;
        border-radius: 999px; background: #ea580c; color: #fff;
        font-size: 10.5px; font-weight: 800; line-height: 20px; text-align: center;
    }

    /* ---- المستخدم ---- */
    .sb-header .sb-user-capsule {
        display: inline-flex !important;
        align-items: center;
        gap: 9px;
        height: 40px;
        padding: 3px 4px;
        padding-inline-end: 10px;
        border-radius: 12px;
        background: transparent;
        border: 1px solid transparent;
        color: var(--hdr-text) !important;
        text-decoration: none !important;
        transition: background .15s ease, border-color .15s ease;
        cursor: pointer;
    }
    .sb-header .sb-user-capsule:hover,
    .sb-header .sb-user-capsule[aria-expanded="true"] { background: var(--hdr-soft); border-color: var(--hdr-border); }
    .sb-header .sb-user-avatar {
        width: 32px; height: 32px; border-radius: 10px;
        object-fit: cover; border: 1px solid var(--hdr-border); flex-shrink: 0;
    }
    .sb-header .sb-user-meta { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
    .sb-header .sb-user-name { font-size: 12.5px; font-weight: 700; color: var(--hdr-text); max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sb-header .sb-user-role { font-size: 10.5px; font-weight: 600; color: var(--hdr-muted); max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ---- القوائم المنسدلة ---- */
    .sb-header .dropdown-menu {
        border: 1px solid var(--hdr-border) !important;
        box-shadow: 0 18px 40px -12px rgba(15, 23, 42, .22) !important;
        border-radius: 14px !important;
        font-family: var(--hdr-font) !important;
        animation: sb-dropdown-fade .16s cubic-bezier(.16, 1, .3, 1);
    }
    @keyframes sb-dropdown-fade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
    .sb-header .dropdown-item { font-family: var(--hdr-font); transition: background .15s ease; }
    .sb-header .dropdown-item:hover { background: var(--hdr-soft); color: var(--hdr-text) !important; }
    .sb-header .dropdown-item.active,
    .sb-header .dropdown-item:active { background: var(--hdr-primary-soft) !important; color: var(--hdr-primary) !important; font-weight: 700; }
    .sb-header .sb-dropdown-eyebrow { font-size: 11px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; color: var(--hdr-muted); }
    .sb-header .sb-zatca-panel-head,
    .sb-header .sb-user-panel-head { background: linear-gradient(135deg, var(--hdr-navy-950) 0%, var(--hdr-navy-800) 100%) !important; }
</style>

<div class="main-header sticky side-header nav nav-item sb-header">
    <div class="container-fluid the-main">

        <!-- القسم الأيمن (الشعار، زر القائمة، وروابط الوصول السريع) -->
        <div class="main-header-left">
            <div class="responsive-logo d-xl-none">
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo.png') }}" class="logo-1" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo-white.png') }}" class="dark-logo-1" alt="logo"></a>
            </div>

            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#" title="{{ __('home.toggle_sidebar') ?? 'القائمة' }}">
                    <i class="header-icon fe fe-menu"></i>
                </a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>

            <!-- الروابط السريعة المباشرة المصممة على شكل كبسولات حديثة -->
            <div class="d-none d-xl-flex align-items-center">
                <ul class="header-shortcut-nav">
                    @can('Sales products')
                        <li>
                            <a class="shortcut-item-link {{ request()->is('*goToSale') ? 'is-active' : '' }}" href="{{ url('/goToSale') }}">
                                <span class="shortcut-icon-chip shortcut-chip-blue">
                                    <i class="fas fa-shopping-bag"></i>
                                </span>
                                <span class="shortcut-label">{{ __('home.sales') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="shortcut-item-link {{ request()->is('*pos') ? 'is-active' : '' }}" href="{{ url('/pos') }}">
                                <span class="shortcut-icon-chip shortcut-chip-rose">
                                    <i class="fas fa-bolt"></i>
                                </span>
                                <span class="shortcut-label">{{ app()->getLocale() == 'ar' ? 'شاشة البيع السريع' : 'Quick sale (POS)' }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('Purchases products')
                        <li>
                            <a class="shortcut-item-link {{ request()->is('*purchases') ? 'is-active' : '' }}" href="{{ url('/purchases') }}">
                                <span class="shortcut-icon-chip shortcut-chip-amber">
                                    <i class="fas fa-shopping-cart"></i>
                                </span>
                                <span class="shortcut-label">{{ __('home.purchases') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('Daily record')
                        <li>
                            <a class="shortcut-item-link {{ request()->is('*Daily_record') ? 'is-active' : '' }}" href="{{ url('/Daily_record') }}">
                                <span class="shortcut-icon-chip shortcut-chip-violet">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                                <span class="shortcut-label">{{ app()->getLocale() == 'ar' ? 'المحاسبة والفواتير' : 'Accounting & invoices' }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('Produects')
                        <li>
                            <a class="shortcut-item-link {{ request()->is('*showAllProducts') ? 'is-active' : '' }}" href="{{ url('/showAllProducts') }}">
                                <span class="shortcut-icon-chip shortcut-chip-emerald">
                                    <i class="fas fa-box-open"></i>
                                </span>
                                <span class="shortcut-label">{{ app()->getLocale() == 'ar' ? 'المخزون' : 'Inventory' }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('Add new product')
                        <li>
                            <a class="shortcut-item-link shortcut-add" href="{{ url('/addnewProduct') }}" target="_blank" rel="noopener">
                                <span class="shortcut-icon-chip shortcut-chip-add">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="shortcut-label">{{ app()->getLocale() == 'ar' ? 'منتج جديد' : 'New product' }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>

        <!-- القسم الأيسر (أدوات التحكم: اللغة، الربط الضريبي، الشاشة الكاملة، والملف الشخصي) -->
        <div class="main-header-right">
            @php
            $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
            $endOfToday = \Carbon\Carbon::now()->endOfDay();

            $totalUnsent = \App\Models\invoices::where('save', 1)
                ->where('sent_to_zatca', 0)
                ->where('status', 0)
                ->whereBetween('created_at', [$startOfMonth, $endOfToday])
                ->count();
            @endphp

            @php
                $lowStockCount = \App\Models\products::where('branchs_id', auth()->user()->branchs_id)
                    ->whereColumn('numberofpice', '<=', 'minmum_quantity_stock_alart')
                    ->count();
                $notifTotal = $lowStockCount + $totalUnsent;
                $isArHdr = app()->getLocale() == 'ar';
            @endphp

            <!-- الفرع والوقت -->
            <div class="sb-info-chip" title="{{ $isArHdr ? 'الفرع الحالي' : 'Current branch' }}">
                <i class="fas fa-store"></i>
                <span>{{ optional(Auth::user()->branch)->name ?? '-' }}</span>
                <span class="sb-sep"></span>
                <i class="far fa-clock"></i>
                <span class="sb-clock" id="sbHeaderClock">{{ \Carbon\Carbon::now()->format('h:i A') }}</span>
            </div>
            <span class="sb-hdr-divider"></span>

            <!-- 0. الإشعارات (نواقص المخزون + فواتير لم تُرسل لهيئة الزكاة) -->
            <div class="dropdown position-relative">
                <a href="#" class="sb-bell-btn dropdown-toggle shadow-none" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false" title="{{ $isArHdr ? 'الإشعارات' : 'Notifications' }}">
                    <i class="far fa-bell" style="font-size: 16px;"></i>
                    @if ($notifTotal > 0)
                        <span class="sb-bell-badge">{{ $notifTotal > 9 ? '+9' : $notifTotal }}</span>
                    @endif
                </a>
                <div class="dropdown-menu p-2 dropdown-menu-end" style="width: 290px; margin-top: 10px;">
                    <div class="px-3 py-1 mb-1 sb-dropdown-eyebrow border-bottom">{{ $isArHdr ? 'الإشعارات' : 'Notifications' }}</div>
                    <a class="sb-notif-item" href="{{ url('/ShowAllNotifications') }}">
                        <span class="sb-notif-icon" style="background:#fffbeb;color:#d97706;"><i class="fas fa-box-open"></i></span>
                        <span>{{ $isArHdr ? 'منتجات وصلت لحد الطلب' : 'Low-stock products' }}</span>
                        <span class="sb-notif-count" style="background:{{ $lowStockCount ? '#fef3c7' : '#f1f5f9' }};color:{{ $lowStockCount ? '#b45309' : '#64748b' }};">{{ $lowStockCount }}</span>
                    </a>
                    <a class="sb-notif-item" href="{{ url('previousSales_not_sended_Invoices_all_branchs') }}">
                        <span class="sb-notif-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-paper-plane"></i></span>
                        <span>{{ $isArHdr ? 'فواتير لم تُرسل لهيئة الزكاة' : 'Invoices not sent to ZATCA' }}</span>
                        <span class="sb-notif-count" style="background:{{ $totalUnsent ? '#fee2e2' : '#f1f5f9' }};color:{{ $totalUnsent ? '#b91c1c' : '#64748b' }};">{{ $totalUnsent }}</span>
                    </a>
                </div>
            </div>

            <!-- 1. زر اختيار اللغة (Language Selector) -->
            <div class="dropdown position-relative">
                <a href="#" class="sb-pill-btn dropdown-toggle shadow-none" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="sb-pill-icon"><i class="fas fa-globe"></i></span>
                    <span class="d-none d-md-inline">{{ $isArHdr ? 'العربية' : 'English' }}</span>
                    <i class="fe fe-chevron-down sb-caret-icon"></i>
                </a>

                <div class="dropdown-menu p-2 dropdown-menu-end" style="width: 175px; margin-top: 10px;">
                    <div class="px-3 py-1 mb-1 sb-dropdown-eyebrow border-bottom">
                        {{ __('home.select_language') }}
                    </div>
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a class="dropdown-item d-flex align-items-center justify-content-between px-3 py-2 rounded-3 {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'active' : 'text-dark' }}"
                           rel="alternate" hreflang="{{ $localeCode }}"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                           style="font-size: 12.5px;">
                            <span>{{ $properties['native'] }}</span>
                            @if(LaravelLocalization::getCurrentLocale() == $localeCode)
                                <i class="fas fa-check text-primary" style="font-size: 11px;"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 2. زر حالة الربط الضريبي ZATCA (هيئة الزكاة والضريبة والجمارك) -->
            <div class="dropdown position-relative">
                <a class="sb-zatca-btn dropdown-toggle shadow-none {{ $totalUnsent > 0 ? 'has-pending' : '' }}" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('home.tax_integration') }}">
                    @if($totalUnsent > 0)
                        <i class="fas fa-exclamation-triangle"></i>
                    @else
                        <span class="sb-zatca-pulse-dot"></span>
                    @endif
                    <span class="d-none d-md-inline">{{ __('home.tax_integration') }}</span>
                    @if($totalUnsent > 0)
                        <span class="sb-zatca-count">{{ $totalUnsent > 99 ? '99+' : $totalUnsent }}</span>
                    @endif
                    <i class="fe fe-chevron-down sb-caret-icon {{ $totalUnsent > 0 ? '' : 'text-white-50' }}"></i>
                </a>

                <div class="dropdown-menu p-0 dropdown-menu-end" style="width: 300px; margin-top: 10px; overflow: hidden;">
                    <div class="p-3 text-white sb-zatca-panel-head">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: rgba(255,255,255,0.12);">
                                    <i class="fas fa-university text-white" style="font-size: 13px;"></i>
                                </div>
                                <h6 class="mb-0 text-white" style="font-size: 13px; font-weight: 700;">{{ __('home.zatca_authority') }}</h6>
                            </div>
                            <span class="badge" style="font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 20px; background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3);">
                                {{ __('home.live') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-3 bg-white text-center">
                        @if($totalUnsent > 0)
                        <div class="mb-3 text-start px-3 py-2 rounded-3" style="background: #fff5f5; border-inline-start: 4px solid #ef4444;">
                            <p class="mb-1 text-dark d-flex align-items-center gap-1" style="font-size: 12px; font-weight: 700;">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                <span>{{ __('home.pending_invoices_warning') }}</span>
                            </p>
                            <p class="text-muted mb-0" style="font-size: 11px;">
                                {{ __('home.you_have') }} <b class="text-danger">{{ $totalUnsent }}</b> {{ __('home.invoices_waiting_to_send') }}
                            </p>
                        </div>

                        <a href="{{ url('previousSales_not_sended_Invoices_all_branchs') }}"
                            class="btn btn-sm btn-block text-white py-2 d-flex align-items-center justify-content-center gap-2"
                            style="background: #2563eb; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; border: none; box-shadow: 0 2px 6px rgba(37,99,235,0.3);">
                            <span>{{ __('home.send_pending_invoices_now') }}</span>
                            <i class="fas fa-paper-plane" style="font-size: 11px;"></i>
                        </a>
                        @else
                        <div class="py-2">
                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 44px; height: 44px; background: #ecfdf5; border: 1px solid #d1fae5;">
                                <i class="fas fa-check-double text-success" style="font-size: 18px;"></i>
                            </div>
                            <p class="mb-1 text-dark font-weight-bold" style="font-size: 12.5px;">{{ __('home.integration_status_stable') }}</p>
                            <small class="text-muted" style="font-size: 11px;">{{ __('home.all_invoices_sent_successfully') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. زر الشاشة الكاملة (Fullscreen Toggle) -->
            <div class="d-none d-sm-flex align-items-center">
                <a class="sb-icon-btn" href="#" id="sbFullscreenToggle" title="{{ __('home.fullscreen') ?? 'شاشة كاملة' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                </a>
            </div>

            <span class="sb-hdr-divider"></span>

            <!-- 4. كبسولة الملف الشخصي والمستخدم (User Profile Capsule) -->
            <div class="dropdown position-relative">
                <a class="sb-user-capsule dropdown-toggle shadow-none" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img alt="user" src="{{ Auth::user()->profile_photo_url }}" class="sb-user-avatar">
                    <span class="sb-user-meta d-none d-lg-flex">
                        <span class="sb-user-name">{{ Auth::user()->name }}</span>
                        <span class="sb-user-role">{{ collect(Auth::user()->roles_name ?? [])->first() ?? optional(Auth::user()->branch)->name }}</span>
                    </span>
                    <i class="fe fe-chevron-down sb-caret-icon"></i>
                </a>

                <div class="dropdown-menu p-0 dropdown-menu-end" style="width: 240px; margin-top: 10px; overflow: hidden;">
                    <div class="p-3 text-white sb-user-panel-head">
                        <div class="d-flex align-items-center gap-2">
                            <img alt="user" src="{{ Auth::user()->profile_photo_url }}" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid rgba(255,255,255,.3);">
                            <div class="overflow-hidden">
                                <h6 class="mb-0 text-white text-truncate" style="font-size: 13px; font-weight: 700;">{{ Auth::user()->name }}</h6>
                                <span class="text-white-50 text-truncate d-block" style="font-size: 11px;">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-2">
                        <a class="dropdown-item py-2 px-3 rounded-3 text-dark d-flex align-items-center gap-2" href="{{ url('user/profile') }}" style="font-size: 12.5px;">
                            <i class="bx bx-slider-alt text-primary" style="font-size: 16px;"></i>
                            <span>{{ __('auth.setting') }}</span>
                        </a>
                        <div class="dropdown-divider my-1"></div>
                        <a class="dropdown-item py-2 px-3 rounded-3 text-danger d-flex align-items-center gap-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="font-size: 12.5px;">
                            <i class="bx bx-log-out" style="font-size: 16px;"></i>
                            <span>{{ __('home.logout') }}</span>
                        </a>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /main-header -->

<script>
    (function () {
        // الساعة
        var clock = document.getElementById('sbHeaderClock');
        if (clock) {
            var isAr = document.documentElement.getAttribute('dir') === 'rtl';
            var tick = function () {
                var d = new Date(), h = d.getHours(), m = String(d.getMinutes()).padStart(2, '0');
                var ap = h >= 12 ? (isAr ? 'م' : 'PM') : (isAr ? 'ص' : 'AM');
                h = h % 12 || 12;
                clock.textContent = String(h).padStart(2, '0') + ':' + m + ' ' + ap;
            };
            tick(); setInterval(tick, 15000);
        }

        // الروابط اللي بتفتح تابة جديدة (زي "منتج جديد") ماتفضلش عليها التركيز،
        // عشان Enter بتاع قارئ الباركود مايفتحهاش تاني لما ترجع للشاشة
        document.querySelectorAll('.sb-header a[target="_blank"]').forEach(function (a) {
            a.addEventListener('click', function () { setTimeout(function () { a.blur(); }, 0); });
        });

        // اسم كل تبويب يظهر عند المرور لما الشاشة صغيرة والأسماء مخفية
        document.querySelectorAll('.sb-header .shortcut-item-link').forEach(function (a) {
            var l = a.querySelector('.shortcut-label');
            if (l && !a.title) a.title = l.textContent.trim();
        });

        // زر القائمة في الموبايل يفتح/يقفل القائمة الجانبية (شاشة البيع السريع ليها تعامل خاص بيها)
        var sidebar = document.querySelector('.app-sidebar');
        document.querySelectorAll('.sb-header .app-sidebar__toggle').forEach(function (t) {
            t.addEventListener('click', function (e) {
                if (document.body.classList.contains('pos-mode') || window.innerWidth > 991 || !sidebar) return;
                e.preventDefault(); e.stopPropagation();
                sidebar.classList.toggle('sidebar-open');
            });
        });
        var overlay = document.querySelector('.app-sidebar__overlay');
        if (overlay && sidebar) overlay.addEventListener('click', function () { sidebar.classList.remove('sidebar-open'); });
    })();

    (function () {
        var sbFsBtn = document.getElementById('sbFullscreenToggle');
        if (!sbFsBtn) return;
        sbFsBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen && document.documentElement.requestFullscreen();
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        });
    })();
</script>
