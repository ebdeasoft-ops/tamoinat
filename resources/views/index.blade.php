@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
<style>
    .nav-link.position-relative {
        display: inline-flex !important;
        padding: 8px !important;
    }

    .custom-badge {
        position: absolute !important;
        top: -2px;
        right: -2px;
        background-color: #ff4d4f;
        color: white;
        font-size: 11px;
        font-weight: bold;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-menu {
        border: none !important;
        border-radius: 10px !important;
        overflow: hidden;
        width: 250px !important;
    }

    .main-notification-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .animate-delay-4 { animation-delay: 0.4s; }
    .animate-delay-5 { animation-delay: 0.5s; }

    /* ===== فلتر الفرع ===== */
    .branch-filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .branch-filter-card label {
        font-weight: 700;
        color: #334155;
        margin-bottom: 0;
        white-space: nowrap;
    }

    .branch-filter-card select {
        min-width: 220px;
    }

    /* حالة تحميل أثناء تحديث البيانات */
    #dashboardDynamicArea.is-loading {
        opacity: 0.4;
        pointer-events: none;
        transition: opacity .15s ease;
    }

    #branchLoadingIcon { display: none; }
    #branchLoadingIcon.is-loading {
        display: inline-block;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* كروت السندات والتحويلات */
    .mini-stat {
        border-radius: 14px;
        padding: 18px;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .mini-stat .mini-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    /* ===== تصميم احترافي لقسمي الموظف والبحث بالتاريخ ===== */
    .employee-lookup-card .card-header,
    .invoice-search-card .card-header {
        background: linear-gradient(135deg, #419BB2 0%, #2c7a8c 100%);
        border-bottom: none;
    }

    .employee-lookup-card .card-header h6,
    .invoice-search-card .card-header h6 {
        color: #fff;
    }

    .employee-lookup-card .card-header i,
    .invoice-search-card .card-header i {
        color: #fff !important;
        background: rgba(255,255,255,.2);
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-inline-end: 6px;
    }

    .stat-pill {
        background: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        padding: 14px 22px;
        min-width: 150px;
        text-align: center;
        transition: all .2s ease;
    }

    .stat-pill .stat-label {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    .stat-pill .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
    }

    .stat-pill.accent-primary .stat-value { color: #419BB2; }
    .stat-pill.accent-success .stat-value { color: #22c55e; }

    .nice-select-wrapper { position: relative; }

    .nice-select-wrapper select,
    .nice-input {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        padding: 10px 14px !important;
        font-size: 14px !important;
        font-weight: 600;
        color: #334155;
        background-color: #fff !important;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .nice-select-wrapper select:focus,
    .nice-input:focus {
        border-color: #419BB2 !important;
        box-shadow: 0 0 0 3px rgba(65,155,178,.12) !important;
        outline: none;
    }

    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
        letter-spacing: .2px;
    }

    .search-btn-pro {
        background: linear-gradient(135deg, #419BB2 0%, #2c7a8c 100%);
        border: none;
        border-radius: 10px;
        padding: 10px 26px;
        font-weight: 700;
        color: #fff;
        transition: transform .15s ease, box-shadow .15s ease;
        box-shadow: 0 4px 12px rgba(65,155,178,.25);
    }

    .search-btn-pro:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(65,155,178,.35);
        color: #fff;
    }

    .search-btn-pro:disabled {
        opacity: .7;
        transform: none;
    }

    .result-summary-badge {
        background: #eef6f8;
        color: #1f5e6b;
        border-radius: 20px;
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .pro-table thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding: 14px 12px !important;
        border-bottom: 2px solid #eef2f6 !important;
    }

    .pro-table tbody td {
        padding: 13px 12px !important;
        font-size: 14px;
        color: #334155;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .pro-table tbody tr:hover { background: #f8fafc; }
    .pro-table tbody tr:last-child td { border-bottom: none !important; }

    .empty-hint {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 14px;
    }

    .empty-hint i {
        font-size: 30px;
        display: block;
        margin-bottom: 10px;
        color: #cbd5e1;
    }
</style>
@endsection

@section('title')
{{ __('home.home') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between align-items-center my-4 p-3 bg-white shadow-sm animate__animated animate__fadeInDown"
    style="border-radius: 15px;">
    <div class="left-content {{ App::getLocale() == 'en' ? 'text-left' : 'text-right' }}">
        <h2 class="main-content-title tx-24 mg-b-1 welcoming font-weight-bold" style="color: #004d44;">
            {{ __('home.welcome') }}
            <span class="text-dark">{{ Auth::user()->name }}</span> !
        </h2>
    </div>

    <div class="dropdown nav-item main-header-notification"></div>

    <div class="main-dashboard-header-right">
        <div class="datetime-wrapper d-flex align-items-center px-3 py-2"
            style="background: #f0f4f4; border-radius: 12px; border: 1px solid #e0e6e6;">
            <div class="ml-3 d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px; background: #004d44; border-radius: 10px; color: white;">
                <i class="far fa-calendar-alt" style="font-size: 18px;"></i>
            </div>
            <div class="text-right">
                <div id="display_date" class="font-weight-bold" style="font-size: 14px; color: #004d44;"></div>
                <div id="display_time" class="text-muted" style="font-size: 12px; font-weight: 600;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
@can('Home')

<!-- فلتر الفرع -->
<div class="branch-filter-card animate__animated animate__fadeInUp">
    <label for="branchFilterSelect"><i class="fas fa-code-branch text-primary me-1"></i>
        {{ __('home.select_branch') }}</label>
    <select id="branchFilterSelect" class="form-control">
        <option value="">{{ __('home.all_branches') }}</option>
        @foreach($branches as $branch)
        <option value="{{ $branch->id }}" {{ (string) $selectedBranch === (string) $branch->id ? 'selected' : '' }}>
            {{ App::getLocale() == 'en' ? ($branch->name_en ?? $branch->name) : $branch->name }}
        </option>
        @endforeach
    </select>
    <i class="fas fa-circle-notch" id="branchLoadingIcon"></i>
    <span class="text-muted fs-13" id="branchFilterHint">{{ __('home.branch_filter_hint') }}</span>
</div>

<div id="dashboardDynamicArea">

    <!-- 1. صف البطاقات السريعة (KPIs) -->
    <div class="row row-sm mb-4 animate__animated animate__fadeInUp animate-delay-2">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
            <div class="card bg-primary-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">{{ __('home.salesdoday') }}</p>
                            <h3 class="mb-0 fw-bold"><span id="kpi_todayInvoicesCount">{{ $todayInvoicesCount }}</span>
                                <small class="fs-6">{{ __('home.invoice') }}</small>
                            </h3>
                        </div>
                        <div class="card-icon bg-white-20 rounded-circle p-3"><i class="fas fa-file-invoice fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
            <div class="card bg-success-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">{{ __('home.TODAYEARNINGS') }}</p>
                            <h3 class="mb-0 fw-bold"><span
                                    id="kpi_todayEarnings">{{ number_format($todayEarnings, 2) }}</span> <small
                                    class="fs-6">{{ __('home.SAR') }}</small></h3>
                        </div>
                        <div class="card-icon bg-white-20 rounded-circle p-3"><i class="fas fa-coins fs-3"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
            <div class="card bg-warning-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">{{ __('home.purchasesdoday') }}</p>
                            <h3 class="mb-0 fw-bold"><span
                                    id="kpi_todayPurchasesCount">{{ $todayPurchasesCount }}</span> <small
                                    class="fs-6">{{ __('home.invoice') }}</small></h3>
                        </div>
                        <div class="card-icon bg-white-20 rounded-circle p-3"><i class="fas fa-shopping-cart fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
            <div class="card bg-danger-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">{{ __('home.TODAYpurchases') }}</p>
                            <h3 class="mb-0 fw-bold"><span
                                    id="kpi_todayPurchasesTotal">{{ number_format($todayPurchasesTotal, 2) }}</span>
                                <small class="fs-6">{{ __('home.SAR') }}</small>
                            </h3>
                        </div>
                        <div class="card-icon bg-white-20 rounded-circle p-3"><i class="fas fa-wallet fs-3"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. سندات القبض والصرف + تحويل المنتجات بين الفروع -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="mini-stat">
                <div>
                    <span class="text-muted fs-12 d-block">{{ __('home.receipt_vouchers') }}</span>
                    <h5 class="mb-0 fw-bold"><span id="kpi_receiptVouchersCount">{{ $receiptVouchersCount }}</span>
                        {{ __('home.receipt_vouchers') }}</h5>
                    <small class="text-success fw-semibold"><span
                            id="kpi_receiptVouchersTotal">{{ number_format($receiptVouchersTotal, 2) }}</span>
                        {{ __('home.SAR') }}</small>
                </div>
                <div class="mini-icon bg-success-transparent text-success"><i class="fas fa-hand-holding-usd"></i></div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="mini-stat">
                <div>
                    <span class="text-muted fs-12 d-block">{{ __('home.payment_vouchers') }}</span>
                    <h5 class="mb-0 fw-bold"><span id="kpi_paymentVouchersCount">{{ $paymentVouchersCount }}</span>
                        {{ __('home.payment_vouchers') }}</h5>
                    <small class="text-danger fw-semibold"><span
                            id="kpi_paymentVouchersTotal">{{ number_format($paymentVouchersTotal, 2) }}</span>
                        {{ __('home.SAR') }}</small>
                </div>
                <div class="mini-icon bg-danger-transparent text-danger"><i class="fas fa-money-check-alt"></i></div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="mini-stat">
                <div>
                    <span class="text-muted fs-12 d-block">{{ __('home.branch_transfers') }}</span>
                    <h5 class="mb-0 fw-bold"><span id="kpi_transfersCount">{{ $transfersCount }}</span>
                        {{ __('home.transfer') }}</h5>
                    <small class="text-info fw-semibold"><span
                            id="kpi_transfersTotal">{{ number_format($transfersTotal, 2) }}</span>
                        {{ __('home.SAR') }}</small>
                </div>
                <div class="mini-icon bg-info-transparent text-info"><i class="fas fa-exchange-alt"></i></div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="mini-stat">
                <div>
                    <span class="text-muted fs-12 d-block">{{ __('home.returns_total') }}</span>
                    <h6 class="mb-1 fw-bold text-danger">{{ __('home.sales_return') }}: <span
                            id="kpi_salesReturnsTotal">{{ number_format($salesReturnsTotal, 2) }}</span></h6>
                    <h6 class="mb-0 fw-bold text-warning">{{ __('home.purchase_return') }}: <span
                            id="kpi_purchaseReturnsTotal">{{ number_format($purchaseReturnsTotal, 2) }}</span></h6>
                </div>
                <div class="mini-icon bg-warning-transparent text-warning"><i class="fas fa-undo-alt"></i></div>
            </div>
        </div>
    </div>

    <!-- 3. الرسوم البيانية والإحصائيات -->
    <div class="row row-sm mb-4">
        <div class="col-xl-6 col-lg-12 mb-3 mb-xl-0 animate__animated animate__fadeInLeft animate-delay-3">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        {{ __('home.sales_purchases_comparison_current_month') }}
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyOverviewChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-3 mb-xl-0 animate__animated animate__fadeInUp animate-delay-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                        <i class="fas fa-users text-primary me-2"></i> {{ __('home.customers_and_suppliers') }}
                    </h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                    <div style="height: 180px; width: 100%;">
                        <canvas id="partnersDoughnutChart"></canvas>
                    </div>
                    <div class="d-flex justify-content-around w-100 mt-2 pt-2 border-top">
                        <div class="text-center">
                            <span class="text-muted fs-11 fw-semibold d-block">{{ __('home.total_customers') }}</span>
                            <span class="fw-bold fs-16 text-primary">
                                <i class="fas fa-user-tag fs-13 me-1"></i> <span
                                    id="kpi_customersCount">{{ $customersCount }}</span>
                            </span>
                        </div>
                        <div class="vr"></div>
                        <div class="text-center">
                            <span class="text-muted fs-11 fw-semibold d-block">{{ __('home.total_suppliers') }}</span>
                            <span class="fw-bold fs-16 text-warning">
                                <i class="fas fa-truck-loading fs-13 me-1"></i> <span
                                    id="kpi_suppliersCount">{{ $suppliersCount }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 animate__animated animate__fadeInRight animate-delay-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                        <i class="fas fa-box text-primary me-2"></i> {{ __('home.returns_and_delivery') }}
                    </h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-around p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="p-2.5 bg-info-transparent rounded-circle me-3 text-center d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i class="fas fa-truck text-info fs-5"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 d-block">{{ __('home.sel_product_withoud_tax') }}
                                ({{ __('home.today') }})</span>
                            <h6 class="mb-0 fw-bold fs-13"><span
                                    id="kpi_todayDeliveryCount">{{ $todayDeliveryCount }}</span>
                                {{ __('home.invoice') }} <small class="text-success">(<span
                                        id="kpi_todayDeliveryNet">{{ number_format($todayDeliveryNet, 2) }}</span>)</small>
                            </h6>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-2">
                        <div class="p-2.5 bg-danger-transparent rounded-circle me-3 text-center d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i class="fas fa-undo-alt text-danger fs-5"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 d-block">{{ __('home.numberodreturnsSale') }}</span>
                            <h6 class="mb-0 fw-bold fs-13"><span
                                    id="kpi_uniqueReturnSalesCount">{{ $uniqueReturnSalesCount }}</span>
                                {{ __('home.invoice') }}</h6>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="p-2.5 bg-warning-transparent rounded-circle me-3 text-center d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i class="fas fa-box-open text-warning fs-5"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 d-block">{{ __('home.numberodreturnsPurchases') }}</span>
                            <h6 class="mb-0 fw-bold fs-13"><span
                                    id="kpi_resourcePurchasesCount">{{ $resourcePurchasesCount }}</span>
                                {{ __('home.invoice') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 3.5 أكتر فرع وأكتر موظف بيعًا اليوم -->
<div class="row row-sm mb-4 animate__animated animate__fadeInUp">
    <div class="col-xl-6 col-lg-12 mb-3 mb-xl-0">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                    <i class="fas fa-store text-primary me-2"></i> {{ __('home.top_branch_today') }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="topBranchesChart" style="max-height: 260px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-12">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                    <i class="fas fa-user-tie text-primary me-2"></i> {{ __('home.top_employee_today') }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="topEmployeesChart" style="max-height: 260px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- 3.6 اختيار موظف لعرض عدد فواتيره اليوم -->
<div class="row row-sm mb-4 animate__animated animate__fadeInUp">
    <div class="col-12">
        <div class="card employee-lookup-card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header py-3 px-4">
                <h6 class="card-title mb-0 fw-bold fs-15 d-flex align-items-center">
                    <i class="fas fa-user-check"></i> {{ __('home.view_employee_sales_today') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-end gap-4 flex-wrap">
                    <div class="nice-select-wrapper" style="min-width: 260px;">
                        <span class="field-label">{{ __('home.select_employee') }}</span>
                        <select id="employeeSelect" class="form-control nice-input">
                            <option value="">{{ __('home.choose_employee_placeholder') }}</option>
                            @foreach($employeesList as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                        &nbsp;
                        &nbsp;

                    <div id="employeeStatsResult" class="d-flex gap-3 flex-wrap" style="display:none !important;">
                        <div class="stat-pill accent-primary">
                            <span class="stat-label">{{ __('home.invoices_count') }}</span>
                            <span class="stat-value" id="employeeInvoiceCount">0</span>
                        </div>
                                                &nbsp;
                        &nbsp;

                        <div class="stat-pill accent-success">
                            <span class="stat-label">{{ __('home.total_sales') }}</span>
                            <span class="stat-value" id="employeeInvoiceTotal">0</span>
                        </div>
                    </div>
                        &nbsp;
                        &nbsp;

                    <div id="employeeStatsPlaceholder" class="text-muted fs-13">
                        <i class="fas fa-info-circle me-1"></i> {{ __('home.select_employee_hint') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3.7 بحث بالتاريخ + الموظف -->
<div class="row row-sm mb-4 animate__animated animate__fadeInUp">
    <div class="col-12">
        <div class="card invoice-search-card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header py-3 px-4">
                <h6 class="card-title mb-0 fw-bold fs-15 d-flex align-items-center">
                    <i class="fas fa-calendar-search"></i> {{ __('home.search_invoices_by_date_employee') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-end gap-3 flex-wrap mb-4">
                    <div>
                        <span class="field-label">{{ __('home.date') }}</span>
                        <input type="date" id="searchDateInput" class="form-control nice-input" style="min-width: 190px;">
                    </div>
                                            &nbsp;
                        &nbsp;

                    <div>
                        <span class="field-label">{{ __('home.employee_optional') }}</span>
                        <select id="searchEmployeeSelect" class="form-control nice-input" style="min-width: 220px;">
                            <option value="">{{ __('home.all_employees') }}</option>
                            @foreach($employeesList as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                                            &nbsp;
                        &nbsp;

                    <button class="search-btn-pro" id="searchInvoicesBtn">
                        <i class="fas fa-search me-1"></i> {{ __('home.search') }}
                    </button>
                    <div class="ms-auto">
                        <span class="result-summary-badge" id="searchResultSummary" style="display:none;">
                            <i class="fas fa-chart-pie"></i> <span></span>
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center pro-table">
                        <thead>
                            <tr>
                                <th class="text-start">{{ __('home.invoice_number') }}</th>
                                <th class="text-start">{{ __('home.customer') }}</th>
                                <th>{{ __('home.employee') }}</th>
                                <th>{{ __('home.total') }}</th>
                                <th>{{ __('home.payment_method') }}</th>
                                <th class="text-end">{{ __('home.time') }}</th>
                            </tr>
                        </thead>
                        <tbody id="searchInvoicesResultBody">
                            <tr>
                                <td colspan="6">
                                    <div class="empty-hint">
                                        <i class="fas fa-calendar-day"></i>
                                        {{ __('home.select_date_search_hint') }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- 4. جدول تحويل المنتجات بين الفروع -->
    <div class="row row-sm mb-4 animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div
                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="p-2 bg-info-transparent rounded-circle me-2 d-flex align-items-center justify-content-center"
                            style="width: 38px; height: 38px;">
                            <i class="fas fa-exchange-alt text-info fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold fs-15 text-dark">{{ __('home.branch_transfers_today') }}</h6>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead>
                                <tr style="background-color: #f8fafc; color: #475569; font-size: 13px;">
                                    <th class="py-3 px-3 text-start">#</th>
                                    <th class="py-3 px-3">{{ __('home.from_branch') }}</th>
                                    <th class="py-3 px-3"></th>
                                    <th class="py-3 px-3">{{ __('home.to_branch') }}</th>
                                    <th class="py-3 px-3">{{ __('home.amount_sar') }}</th>
                                    <th class="py-3 px-3">{{ __('home.status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="transfersTableBody">
                                @include('partials.branch-transfers')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. أحدث العمليات -->
    <div class="row row-sm mb-4 animate__animated animate__fadeInUp animate-delay-5">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div
                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="p-2 bg-primary-transparent rounded-circle me-2 d-flex align-items-center justify-content-center"
                            style="width: 38px; height: 38px;">
                            <i class="fas fa-receipt text-primary fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold fs-15 text-dark">{{ __('home.latest_transactions_today') }}
                        </h6>
                    </div>
                    <a href="{{ url('/previousSalesInvoices') }}"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                        {{ __('home.view_all') }} <i class="fas fa-arrow-left ms-1 fs-11"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center"
                            style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background-color: #f8fafc; color: #475569; font-size: 13px;">
                                    <th class="py-3 px-3 text-start">{{ __('home.invoice_number') }}</th>
                                    <th class="py-3 px-3 text-start">{{ __('home.customer') }}</th>
                                    <th class="py-3 px-3">{{ __('home.total_amount') }}</th>
                                    <th class="py-3 px-3">{{ __('home.payment_method') }}</th>
                                    <th class="py-3 px-3">{{ __('home.status') }}</th>
                                    <th class="py-3 px-3 text-end">{{ __('home.time') }}</th>
                                </tr>
                            </thead>
                            <tbody id="latestInvoicesTableBody">
                                @include('partials.latest-invoices')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. آخر 5 عمليات شراء اليوم -->
    <div class="row row-sm mb-4 animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="p-2 bg-warning-transparent rounded-circle me-2 d-flex align-items-center justify-content-center"
                            style="width: 38px; height: 38px;">
                            <i class="fas fa-shopping-basket text-warning fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold fs-15 text-dark">آخر 5 عمليات شراء اليوم</h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead>
                                <tr style="background-color: #f8fafc; color: #475569; font-size: 13px;">
                                    <th class="py-3 px-3 text-start">رقم الفاتورة</th>
                                    <th class="py-3 px-3 text-start">المورد</th>
                                    <th class="py-3 px-3">المبلغ</th>
                                    <th class="py-3 px-3">طريقة الدفع</th>
                                    <th class="py-3 px-3 text-end">الوقت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestPurchasesToday as $purchase)
                                    <tr>
                                        <td class="text-start">{{ $purchase->orderId ?? $purchase->purchase_invoice_no ?? $purchase->id }}</td>
                                        <td class="text-start">{{ optional($purchase->supllier)->name ?? '—' }}</td>
                                        <td>{{ number_format($purchase->In_debt, 2) }}</td>
                                        <td>{{ $purchase->Pay_Method_Name ?? '—' }}</td>
                                        <td class="text-end">{{ \Carbon\Carbon::parse($purchase->created_at)->format('H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا توجد عمليات شراء اليوم</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /#dashboardDynamicArea -->

@endcan
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{--
=====================================================================
عدّل الـ JS بتاع البحث عشان يستخدم مفاتيح الترجمة بدل النصوص الثابتة
=====================================================================
--}}
<script>
$(document).ready(function () {
    // ===== عند اختيار موظف =====
    $('#employeeSelect').on('change', function () {
        const employeeId = $(this).val();
        const $result = $('#employeeStatsResult');
        const $placeholder = $('#employeeStatsPlaceholder');

        if (!employeeId) {
            $result.hide();
            $placeholder.show();
            return;
        }

        $.get("{{ route('dashboard.employee-invoices-today') }}", { employee_id: employeeId })
            .done(function (res) {
                $('#employeeInvoiceCount').text(res.count);
                $('#employeeInvoiceTotal').text(res.total + ' {{ __('home.sar_currency') }}');
                $placeholder.hide();
                $result.css('display', 'flex');
            })
            .fail(function () {
                alert('{{ __('home.employee_fetch_error') }}');
            });
    });

    // ===== البحث بالتاريخ والموظف =====
    $('#searchInvoicesBtn').on('click', function () {
        const date = $('#searchDateInput').val();
        const employeeId = $('#searchEmployeeSelect').val();

        if (!date) {
            alert('{{ __('home.select_employee_alert') }}');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __('home.searching') }}');

        $.get("{{ route('dashboard.search-invoices') }}", { search_date: date, employee_id: employeeId })
            .done(function (res) {
                $('#searchInvoicesResultBody').html(res.html);
                $('#searchResultSummary').show().find('span')
                    .text('{{ __('home.invoices_count') }}: ' + res.count + ' — {{ __('home.total') }}: ' + res.total + ' {{ __('home.sar_currency') }}');
            })
            .fail(function () {
                alert('{{ __('home.search_fetch_error') }}');
            })
            .always(function () {
                $btn.prop('disabled', false).html('<i class="fas fa-search me-1"></i> {{ __('home.search') }}');
            });
    });
});
</script>
<script>
    let partnersChart, monthlyChart;

    document.addEventListener("DOMContentLoaded", function () {

        // ===== مخطط أكتر فرع بيعًا اليوم =====
        const topBranchesCtx = document.getElementById('topBranchesChart').getContext('2d');
        new Chart(topBranchesCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($topBranches as $tb)
                        '{{ optional($tb->branch)->name ?? "فرع #".$tb->branchs_id }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'إجمالي المبيعات (ر.س)',
                    data: [
                        @foreach($topBranches as $tb)
                            {{ $tb->total_sales ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: '#3b82f6',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // ===== مخطط أكتر موظف بيعًا اليوم =====
        const topEmployeesCtx = document.getElementById('topEmployeesChart').getContext('2d');
        new Chart(topEmployeesCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($topEmployees as $te)
                        '{{ optional($te->user)->name ?? "موظف #".$te->user_id }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'إجمالي المبيعات (ر.س)',
                    data: [
                        @foreach($topEmployees as $te)
                            {{ $te->total_sales ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: '#22c55e',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });

        const ctxPartners = document.getElementById('partnersDoughnutChart').getContext('2d');
        partnersChart = new Chart(ctxPartners, {
            type: 'doughnut',
            data: {
                labels: ['{{ __('home.total_customers') }}', '{{ __('home.total_suppliers') }}'],
                datasets: [{
                    data: [{{ $customersCount ?? 0 }}, {{ $suppliersCount ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'inherit', size: 12 }, padding: 15, usePointStyle: true } },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' ' + context.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });

        const ctx = document.getElementById('monthlyOverviewChart').getContext('2d');
        monthlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    '{{ __('home.total_sales') }}',
                    '{{ __('home.tax_free_deliveries') }}',
                    '{{ __('home.total_purchases') }}'
                ],
                datasets: [{
                    label: '{{ __('home.amount_sar') }}',
                    data: [
                        {{ $monthSales ?? 0 }},
                        {{ $monthDeliverySales ?? 0 }},
                        {{ $monthPurchasesTotal ?? 0 }}
                    ],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: function (context) {
                        const chart = context.chart;
                        const { ctx, chartArea } = chart;
                        if (!chartArea) {
                            return null;
                        }
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(54, 162, 235, 0.05)');
                        gradient.addColorStop(1, 'rgba(54, 162, 235, 0.3)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function (value) { return value.toLocaleString() + ' {{ __('home.SAR') }}'; } },
                        grid: { color: '#f0f0f0' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });

    // --- دالة جلب الفواتير المعلقة عبر AJAX ---
    function fetchPendingInvoices() {
        const apiUrl = "/get-pending-invoices";
        $.ajax({
            url: apiUrl,
            type: "GET",
            dataType: "json",
            success: function (response) {
                let invoiceList = $('#invoice_list');
                if (invoiceList.length) {
                    invoiceList.empty();
                    let invoices = Array.isArray(response) ? response : [];
                    $('#invoice_count').text(invoices.length);

                    if (invoices.length > 0) {
                        invoices.forEach(function (invoice) {
                            invoiceList.append(`
                                <a class="d-flex p-3 border-bottom" href="{{ url('purchase_details') }}/${invoice.id}">
                                    <div class="mr-3 ml-3">
                                        <h5 class="notification-label mb-1">رقم الفاتورة: ${invoice.id}</h5>
                                    </div>
                                </a>
                            `);
                        });
                    } else {
                        invoiceList.html('<p class="text-center p-3 text-muted">لا توجد فواتير معلقة</p>');
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("خطأ أثناء جلب الفواتير المعلقة:", error);
            }
        });
    }

    // --- دالة تحديث الوقت والتاريخ تلقائياً ---
    function updateDateTime() {
        var now = new Date();
        var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var currentDate = now.toLocaleDateString('ar-SA', dateOptions);
        var timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
        var currentTime = now.toLocaleTimeString('ar-SA', timeOptions);

        if (document.getElementById('display_date')) document.getElementById('display_date').innerHTML = currentDate;
        if (document.getElementById('display_time')) document.getElementById('display_time').innerHTML = currentTime;

        setTimeout(updateDateTime, 1000);
    }

    // --- تحديث لوحة التحكم بالكامل عند تغيير الفرع (AJAX بدون Reload) ---
    function loadBranchStats(branchId, pushState) {
        const $area = $('#dashboardDynamicArea');
        const $icon = $('#branchLoadingIcon');

        $area.addClass('is-loading');
        $icon.addClass('is-loading');

        $.get("{{ route('home.branch-stats') }}", { branch_id: branchId })
            .done(function (res) {
                const kpis = res.kpis;
                Object.keys(kpis).forEach(function (key) {
                    const $el = $('#kpi_' + key);
                    if ($el.length) $el.text(kpis[key]);
                });

                if (monthlyChart) {
                    monthlyChart.data.datasets[0].data = [
                        res.chart.monthSales,
                        res.chart.monthDeliverySales,
                        res.chart.monthPurchasesTotal
                    ];
                    monthlyChart.update();
                }

                if (partnersChart) {
                    partnersChart.data.datasets[0].data = [res.chart.customersCount, res.chart.suppliersCount];
                    partnersChart.update();
                }

                $('#latestInvoicesTableBody').html(res.latestInvoicesHtml);
                $('#transfersTableBody').html(res.transfersHtml);

                if (pushState && window.history && window.history.pushState) {
                    const newUrl = window.location.pathname + (branchId ? ('?branch_id=' + branchId) : '');
                    window.history.pushState({ path: newUrl }, '', newUrl);
                }
            })
            .fail(function () {
                console.error('تعذر تحديث بيانات الفرع');
            })
            .always(function () {
                $area.removeClass('is-loading');
                $icon.removeClass('is-loading');
            });
    }

    $(document).ready(function () {

        // ===== عند اختيار موظف: عرض عدد فواتيره اليوم =====
        $('#employeeSelect').on('change', function () {
            const employeeId = $(this).val();
            const $result = $('#employeeStatsResult');
            const $placeholder = $('#employeeStatsPlaceholder');

            if (!employeeId) {
                $result.hide();
                $placeholder.show();
                return;
            }

            $.get("{{ route('dashboard.employee-invoices-today') }}", { employee_id: employeeId })
                .done(function (res) {
                    $('#employeeInvoiceCount').text(res.count);
                    $('#employeeInvoiceTotal').text(res.total + ' ر.س');
                    $placeholder.hide();
                    $result.css('display', 'flex');
                })
                .fail(function () {
                    alert('تعذر جلب بيانات الموظف');
                });
        });

        // ===== البحث بالتاريخ والموظف =====
        $('#searchInvoicesBtn').on('click', function () {
            const date = $('#searchDateInput').val();
            const employeeId = $('#searchEmployeeSelect').val();

            if (!date) {
                alert('من فضلك اختر تاريخ');
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> جاري البحث...');

            $.get("{{ route('dashboard.search-invoices') }}", { search_date: date, employee_id: employeeId })
                .done(function (res) {
                    $('#searchInvoicesResultBody').html(res.html);
                    $('#searchResultSummary').show().find('span')
                        .text('عدد الفواتير: ' + res.count + ' — الإجمالي: ' + res.total + ' ر.س');
                })
                .fail(function () {
                    alert('تعذر تنفيذ البحث');
                })
                .always(function () {
                    $btn.prop('disabled', false).html('<i class="fas fa-search me-1"></i> بحث');
                });
        });

        updateDateTime();
        fetchPendingInvoices();
        setInterval(fetchPendingInvoices, 120000);

        $('#invoice-no-btn').on('click', function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('show');
            $(this).next('.dropdown-menu').toggleClass('show');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.main-header-notification').length) {
                $('.main-header-notification').removeClass('show');
                $('.dropdown-menu').removeClass('show');
            }
        });

        // تفعيل فلتر الفرع
        $('#branchFilterSelect').on('change', function () {
            loadBranchStats($(this).val(), true);
        });
    });
</script>
@endsection