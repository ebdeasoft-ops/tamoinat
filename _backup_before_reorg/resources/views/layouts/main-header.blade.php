<!-- main-header opened -->
<style>
    /* =====================================================================
       EBDEA SOFT — Top Header
       يعيد استخدام نفس متغيرات هوية الشريط الجانبي (main-sidebar.blade.php)
       عشان الهيدر والسايدبار يبانوا نظام واحد متناسق بدل تصميمين مختلفين.
       ===================================================================== */
    .sb-header {
        --hdr-bg: #ffffff;
        --hdr-border: rgba(15, 23, 42, .07);
        --hdr-shadow: 0 2px 18px rgba(15, 23, 42, .05);
        --hdr-text: #0f172a;
        --hdr-muted: #64748b;
        --hdr-accent: var(--sb-blue-600, #f97316);
        --hdr-accent-soft: rgba(249, 115, 22, .1);
        --hdr-navy-900: var(--sb-navy-900, #0a0e18);
        --hdr-navy-800: var(--sb-navy-800, #131a2c);
        --hdr-pill-bg: #f8fafc;
        --hdr-pill-border: #e6eaf0;
        --hdr-radius: 999px;
        --hdr-font: var(--sb-font, 'IBM Plex Sans Arabic', 'Cairo', 'Segoe UI', system-ui, sans-serif);

        background: var(--hdr-bg) !important;
        box-shadow: var(--hdr-shadow);
        border-bottom: 1px solid var(--hdr-border);
        font-family: var(--hdr-font);
    }

    .sb-header .the-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        min-height: 68px;
    }

    /* ---- left cluster: logo, sidebar toggle, quick links ---------------- */
    .sb-header .main-header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .sb-header .responsive-logo img {
        max-height: 36px;
    }

    .sb-header .app-sidebar__toggle a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        color: var(--hdr-text) !important;
        transition: background .15s ease, color .15s ease;
    }

    .sb-header .app-sidebar__toggle a:hover {
        background: var(--hdr-accent-soft);
        color: var(--hdr-accent) !important;
    }

    .sb-header .header-shortcut-nav {
        display: flex;
        gap: 4px;
        list-style: none;
        padding: 0;
        margin: 0;
        align-items: center;
    }

    .sb-header .header-shortcut-nav .nav-link {
        display: flex !important;
        align-items: center;
        gap: 8px;
        padding: 8px 14px !important;
        border-radius: var(--hdr-radius);
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--hdr-text) !important;
        text-decoration: none;
        transition: background .15s ease, color .15s ease;
    }

    .sb-header .header-shortcut-nav .nav-link:hover {
        background: var(--hdr-accent-soft);
        color: var(--hdr-accent) !important;
    }

    .sb-header .header-shortcut-nav .nav-link i {
        font-size: 14px;
        color: var(--hdr-accent) !important;
        margin: 0 !important;
    }

    /* ---- right cluster: language / zatca / fullscreen / profile --------- */
    .sb-header .main-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sb-header .sb-pill-btn {
        display: flex !important;
        align-items: center;
        gap: 8px;
        height: 38px;
        padding: 0 14px;
        border-radius: var(--hdr-radius);
        background: var(--hdr-pill-bg);
        border: 1px solid var(--hdr-pill-border);
        color: var(--hdr-text);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 700;
        transition: border-color .15s ease, background .15s ease;
    }

    .sb-header .sb-pill-btn:hover,
    .sb-header .sb-pill-btn:focus,
    .sb-header .sb-pill-btn.show {
        border-color: var(--hdr-accent);
        background: var(--hdr-accent-soft);
        color: var(--hdr-text);
    }

    .sb-header .sb-pill-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--hdr-accent-soft);
        color: var(--hdr-accent);
        font-size: 11px;
        flex-shrink: 0;
    }

    .sb-header .sb-zatca-btn {
        display: flex !important;
        align-items: center;
        gap: 8px;
        height: 38px;
        padding: 0 14px;
        border-radius: var(--hdr-radius);
        background: linear-gradient(135deg, var(--hdr-navy-900) 0%, var(--hdr-navy-800) 100%);
        color: #fff !important;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        border: none;
        position: relative;
    }

    .sb-header .sb-zatca-badge {
        position: absolute;
        top: -6px;
        inset-inline-end: -8px;
        font-size: 9px;
        font-weight: 800;
        color: #fff;
        background: #ef4444;
        border: 1.5px solid #fff;
        border-radius: 999px;
        padding: 1px 5px;
        line-height: 1.4;
    }

    .sb-header .sb-icon-btn {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        color: var(--hdr-muted) !important;
        transition: background .15s ease, color .15s ease;
    }

    .sb-header .sb-icon-btn:hover {
        background: var(--hdr-accent-soft);
        color: var(--hdr-accent) !important;
    }

    .sb-header .sb-user-trigger {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .sb-header .sb-user-trigger img {
        width: 38px;
        height: 38px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid var(--hdr-accent);
    }

    /* ---- dropdown panels --------------------------------------------- */
    .sb-header .dropdown-menu {
        border: none !important;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .14) !important;
        border-radius: 14px !important;
    }

    .sb-header .dropdown-item {
        font-family: var(--hdr-font);
    }

    .sb-header .dropdown-item.active,
    .sb-header .dropdown-item:active {
        background: var(--hdr-accent-soft) !important;
        color: var(--hdr-accent) !important;
    }

    .sb-header .sb-dropdown-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .03em;
        color: var(--hdr-muted);
    }

    .sb-header .sb-zatca-panel-head,
    .sb-header .sb-user-panel-head {
        background: linear-gradient(135deg, var(--hdr-navy-900) 0%, var(--hdr-navy-800) 100%) !important;
    }
</style>

<div class="main-header sticky side-header nav nav-item sb-header">
    <div class="container-fluid the-main">

        <!-- القسم الأيمن (الشعار، زر القائمة، والروابط السريعة) -->
        <div class="main-header-left">
            <div class="responsive-logo">
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo.png') }}" class="logo-1" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo-white.png') }}" class="dark-logo-1" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="logo-2" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="dark-logo-2" alt="logo"></a>
            </div>

            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>

            <!-- الروابط السريعة المباشرة في الهيدر العلوي -->
            <div class="d-none d-xl-flex align-items-center">
                <ul class="nav header-shortcut-nav mb-0">

                    @can('Sales products')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/goToSale') }}">
                            <i class="fas fa-cash-register"></i>
                            <span>{{ __('home.sales') }}</span>
                        </a>
                    </li>
                    @endcan

                    @can('sales return')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/return_sale') }}">
                            <i class="fas fa-undo"></i>
                            <span>{{ __('home.salesـreturned') }}</span>
                        </a>
                    </li>
                    @endcan

                    @can('Purchases products')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/purchases') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>{{ __('home.purchases') }}</span>
                        </a>
                    </li>
                    @endcan

                    @can('offer price to customer')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/getproductspricetocustomer') }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>{{ __('home.Offerـpricesـtoـcustomer') }}</span>
                        </a>
                    </li>
                    @endcan

                </ul>
            </div>
        </div>

        <!-- القسم الأيسر (مجموعة أدوات التحكم متجاورة وبمسافات دقيقة) -->
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

            <!-- 1. زر تغيير اللغة -->
            <div class="dropdown position-relative">
                <a href="#" class="sb-pill-btn dropdown-toggle shadow-none" data-toggle="dropdown" data-bs-toggle="dropdown">
                    <span class="sb-pill-icon"><i class="fas fa-globe"></i></span>
                    <span class="d-none d-md-inline">
                        {{ LaravelLocalization::getCurrentLocaleName() == 'اللغة العربية' ? __('home.arabic') : LaravelLocalization::getCurrentLocaleName() }}
                    </span>
                </a>

                <div class="dropdown-menu shadow-lg border-0 p-2 dropdown-menu-end" style="width: 170px; margin-top: 8px;">
                    <div class="px-3 py-1 mb-1 sb-dropdown-eyebrow border-bottom">
                        {{ __('home.select_language') }}
                    </div>
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a class="dropdown-item d-flex align-items-center justify-content-between px-3 py-2 rounded {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'active' : 'text-dark' }}"
                           rel="alternate" hreflang="{{ $localeCode }}"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                           style="font-size: 12px;">
                            <span>{{ $properties['native'] }}</span>
                            @if(LaravelLocalization::getCurrentLocale() == $localeCode)
                                <i class="fas fa-check" style="font-size: 11px;"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 2. زر حالة الربط الضريبي ZATCA -->
            <div class="dropdown position-relative">
                <a class="sb-zatca-btn dropdown-toggle shadow-none" href="#" data-toggle="dropdown" data-bs-toggle="dropdown">
                    <span class="position-relative d-flex align-items-center justify-content-center">
                        <i class="fas fa-shield-alt"></i>
                        @if($totalUnsent > 0)
                        <span class="sb-zatca-badge">{{ $totalUnsent }}</span>
                        @endif
                    </span>
                    <span class="d-none d-md-inline">{{ __('home.tax_integration') }}</span>
                </a>

                <div class="dropdown-menu shadow-lg border-0 p-0 dropdown-menu-end" style="width: 290px; margin-top: 8px; overflow: hidden;">
                    <div class="p-3 text-white sb-zatca-panel-head">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(255,255,255,0.15);">
                                    <i class="fas fa-university text-white" style="font-size: 12px;"></i>
                                </div>
                                <h6 class="mb-0 text-white" style="font-size: 13px; font-weight: 700;">{{ __('home.zatca_authority') }}</h6>
                            </div>
                            <span class="badge" style="font-size: 10px; font-weight: bold; padding: 3px 7px; border-radius: 20px; background: rgba(255,255,255,.15); color: #fff;">
                                {{ __('home.live') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-3 bg-white text-center">
                        @if($totalUnsent > 0)
                        <div class="mb-3 text-start px-3 py-2 rounded" style="background: #fff5f5; border-right: 4px solid #ef4444;">
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
                            style="background: var(--sb-blue-600, #f97316); border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none;">
                            <span>{{ __('home.send_pending_invoices_now') }}</span>
                            <i class="fas fa-paper-plane" style="font-size: 10px;"></i>
                        </a>
                        @else
                        <div class="py-2">
                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 40px; height: 40px; background: #e8f5e9;">
                                <i class="fas fa-check-double text-success" style="font-size: 16px;"></i>
                            </div>
                            <p class="mb-1 text-dark font-weight-bold" style="font-size: 12px;">{{ __('home.integration_status_stable') }}</p>
                            <small class="text-muted" style="font-size: 10px;">{{ __('home.all_invoices_sent_successfully') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. زر الشاشة الكاملة -->
            <div class="d-none d-sm-flex align-items-center">
                <a class="sb-icon-btn" href="#" id="sbFullscreenToggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                </a>
            </div>

            <!-- 4. قائمة المستخدم (Profile) -->
            <div class="dropdown position-relative">
                <a class="sb-user-trigger" href="#" data-toggle="dropdown" data-bs-toggle="dropdown">
                    <img alt="user" src="{{ Auth::user()->profile_photo_url }}">
                </a>

                <div class="dropdown-menu shadow-lg border-0 p-0 dropdown-menu-end" style="width: 230px; margin-top: 8px; overflow: hidden;">
                    <div class="p-3 text-white sb-user-panel-head">
                        <div class="d-flex align-items-center gap-2">
                            <img alt="user" src="{{ Auth::user()->profile_photo_url }}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid rgba(255,255,255,.25);">
                            <div class="overflow-hidden">
                                <h6 class="mb-0 text-white text-truncate" style="font-size: 12px; font-weight: 600;">{{ Auth::user()->name }}</h6>
                                <span class="text-white-50 text-truncate d-block" style="font-size: 10px;">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-2">
                        <a class="dropdown-item py-2 px-3 rounded text-dark d-flex align-items-center gap-2" href="{{ url('user/profile') }}" style="font-size: 12px;">
                            <i class="bx bx-slider-alt text-muted" style="font-size: 15px;"></i>
                            <span>{{ __('auth.setting') }}</span>
                        </a>
                        <a class="dropdown-item py-2 px-3 rounded text-danger d-flex align-items-center gap-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="font-size: 12px;">
                            <i class="bx bx-log-out" style="font-size: 15px;"></i>
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
