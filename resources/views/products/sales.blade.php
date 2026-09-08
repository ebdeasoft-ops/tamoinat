@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<!--Internal  Datatable js -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   شاشة المبيعات — نسخة مبسطة تعتمد على الباركود (Scoped)
   نفس هوية الألوان الاحترافية (كحلي/تركواز) لكن الجدول والمنطق مبسّطين
   عشان يناسبوا محل تموينات: باركود، اسم صنف، كمية، سعر، إجمالي — بس.
   ========================================================================== */

:root {
    --brand-navy: #1b3358;
    --brand-navy-light: #23395D;
    --brand-teal: #2f97ac;
    --brand-teal-dark: #1f7a8c;
    --accent-blue: #3d7bff;
    --success: #16a34a;
    --warning: #f59e0b;
    --danger: #e0293f;
    --danger-dark: #c81e33;
    --surface: #ffffff;
    --bg-page: #f4f6fa;
    --bg-soft: #f8fafc;
    --border: #e3e7ee;
    --border-strong: #cfd6e2;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 3px rgba(16, 24, 40, .06);
    --shadow-md: 0 6px 16px rgba(16, 24, 40, .08);
    --shadow-lg: 0 14px 34px rgba(16, 24, 40, .14);
    font-family: "Tajawal", "Cairo", sans-serif;
}

.pro-sales-page, .pro-sales-page table, .pro-sales-page td, .pro-sales-page th, .pro-sales-page input, .pro-sales-page button, .pro-sales-page select, .pro-sales-page .form-control {
    font-family: "Tajawal", "Cairo", sans-serif;
    font-weight: 600 !important;
    color: var(--text-main) !important;
}

/* -------------------- هيدر الصفحة -------------------- */
.pro-sales-header .breadcrumb-header {
    background: linear-gradient(135deg, var(--brand-navy) 0%, var(--brand-navy-light) 100%);
    border-radius: var(--radius-md);
    padding: 20px 24px;
    min-height: 88px;
    box-shadow: var(--shadow-md);
}

.pro-sales-header .content-title {
    color: #fff !important;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: .2px;
}

.pro-sales-header .btn {
    border-radius: 8px !important;
    font-weight: 700 !important;
    box-shadow: var(--shadow-sm);
    transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    border: none !important;
}

.pro-sales-header .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    filter: brightness(1.08);
}

/* -------------------- الكروت -------------------- */
.pro-sales-page .card {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-sm);
}

.pro-sales-page .card-header {
    background: transparent;
    border-bottom: none;
}

/* -------------------- جدول المنتجات -------------------- */
.pro-sales-page table {
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100%;
}

.pro-sales-page table.table-bordered {
    border: 1px solid var(--border) !important;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.pro-sales-page table thead th {
    background: linear-gradient(180deg, #f4f6fb 0%, #eef1f8 100%) !important;
    padding: 12px 8px;
    font-weight: 700 !important;
    color: var(--brand-navy) !important;
    text-align: center;
    border: 1px solid var(--border) !important;
    font-size: 13px;
    white-space: nowrap;
}

#productsTableBody tr {
    background: var(--surface);
    transition: background .15s ease;
}

#productsTableBody tr:nth-child(even) {
    background: #fafbfd;
}

#productsTableBody tr:hover {
    background: #eef4ff;
}

#productsTableBody td {
    border: 1px solid var(--border) !important;
    padding: 6px !important;
    vertical-align: middle;
}

#productsTableBody .form-control {
    height: 36px;
    padding: 4px 8px;
    text-align: center;
    min-width: 70px;
}

#productsTableBody .product-name {
    text-align: right;
    min-width: 180px;
}

/* -------------------- الحقول والقوائم -------------------- */
.pro-sales-page .form-control {
    border-radius: var(--radius-sm) !important;
    border: 1px solid var(--border-strong) !important;
    background: var(--surface);
    transition: border-color .15s ease, box-shadow .15s ease;
}

.pro-sales-page .form-control:focus {
    border-color: var(--accent-blue) !important;
    box-shadow: 0 0 0 3px rgba(61, 123, 255, .18);
    outline: none;
}

.pro-sales-page .form-control[readonly] {
    background: var(--bg-soft);
    color: var(--text-muted) !important;
}

.pro-sales-page .select2-container--default .select2-selection--single {
    height: 40px !important;
    border-radius: var(--radius-sm) !important;
    border: 1px solid var(--border-strong) !important;
    display: flex;
    align-items: center;
}

.pro-sales-page .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
}

.pro-sales-page .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px !important;
}

/* -------------------- الأزرار -------------------- */
.pro-sales-page .addProductBtn, .pro-sales-page #addProductBtn {
    background: linear-gradient(135deg, var(--brand-teal) 0%, var(--brand-teal-dark) 100%) !important;
    color: #fff !important;
    border: none;
    border-radius: var(--radius-sm) !important;
    font-weight: 700 !important;
    padding: 6px 14px;
    box-shadow: var(--shadow-sm);
    transition: transform .15s ease, box-shadow .15s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.pro-sales-page .addProductBtn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.pro-sales-page .btn-info, .pro-sales-page .btn-primary {
    background: linear-gradient(135deg, var(--accent-blue), #2f66e0) !important;
    border: none !important;
    color: #fff !important;
    border-radius: var(--radius-sm) !important;
    font-weight: 700 !important;
}

.pro-sales-page .btn-info:hover, .pro-sales-page .btn-primary:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
}

.pro-sales-page .btn-danger {
    background: linear-gradient(135deg, #ff5b5b, var(--danger)) !important;
    border: none !important;
    border-radius: var(--radius-sm) !important;
}

.pro-sales-page .btn-danger:hover {
    filter: brightness(1.08);
    transform: scale(1.04);
}

.pro-sales-page #saveInvoice, .pro-sales-page #saveAsDraft {
    border: none !important;
    border-radius: var(--radius-sm) !important;
    font-weight: 700 !important;
    padding: 6px 22px !important;
    font-size: 14px;
    box-shadow: var(--shadow-sm);
    transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
}

.pro-sales-page #saveInvoice:hover, .pro-sales-page #saveAsDraft:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    filter: brightness(1.05);
}

.pro-sales-page .invoice-actions-bar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 8px;
    margin-top: 4px;
    box-shadow: var(--shadow-sm);
}

/* -------------------- شريط الباركود السريع -------------------- */
.pro-sales-page .barcode-scanner-bar {
    background: linear-gradient(180deg, #fff 0%, var(--bg-soft) 100%);
    border: 2px solid var(--brand-teal);
    border-radius: var(--radius-lg);
    padding: 14px 18px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 18px !important;
}

.pro-sales-page .barcode-scanner-bar .input-group-text {
    background: linear-gradient(135deg, var(--brand-teal) 0%, var(--brand-teal-dark) 100%) !important;
    color: #fff !important;
    border: none !important;
    font-size: 20px;
    padding-left: 18px;
    padding-right: 18px;
}

.pro-sales-page #quick_scan {
    height: 48px !important;
    font-size: 1.15rem !important;
    font-weight: 700 !important;
    border: none !important;
    background: var(--bg-soft) !important;
}

.pro-sales-page #quick_scan:focus {
    box-shadow: none !important;
    background: #fff !important;
}

.pro-sales-page #btn_execute_scan {
    height: 48px;
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0 !important;
    font-size: 15px;
}

.pro-sales-page .barcode-scanner-bar small {
    color: var(--text-muted) !important;
    font-weight: 600 !important;
}

/* -------------------- ملخص الفاتورة -------------------- */
.pro-sales-page .invoice-summary-panel {
    background: linear-gradient(180deg, #fff 0%, var(--bg-soft) 100%) !important;
    border: 1px solid var(--border) !important;
    border-radius: var(--radius-md) !important;
}

.pro-sales-page .invoice-totals-grid .form-label {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 700 !important;
    margin-bottom: 4px;
}

.pro-sales-page .invoice-totals-grid input#grandTotal {
    background: var(--brand-navy) !important;
    color: #fff !important;
    font-size: 18px;
    font-weight: 800 !important;
    border: none !important;
    height: 44px;
}

.pro-sales-page .invoice-totals-grid input {
    height: 40px;
    text-align: center;
}

/* -------------------- شريط الطباعة -------------------- */
.pro-sales-page #printdiv {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    padding: 16px;
    background: var(--surface);
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.pro-sales-page #printdiv .btn, .pro-sales-page #printdiv a, .pro-sales-page #printdiv button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 38px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    transition: all .2s ease !important;
    border: none !important;
    padding: 0 16px !important;
    text-decoration: none !important;
    box-shadow: var(--shadow-sm) !important;
}

.pro-sales-page #send_whats_app {
    width: 45px !important;
    background-color: #25d366 !important;
    font-size: 20px !important;
    color: #fff !important;
    padding: 0 !important;
}

.pro-sales-page #printdiv a:hover, .pro-sales-page #printdiv button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md) !important;
    filter: brightness(1.08);
}

.pro-sales-page .svg-icon-buttons {
    fill: currentColor;
    margin-right: 5px;
    margin-left: 5px;
}

.pro-sales-page #sendzatca {
    background-color: #64748b !important;
    color: #fff !important;
}

/* -------------------- المودالات (غير محصورة عشان بتظهر برا .row) -------------------- */
.modal-content {
    border-radius: var(--radius-lg) !important;
    border: none;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--brand-navy) 0%, var(--brand-navy-light) 100%);
    border-bottom: none !important;
    padding: 14px 20px;
}

.modal-header .modal-title, .modal-header h6 {
    color: #fff !important;
    font-weight: 700 !important;
}

.modal-header .close {
    color: #fff;
    opacity: .85;
    text-shadow: none;
}

.modal-body {
    background: #fff;
    padding: 18px 20px;
}

.modal-footer {
    border-top: 1px solid var(--border);
    background: var(--bg-soft);
}

#productsTable th, #productsTable td, #SearchProductTable th, #SearchProductTable td {
    border: 1px solid var(--border) !important;
}

#productsTable tr:hover, #SearchProductTable tbody tr:hover {
    background: #eef3ff !important;
}

td .d-flex {
    align-items: center;
}

/* -------------------- شاشة التحميل -------------------- */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, .72);
    backdrop-filter: blur(2px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    color: #fff;
    font-size: 20px;
    display: none;
}

#loading-screen p {
    margin: 0 0 0 12px;
    font-weight: 700;
    line-height: 1.6;
}

#loading-animation {
    border: 4px solid rgba(255, 255, 255, .25);
    border-radius: 50%;
    border-top: 4px solid #3d7bff;
    width: 50px;
    height: 50px;
    animation: pro-sales-spin 1s linear infinite;
}

@keyframes pro-sales-spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* -------------------- استجابة الشاشات الصغيرة -------------------- */
@media (max-width: 991px) {
    .pro-sales-page .invoice-totals-grid>div {
        margin-bottom: 10px;
    }
}

@media (max-width: 767px) {
    .pro-sales-page #printdiv {
        justify-content: center;
    }

    .pro-sales-page #saveInvoice,
    .pro-sales-page #saveAsDraft {
        width: 100%;
        margin: 4px 0 !important;
    }

    .pro-sales-page .barcode-scanner-bar .input-group {
        flex-wrap: nowrap;
    }
}
</style>

<style>
/* أزرار أوضح: حجم أكبر شوية، حواف مدورة، ظل خفيف، وحركة عند الهوفر */
.pro3-header-row .pro3-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 10px 18px !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    border: none !important;
    border-radius: 8px !important;
    white-space: nowrap !important;
    letter-spacing: 0.2px;
    transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
}

.pro3-header-row .pro3-btn i {
    font-size: 13px !important;
}

.pro3-header-row .pro3-btn-navy {
    background-color: #23395D !important;
    box-shadow: 0 4px 10px rgba(35, 57, 93, 0.35) !important;
}

.pro3-header-row .pro3-btn-green {
    background-color: #1f9d55 !important;
    box-shadow: 0 4px 10px rgba(31, 157, 85, 0.35) !important;
}

.pro3-header-row .pro3-btn:hover, .pro3-header-row .pro3-btn:focus {
    filter: brightness(1.1);
    transform: translateY(-2px);
    color: #fff !important;
}

.pro3-header-row .pro3-btn:active {
    transform: translateY(0);
    filter: brightness(0.95);
}

@media (max-width: 767px) {
    .pro3-header-row .pro3-actions {
        width: 100%;
    }

    .pro3-header-row .pro3-btn {
        flex: 1 1 auto;
        justify-content: center;
    }
}
</style>
@section('title')
{{ __('home.sales') }}
@stop
@endsection
@section('page-header')
<div class="main-parent pro-sales-header">
    <div style="justify-content: space-between !important" class="breadcrumb-header parent-heading">
        <div class="my-auto" style="width:100%">
            <div class="d-flex align-items-center justify-content-between flex-wrap pro3-header-row"
                style="width:100%; gap: 10px;">

                <div class="d-flex align-items-center">
                    <h4 class="content-title mb-0">
                        <i class="fa fa-cash-register"></i> {{ __('home.sales') }}
                    </h4>
                </div>

                <div class="d-flex flex-wrap align-items-center pro3-actions" style="gap: 10px;">

                    {{-- خيار التحكم في إظهار الرقم --}}
                    <div class="select-wrapper">
                        <select class="form-control select2" name="numbershowstatus" id="numbershowstatus" required>
                            <option value="1">{{ __('home.shownumberselect') }}</option>
                            <option value="0">{{ __('home.notshow') }}</option>
                        </select>
                    </div>

                    {{-- زر إضافة عميل جديد --}}
                    <button type="button" class="btn btn-sm text-white pro3-btn pro3-btn-navy" data-toggle="modal"
                        href="#createcustomer">
                        <i class="fa fa-user-plus"></i> {{ __('home.addnewcustomer') }}
                    </button>

                    {{-- زر إضافة منتج جديد: يفتح صفحة إنشاء المنتج في تاب جديد --}}
                    <a href="{{ URL::to('addnewProduct') }}" target="_blank"
                        class="btn btn-sm text-white pro3-btn pro3-btn-navy">
                        <i class="fa fa-plus-circle"></i> {{ __('home.addnewproduct') }}
                    </a>

                    {{-- زر تحديث الفاتورة برقمها --}}
                    <button type="button" class="btn btn-sm text-white pro3-btn pro3-btn-green" data-toggle="modal"
                        href="#updateinvoicebyidmodale">
                        <i class="fa fa-sync-alt"></i> {{ __('home.updateinvoicebyid') }}
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
</div>

@endsection
@section('content')
<br>
<center>
    <div id="loading-screen">
        <div id="loading-animation"></div>
        &nbsp; <p> جارٍ إرسال الفاتورة، يرجى الانتظار <br>Invoice is being sent, please wait</p>
    </div>
</center>

<!-- row -->
<div class="row pro-sales-page">

    <div class="col-xl-12">
        <div class="card mg-b-20">

            <div class="card-header pb-0">
                <?php
    $avtSaleRate = App\Models\Avt::find(1);
    $avtSaleRate = $avtSaleRate->AVT;
    $system_settings = App\Models\system_setting::find(1);
    $discound_allow = $system_settings->discount_on_invoice;
                                                                                                     ?>
                <form enctype="multipart/form-data" method="POST" role="search" name="form-name" id='formdata'
                    autocomplete="off">
                    {{ csrf_field() }}

                    <input type="hidden" name="action" id="invoice_action">

                    <div style="border-radius: 10px" class="card p-3 my-3">

                        {{-- شريط الباركود السريع — نقطة الدخول الأساسية لإضافة الأصناف --}}
                        <div class="barcode-scanner-bar">
                            <div class="row align-items-center g-2">
                                <div class="col-md-8">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">
                                            <i class="fa fa-barcode"></i>
                                        </span>
                                        <input type="text" id="quick_scan" name="quick_scan"
                                            class="form-control" placeholder="{{ __('home.placeholder') ?? 'امسح الباركود أو اكتب الكود واضغط Enter' }}"
                                            autocomplete="off" autofocus>
                                        <button class="btn addProductBtn" style="width:auto" type="button"
                                            id="btn_execute_scan" onclick="executeProductSearch()">
                                            <i class="fa fa-plus-circle"></i>
                                            {{ __('home.Add') ?? 'إضافة' }}
                                        </button>
                                    </div>
                                    <small class="mt-2 d-block">
                                        <i class="fa fa-info-circle"></i>
                                        {{ __('home.presss') ?? 'اضغط' }} <strong>Enter</strong> {{ __('home.Add') ?? 'للإضافة' }}
                                    </small>
                                </div>
                           
                            </div>
                        </div>

                        <?php $i = 0; ?>
                        <table class="table-responsive table table-bordered">
                            <colgroup>
                                <col style="width:0.5%">
                                <col style="width:4%">
                                <col style="width:15%">
                                <col style="width:32%">
                                <col style="width:14%">
                                <col style="width:14%">
                                <col style="width:12%">
                                <col style="width:8%">
                            </colgroup>
                            <thead class="table-dark">
                                <tr>
                                    <th>- </th>
                                    <th> # </th>
                                    <th>{{ __('home.productNo') }} </th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('home.price') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                            </tbody>
                        </table>

                        <br>
     <div class="col-md-4 text-md-end">
                                    <button type="button" class="addProductBtn" style="background-color: #419BB2"
                                        onclick="addRow()"><i class="fa fa-search"></i>
                                        {{ __('supprocesses.addproduct') }}
                                    </button>
                                </div>
                        {{-- حقول مخفية لازمة لتوافق الحفظ (save_invoice_sale) لكن مالهاش داعي تتعرض للمستخدم --}}
                        <input type="text" readonly id="discound_on_invoice" name="discound_on_invoice"
                            class="form-control" value="0" hidden>

                        <div class="row mt-3 invoice-totals-grid">

                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="pro-totals-card">
                                    <span class="pro-stat-icon"
                                        style="background:rgba(61,123,255,.1); color:#3d7bff;"><i
                                            class="fa fa-coins"></i></span>
                                    <label class="form-label">{{ __('home.the amount') }}</label>
                                    <input readonly type="text" id="totalSum" name="totalSum"
                                        class="form-control parent-input">
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="pro-totals-card">
                                    <span class="pro-stat-icon"
                                        style="background:rgba(245,158,11,.12); color:#b45309;"><i
                                            class="fa fa-receipt"></i></span>
                                    <label class="form-label">{{ __('home.addedValue') }}</label>
                                    <input type="text" id="totalTax" name="totalTax" readonly
                                        class="form-control parent-input">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="pro-totals-card pro-total-grand">
                                    <span class="pro-stat-icon"><i class="fa fa-dollar-sign"></i></span>
                                    <label class="form-label">{{ __('home.total') }}</label>
                                    <input type="text" id="grandTotal" name="grandTotal" readonly
                                        class="form-control parent-input">
                                </div>
                            </div>

                            {{-- حقل الخصم الإجمالي بقى مخفي بالكامل (تم إلغاء موضوع الخصم من الشاشة) --}}
                            <input type="text" id="totaldiscound" name="totaldiscound" value="0" hidden>
                        </div>

                        <br>

                        <input type="number" class="form-control" name="show_invoice_number_update"
                            id="show_invoice_number_update" value=0 title=" رقم الفاتورة " hidden>

                        <div class='row'>

                            <div class="col-lg-5 mg-t-10">
                                <label for="clientnamesearch" class="pro-field-label"><i
                                        class="fa fa-user"></i>{{ __('home.chooseclient') }}</label>
                                <select style="width:100%!important" name="clientnamesearch" id="clientnamesearch"
                                    class="form-control select2">
                                    <option value=1>عميل نقدي CASH CUSTOMER</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mg-t-10">
                                <label for="paymentmethod" class="pro-field-label"><i
                                        class="fa fa-wallet"></i>{{ __('home.paymentmethod') }}</label>
                                <select class="form-control select2" name="paymentmethod" id="paymentmethod" required>
                                    @foreach (App\Models\financial_accounts::where('parent_account_number', 4)->where(
                                    'branchs_id',
                                    Auth()->user()->branchs_id
                                    )->orwhere('parent_account_number', 5)->where(
                                    'parent_account_number',
                                    '!=',
                                    NULL
                                    )->where('branchs_id', Auth()->user()->branchs_id)->get() as $section)
                                    <option value="{{ $section->id }}">
                                        {{ app()->getLocale() == 'en' ? $section->name_en : $section->name }}
                                        ({{ $section->account_number }})
                                    </option>
                                    @endforeach
                                    <option value="Credit"> {{ __('report.credit') }} </option>
                                    <option value="Partition"> {{ __('home.Partition of the amount') }} </option>
                                </select>
                            </div>
                            <!-- col-4 -->

                            <div class="col-lg-2 mg-t-10" id="type">
                                <p class="mg-b-10 parent-label">&nbsp;</p>
                                <select class="form-control parent-input" name="payment_type" id="payment_type"
                                    required>
                                    <option id="Cash" value="Cash"> {{ __('report.cash') }}</option>
                                    <option value="Shabka"> {{ __('report.shabka') }} </option>
                                    <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                                    <option value="Credit"> {{ __('report.credit') }} </option>
                                    <option value="Partition"> {{ __('home.Partition of the amount') }} </option>
                                </select>
                            </div>

                            <input type="text" class="form-control parent-input" name="cashamount_form"
                                id="cashamount_form" hidden>
                            <input type="text" class="form-control parent-input" name="bankamount_form"
                                id="bankamount_form" hidden>
                            <input type="text" class="form-control parent-input" name="bank_transfer_form"
                                id="bank_transfer_form" hidden>

                            {{-- P.O اتشال من الواجهة (مش لازمة) — سايبه مخفي بقيمته الافتراضية عشان السيرفر لسه بيقرأه --}}
                            <input type="hidden" id="p_o" name="p_o" value="-">

                            <div class="col-lg-2 mg-t-10">
                                <label for="notes" class="pro-field-label"><i
                                        class="fa fa-sticky-note"></i>{{ __('home.notesClient') }}</label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="notes"
                                    name="notes" title="يرجي ادخال ملاحظات   " onchange="makenoteoninvoice()"
                                    value="- ">
                            </div>

                            <input class="form-control parent-input fc-datepicker" hidden value="0" name="date"
                                id="date" placeholder="YYYY-MM-DD" type="text" required>

                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                            <input type="text" hidden=true class="form-control" id="invoice_number"
                                name="invoice_number" value="{{ $data['invoice_id'] ?? '' }}">

                            <input type="text" hidden=true class="form-control" id="saveinvice" name="saveinvice"
                                value=0>

                            <input hidden=true class="form-control" id="branchs_id" name="branchs_id"
                                value="{{Auth()->user()->branchs_id}}">
                            <input hidden=true class="form-control" id="user_id" name="user_id"
                                value="{{Auth()->user()->discount_allow_limit}}">
                            <?php
    $rate_discount = App\Models\system_setting::find(1);
    $rate_system = $rate_discount->discount_on_invoice;
                    ?>
                            <input hidden=true class="form-control" id="rate_system" name="rate_system"
                                value="{{$rate_system}}">
                            <input hidden=true class="form-control" id="shownumberproduct" name="shownumberproduct"
                                value="1">

                        </div>

                        <div class="d-flex justify-content-center invoice-actions-bar">

                            <br>

                            <button type="button" id="saveInvoice" style="background-color: #419BB2"
                                class="btn btn-success p-1">
                                {{ __('home.invoice_save') }}
                                <i class="fas fa-check-circle"></i>
                            </button>

                            &nbsp;

                            <button type="button" id="saveAsDraft"
                                style="background-color: #ff9800; border-color: #ff9800;"
                                class="btn btn-warning p-1 text-white">
                                {{ __('home.pending_invoice') }}
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </div>
                        <br>
                 </form>
             </div>

             <input type="text" class="form-control " name="show_invoice_number" id="show_invoice_number" value=0
                title=" رقم الفاتورة " hidden>

             <center>
                <div class="justify-content-center" id="printdiv">

                    <a id="send_whats_app" target="_blank" title="WhatsApp">
                        <i class="bx bxl-whatsapp"></i>
                    </a>

                    <a id="generate_pdf" class="btn btn-success" target="_blank">
                        <i class="fa-solid fa-download ml-1"></i>
                        {{ __('home.dwonloadpdf') }}
                    </a>

                    <button type="button" id="printReciept" class="btn btn-success">
                        <svg class="svg-icon-buttons" viewBox="0 0 20 20" style="width: 18px;">
                            <path
                                d="M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z">
                            </path>
                        </svg>
                        {{ __('home.print') }}
                    </button>

                    <button type="button" id="reciptprinter" class="btn btn-success">
                        <i class="fa-solid fa-print ml-1"></i>
                        {{ __('home.reciptprinter') }}
                    </button>

                    <button type="button" id="sendzatca" class="btn">
                        <i class="fa-regular fa-paper-plane ml-1"></i>
                        {{ __('home.uploadzatca') }}
                    </button>

                </div>

             </center>

         </div>

     </div>

</div>

        <!-- row closed -->
</div>
<!-- Container closed -->
</div>
</div>
<!--search  -->

<div class="modal fade product-selection"
    style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="massagesave"
    name="massagesave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl"
        style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
        <div class="modal-content">

            <div class="modal-body" style="justify-content: center;">

                <center><img style="width:250px;height:250px;" class="custom_img"
                        src="{{ asset('assets/admin/uploads/done.png') }}">
                </center>

            </div>

        </div>
    </div>
</div>

</div>
<input hidden=true class="form-control" id="phone" name="phone">
<input hidden=true class="form-control" id="discound_allow" name="discound_allow" value="{{ $discound_allow / 100 }}">


<div class="modal fade product-selection" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
             </div>
            <div class="modal-body">

                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                            <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                                {{__('home.searchaboutproduct')}} </label>
                            <input autocomplete="off" dir="ltr" type="text" autofocus class="form-control parent-input"
                                placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct"
                                name="searchaboutproduct" oninput="searchaboutproductfunction()" autofocus>
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.groups') }}</label>
                            <select style="width:100%!important" name="product_group" id="product_group"
                                class="form-control select2">
                                <!--placeholder-->
                                @foreach (App\Models\products_group::get() as $section)
                                <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="ajax_responce_serarchDiv">
                        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%"
                            style="border: 2px solid rgba(0,0,0,.3);">
                            <col style="width:5%">
                            <col style="width:14%">
                            <col style="width:28%">
                            <col style="width:10%">
                            <col style="width:18%">
                            <col style="width:15%">
                            <col style="width:10%">

                            <thead>
                                <tr>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                        {{__('home.product')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                        {{__('home.branch')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                        {{__('home.productlocation')}}
                                    </th>

                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}
                                    </th>
                                    <th style="font-size: 13px" class="border-bottom-0">
                                        {{__('home.purchaseproductwithouttax')}}
                                    </th>
                                    <th style="font-size: 13px" class="border-bottom-0">
                                        {{__('home.sellingproduct without tax')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>
                                   </tr>
                            </thead>

                              <tbody class="">
                                <?php $i = 0;
    $data = 'm'; ?>

                                <?php $i++ ?>

                                <tr>
                                    <td id="tableData" dir=ltr>-</td>
                                    <td id="tableData" data-target="product_name">-</td>
                                    <td id="tableData" data-target="product_name">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData">- </td>
                                </tr>

                            </tbody>
                        </table>
                        <td id="tableData">- </td>
                        </tr>

                        </tbody>
                        </table>
                        <div>

                        </div>

                       </div>
                 </div>

                 <div class="modal-footer">
                    {{-- <button id="added_product" name="added_product" id="added_product"
                            class="btn btn-primary">{{__('home.confirm')}}</button>
                    --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                </div>

             </div>

          </div>
    </div>

</div>


<input type="text" class="form-control " id="avtValue" name="avtValue" value="{{$avtSaleRate}}" hidden>
{{-- End Update ( 24/4/2023 ) --}}


<div class="modal p-3" id="createcustomer">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.addnewcustomer') }} </h6><button aria-label="Close"
                        class="close close-special" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-6 col-md-4 mb-2">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.name') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="name" name="name"
                            title="{{ __('supprocesses.name') }}" required>
                    </div>

                    <div class="col-lg-4 col-md-3 mb-2 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.phone') }}</label>
                        <input style="height:32px;" type="text" class="form-control parent-input" id="phone"
                            name="phone" onkeyup="phoneConvert()" title="{{ __('supprocesses.phone') }}">
                    </div>

                    <div class="col-lg-4 col-md-3 mb-2 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.email') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="email" name="email"
                            title="{{ __('supprocesses.email') }}" value='Example@gmail.com'>
                    </div>
                </div>

                {{-- 2 --}}
                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.timeout_periodـinـdays') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input"
                            id="timeout_periodـinـdays" name="timeout_periodـinـdays"
                            title="{{ __('supprocesses.timeout_periodـinـdays') }}"
                            onkeyup="timeout_periodـinـdaysConvert()" value=30 required>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('home.tax_number') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="TaxـNumber"
                            name="TaxـNumber" value="0" title="{{ __('supprocesses.TaxـNumber') }}">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('home.CRN') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="CRN" name="CRN"
                            onkeyup="TaxـNumberConvert() " value=0 title="{{ __('supprocesses.TaxـNumber') }}">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.credit_limit') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="credit_limit"
                            name="credit_limit" onkeyup="credit_limitConvert()"
                            title="{{ __('supprocesses.credit_limit') }}" value=10000 required>
                    </div>

                    <div class="col-lg-2 col-md-3">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_notes') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="product_notes"
                            name="product_notes" title="{{ __('supprocesses.product_notes') }}" value='-'>
                    </div>

                </div>
                <div class="row mb-3">

                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.city') }}</label>
                        <input type="text" class="form-control parent-input" id="city" name="city"
                            title="{{ __('supprocesses.product_notes') }}" required value='-'>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.region') }}</label>
                        <input type="text" class="form-control parent-input" id="sub_city" name="sub_city"
                            title="{{ __('supprocesses.product_notes') }}" required value='-'>
                    </div>

                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.StreetName') }}</label>
                        <input type="text" class="form-control parent-input" id="StreetName" name="StreetName"
                            title="{{ __('supprocesses.product_notes') }}" required value='-'>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.plot_identification') }}</label>
                        <input type="text" class="form-control parent-input" id="plot_identification"
                            name="plot_identification" title="{{ __('supprocesses.product_notes') }}" required value=0>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.buildnumber') }}</label>
                        <input type="text" class="form-control parent-input" id="buildnumber" name="buildnumber"
                            title="{{ __('supprocesses.product_notes') }}" required value=0>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.postcode') }}</label>
                        <input type="text" class="form-control parent-input" id="postcode" name="postcode"
                            title="{{ __('home.postcode') }}" required value=0>
                    </div>

                </div>
                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                        onclick="createnewcustomerajax()">
                        {{ __('supprocesses.save_data') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none"
                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                            </path>
                        </svg>
                    </button>
                </div>
        </div>

     </div>
</div>
</div>
<input id="convert_preparation_order_to_invoice" name="convert_preparation_order_to_invoice" value="{{$invoiceId}}"
    hidden>

<div class="modal p-3" id="updateinvoicefromsale">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.updateinvoice') }} </h6><button aria-label="Close"
                        class="close close-special" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('home.enterinvoicenumber') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input"
                            id="updateinvoicebyidforsale_update" name="updateinvoicebyidforsale_update"
                            title="{{ __('supprocesses.name') }}" required>
                    </div>

                </div>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                        id="updateinvoicebyidforsaleupdate">
                        {{ __('home.search') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none"
                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                            </path>
                        </svg>
                    </button>
                </div>
        </div>

     </div>
</div>
</div>


<div class="modal p-3" id="updateinvoicebyidmodale">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.updateinvoicebyid') }} </h6><button aria-label="Close"
                        class="close close-special" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('home.enterinvoicenumber') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="updateinvoicebyid"
                            name="name" title="{{ __('supprocesses.name') }}" required>
                    </div>

                </div>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                        id="getinvoiceupdate">
                        {{ __('home.search') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none"
                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                            </path>
                        </svg>
                    </button>
                </div>
        </div>

     </div>
</div>
</div>


<div class="modal fade product-selection" id="operation_product" name="main_product" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="table-responsive" id="ajax_responce_operation_product_Div">
                </div>
            </div>

            <div class="modal-footer">
                {{-- <button id="added_product" name="added_product" id="added_product"
                        class="btn btn-primary">{{__('home.confirm')}}</button>
                --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
            </div>

         </div>
      </div>
</div>

</div>
<input hidden=true class="form-control" id="firstiteminput" name="firstiteminput" value="0">

{{-- End Update ( 24/4/2023 ) --}}
<div class="modal fade product-selection" id="main_product2" name="main_product2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="table-responsive" id="ajax_responce_main_product_Div2">
                </div>
            </div>

            <div class="modal-footer">
                {{-- <button id="added_product" name="added_product" id="added_product"
                        class="btn btn-primary">{{__('home.confirm')}}</button>
                --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
            </div>

         </div>
      </div>
</div>

</div>
<div class="modal" id="paymentmethod_MODALE">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.Partition of the amount') }}</h6>
            </div>
            <div class="modal-body" style="width:100%">
                <div class="d-flex align-items-center justify-content-center" style="width:100%">
                    <label style="font-size:20px" class="control-label parent-label me-1">
                        {{ __('home.total') }} :&nbsp;&nbsp;
                    </label>
                    <label style="font-size:25px;font-weight:bold;color:green" id="totalvalue">0</label>
                    <label style="font-size:20px" class="control-label parent-label ms-1">
                        &nbsp;&nbsp;{{ __('home.SAR') }}
                    </label>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col">
                    <label class="control-label parent-label">{{ __('report.cash') }}</label>
                    <input type="text" class="form-control parent-input" name="cashamount" id="cashamount" readonly
                        value="0">
                </div>

                <div class="col">
                    <label class="control-label parent-label">{{ __('report.shabka') }}</label>
                    <input type="text" class="form-control parent-input" name="bankamount" id="bankamount"
                        onkeyup="calcCash()" value="0">
                </div>

                <div class="col">
                    <label class="control-label parent-label">{{ __('home.bank_transfer') }}</label>
                    <input type="text" class="form-control parent-input" name="bank_transfer" id="bank_transfer"
                        onkeyup="calcCash()" value="0">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
@section('js')

<!-- 1. استدعاء مكتبة jQuery (ضرورية جداً قبل أي ملف آخر) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2. ملفات الإضافات (Plugins) -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

<!-- 3. ملف البوتستراب (يأتي بعد الـ jQuery دائماً) -->
<script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- 4. ملف الـ SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/**
 * تبني صف منتج مبسّط لجدول الفاتورة: باركود/كود، اسم الصنف، سعر، كمية، إجمالي.
 * الحقول المخفية اللي بتتبعت فعليًا لسيرفر save_invoice_sale هي:
 * product_id, product_name, price, quentity, discound (=0), tax_rate, tax
 * (التكلفة الحقيقية بتتجاب من قاعدة البيانات مباشرة في السيرفر، مش من الفورم).
 * @param {number} index - رقم الصف (rowIndex)
 * @param {object} options - بيانات اختيارية للتعبئة المبدئية
 * @returns {string} HTML الخاص بالصف جاهز للإدراج فى الجدول
 */
function createProductRow(index, options = {}) {
    const defaults = {
        product_id: '',
        product_name: '',
        product_code: '',
        available_quantity: 0,
        price: 0,
        quantity: 1,
        tax: 0,
        total: 0,
        readonlyName: true,
    };

    const d = { ...defaults, ...options };

    return `
        <tr data-index="${index}">
            <td>
                <input type="hidden" name="products[${index}][product_id]" class="product-id" value="${d.product_id}">
                <input type="hidden" class="form-control product-available_quentity" value="${d.available_quantity}">
                <input type="hidden" name="products[${index}][discound]" class="product-discound" value="0">
                <input type="hidden" name="products[${index}][tax_rate]" class="product-tax-rate" value="${$('#avtValue').val() || 0.15}">
                <input type="hidden" name="products[${index}][tax]" class="product-tax" value="${Number(d.tax).toFixed(2)}">
            </td>

            <td class="align-middle text-center">${index + 1}</td>

            <td class="text-start">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control product-code" value="${d.product_code}" placeholder="باركود" readonly>
                    <button type="button" class="btn btn-sm btn-info p-1"
                            style="background-color: #FBA10F;font-size:13px;width:40px"
                            onclick="openProductModal(${index})">
                        <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                             fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </button>
                </div>
            </td>

            <td class="text-start">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control product-name" name="products[${index}][product_name]"
                           value="${d.product_name}" placeholder="اختر منتج" readonly>
                    <button type="button" class="btn btn-sm btn-info p-1"
                            style="background-color: #FBA10F;font-size:13px;width:40px"
                            onclick="openProductModal(${index})">
                        <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                             fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </button>
                </div>
            </td>

            <td>
                <input type="text" name="products[${index}][price]" class="form-control product-price"
                       value="${Number(d.price).toFixed(2)}" min="0" oninput="calculateTotals()">
            </td>

            <td>
                <div class="d-flex align-items-center justify-content-center" style="width:100%;">
                    <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
                            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
                            type="button" onclick="minusFunctionIndex(this)">−</button>

                    <input type="text" name="products[${index}][quentity]" style="width: 55px; text-align: center;"
                           class="form-control product-quentity mx-2" oninput="calculateTotals()" value="${d.quantity}">

                    <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
                            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
                            type="button" onclick="plusFunctionIndex(this)">+</button>
                </div>
            </td>

            <td><input type="text" class="form-control product-total" readonly value="${Number(d.total).toFixed(2)}"></td>

            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button>
            </td>
        </tr>
    `;
}

$("#getinvoiceupdate").click(function(e) {
    e.preventDefault(); // تأكد من وضعها في البداية

    // إعادة ضبط الحقول قبل جلب البيانات الجديدة
    $('#Bank_transfer').val(0);
    $('#creaditamount').val(0);
    $('#bankamount').val(0);
    $('#cashamount').val(0);
    $("#paymodal").val("Cash").change();

    var url = "{{ URL::to('updateinvoicebyid') }}" + "/" + $('#updateinvoicebyid').val();

    jQuery.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data)
            if (data.error) {
                // تقسيم الرسالة لعرضها بشكل منسق
                let messages = data.error.split('|');

                Swal.fire({
                    icon: 'error',
                    title: 'تنبيه / Alert',
                    html: `<strong>${messages[0]}</strong><br>${messages[1]}`,
                    confirmButtonText: 'حسناً / OK'
                });
                return;
            }
            if (data == 0) {
                alert("{{ __('home.stocknotAvailable') }}");
            } else {
                // 1. مسح الجدول الحالي قبل إضافة المنتجات الجديدة
                $("#productsTableBody").html("");

                // 2. تعبئة بيانات العميل في Select2
                if (data.customer) {
                    var newOption = new Option(data.customer.name, data.customer.id, true, true);
                    $('#clientnamesearch').append(newOption).trigger('change');
                }

                // 3. إضافة المنتجات للجدول
                data.product.forEach(function(item) {
                    let quantity = parseFloat(item.quantity) || 0;
                    let index = rowIndex++; // 👈 يزيد دايمًا

                    if (quantity > 0) {
                        let unitPrice = parseFloat(item.Unit_Price) || 0;
                        let taxAmount = parseFloat(item.Added_Value) ||
                            0; // الضريبة لكل منتج من الـ JSON

                        let row = createProductRow(index, {
                            product_id: item.id,
                            product_name: item.product_name,
                            product_code: item.Product_Code,
                            price: unitPrice,
                            quantity: quantity,
                            tax: taxAmount,
                            total: (unitPrice * quantity) + taxAmount,
                        });

                        $("#productsTableBody").append(row);

                    }
                });
                rowIndex++
                rowIndex++
                console.log('rowIndex')
                console.log(rowIndex)
                // 4. تحديث عداد الصفوف العالمي (إذا كنت تستخدمه للإضافات اليدوية لاحقاً)

                // 5. تعبئة إجماليات الفاتورة السفلية من الـ JSON مباشرة
                $('#invoicetotal_price_total').val(data.invoicetotal_price);
                $('#invoicetotal_addedvalue_total').val(data.invoicetotal_addedvalue);
                $('#invoicetotal_discount_total').val(data.invoicetotal_discount);

                // 6. استدعاء دالة الحسابات لإعادة التأكيد على الأرقام
                if (typeof calculateTotals === "function") {
                    calculateTotals();
                }

                // إظهار أزرار التحكم بعد التحميل بنجاح
            }
        },
        error: function(xhr) {
            console.error("Error fetching invoice:", xhr.responseText);
            alert("حدث خطأ أثناء جلب بيانات الفاتورة");
        }
    });
});
$(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // يمنع إرسال النموذج
        return false;
    }
});


function replaceproduct(id) {
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    console.log(" {{URL::to('operationproducts')}}/" + branchs_id + "/" + id)
    jQuery.ajax({
        url: " {{URL::to('operationproducts')}}/" + branchs_id + "/" + id,
        type: 'get',
        dataType: 'html',
        cache: false,

        success: function(data) {
            console.log('done')
            $('#operation_product').modal().show();

            $("#ajax_responce_operation_product_Div").html(data);
        },
        error: function() {

        }
    });

}

// cashamount is always the auto-calculated remainder:
// total - (shabka amount + bank transfer amount)
function calcCash() {
    var total = parseFloat(document.getElementById('grandTotal').value) || 0;
    var bank = parseFloat($('#bankamount').val()) || 0;
    var transfer = parseFloat($('#bank_transfer').val()) || 0;

    var cash = total - bank - transfer;

    $('#cashamount').val(cash.toFixed(2));

    // keep hidden form fields in sync so the real submitted values are correct
    $('#cashamount_form').val($('#cashamount').val());
    $('#bankamount_form').val($('#bankamount').val());
    $('#bank_transfer_form').val($('#bank_transfer').val());
}


$('select[name="paymentmethod"]').on('change', function() {
    var selectedText = $(this).find(':selected').text();
    var selectedValue = $(this).val();
    var $paymentType = $('#payment_type');

    // 1. إظهار جميع الخيارات أولاً
    $paymentType.find('option').show().prop('disabled', false);

    // التحقق مما إذا كان النص يحتوي على بنك أو bank (بأي حالة أحرف)
    var isBank = /بنك|bank/i.test(selectedText);

    if (isBank) {
        // إذا اخترت البنك: نعطل الخيارات الأخرى، ونترك الشبكة والتحويل، ونُفعل الحقل ليصبح قابلاً للتعديل
        $paymentType.find('option').not('[value="Shabka"], [value="Bank_transfer"]').hide().prop('disabled',
            true);
        $paymentType.val('Shabka').change();
        $paymentType.prop('disabled', false); // مسموح بالتعديل ويتم إرساله

    } else if (selectedValue == "Credit") {
        // إذا اخترت الآجل
        $paymentType.find('option').not('[value="Credit"]').hide().prop('disabled', true);
        $paymentType.val('Credit').change();
        $paymentType.prop('disabled', false); // نتركه غير معطل كي يتم إرسال قيمته للسيرفر

    } else if (selectedValue == "Partition") {
        // إذا اخترت التقسيط
        $paymentType.find('option').not('[value="Partition"]').hide().prop('disabled', true);
        $paymentType.val('Partition').change();
        $paymentType.prop('disabled', false); // نتركه غير معطل كي يتم إرسال قيمته

        $('#paymentmethod_MODALE').modal().show();
        var value = document.getElementById('grandTotal').value;
        document.getElementById('totalvalue').innerHTML = value * 1;
    } else {
        // الحالة الافتراضية (نقدي)
        $paymentType.find('option').not('[value="Cash"]').hide().prop('disabled', true);
        $paymentType.val('Cash').change();
        $paymentType.prop('disabled', false); // نتركه غير معطل كي يتم إرسال قيمته
    }
});

// تفعيل الحقل وتجهيزه قبل حفظ الفاتورة لضمان إرسال قيمته دائماً
    let rowIndex = 1;

$("#updateinvoicebyidforsaleupdate").click(function(e) {

    event.preventDefault();
    var url = " {{ URL::to('updateinvoicebyidforsaleupdate') }}" + "/" + $('#updateinvoicebyidforsale_update')
        .val();
    console.log(url)
    jQuery.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        cache: false,

        success: function(data) {
                $('#show_invoice_number_update').val($('#updateinvoicebyidforsale_update').val())

            console.log('++++++')
            console.log(data)

            document.getElementById("productsTableBody").innerHTML = "";

            data['product'].forEach(async (product) => {
                quentity = product['quantity']

                let index = rowIndex - 1

                if (quentity > 0) {
                    let table = document.getElementById('productsTableBody');

                    let row = createProductRow(index, {
                        product_id: product['id'],
                        product_name: product['product_name'],
                        product_code: product['Product_Code'],
                        price: product['Unit_Price'],
                        quantity: quentity,
                    });

                    table.insertAdjacentHTML("beforeend", row);

                    index = rowIndex++; // 👈 يزيد دايمًا
                }

                window.currentRow = index;
            });
            try {
                $('#clientnamesearch').append(
                    $('<option>', {
                        value: data['customer']['id'],
                        text: data['customer']['name']
                    })
                );
                $('#clientnamesearch').val(data['customer']['id']).trigger('change');
            } catch (e) {
                console.error(e);
            }
            console.log('n')
            console.log(data['customer']['name'])

            calculateTotals()
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth"
            });

            document.getElementById('printdiv').hidden = true
            document.getElementById('saveInvoice').hidden = false

           },
        error: function(response) {
            alert("{{ __('home.sorryerror') }}")

        }

    })

});

function plusFunctionIndex(btn) {
    let input = btn.previousElementSibling;
    input.value = (parseInt(input.value) || 0) + 1;
    calculateTotals();
}

function minusFunctionIndex(btn) {
    let input = btn.nextElementSibling;
    let val = parseInt(input.value) || 0;
    if (val > 0) input.value = val - 1;
    calculateTotals();
}
$('select[name="product_group"]').on('change', function() {
    var selectclientid = $(this).val();
    var token_search = $("#token_search").val();
    console.log(selectclientid)
    if (selectclientid) {
        $.ajax({
            url: "{{ URL::to('product_sale_group_ajax') }}",
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                "_token": token_search,
                "group_id": selectclientid,
                "currentrow": window.currentRow,

            },
            success: function(products) {
                $("#ajax_responce_serarchDiv").html(products);

            },

            error: function(response) {
                console.log(response)
            }
        });
    } else {
        console.log('AJAX load did not work');
    }
});
let searchText = '';

$('#clientnamesearch').select2({
    placeholder: 'ابحث عن المنتج',
    allowClear: true,
    minimumInputLength: 2,
    ajax: {
        url: "{{ route('clientnamesearch.search') }}",
        dataType: 'json',
        delay: 300,
        data: function(params) {
            searchText = params.term
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            if (!data || data.length === 0) {
                // 👇 الكود اللي عايز يتنفّذ
                console.log('مفيش بيانات راجعة');
                $('#name').val(searchText)

                $('#clientnamesearch').select2('close');

                return {
                    results: []
                };
            } else {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name + ' :-' + item.tax_no
                    }))
                };
            }
        }
    }
});
</script>

<script>
let barcodeEnabled = true;

var barcode = '';
var interval;
let productCache = {};

document.addEventListener('keydown', function(evt) {
    if (!barcodeEnabled) return; // 🛑 يمنع القراءة أثناء انشغال الكود

    if (interval)
        clearInterval(interval);
    if (evt.code == 'Enter') {
        if (barcode)
            if ($('#saveinvice').val() == 1) {
                $('#invoice_number').val('')
                $('#saveinvice').val('0')

                handleBarcode(barcode);

            } else {
                handleBarcode(barcode);

            }
        barcode = '';
        // مهم: نمسح المحتوى الفعلي لحقل الباركود بعد كل مسح، وإلا كل الأرقام
        // اللي بيبعتها قارئ الباركود هتفضل تتراكم فوق بعضها في نفس الحقل
        var quickScanField = document.getElementById('quick_scan');
        if (quickScanField) {
            quickScanField.value = '';
        }
        return;
    }
    if (evt.key != 'Shift')
        barcode += evt.key;
    interval = setInterval(() => barcode = '', 20);
});

// شريط الباركود السريع أعلى الجدول: منع الـ Enter من عمل submit للفورم
// (المسح الفعلي بيتم عن طريق المستمع العام فوق، وده بيمسك أي دخل من قارئ الباركود
// حتى لو مكنش التركيز جوه الحقل نفسه). الزرار "إضافة" بيستخدم executeProductSearch مباشرة.
document.addEventListener('DOMContentLoaded', function() {
    var quickScan = document.getElementById('quick_scan');
    if (quickScan) {
        quickScan.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    }
});

function executeProductSearch() {
    let inputField = document.getElementById('quick_scan');
    let productCode = inputField.value.trim();
    if (productCode === "") {
        return;
    }
    inputField.value = '';
    handleBarcode(productCode);
    inputField.focus();
}

function replaceproductorginal(id) {
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    console.log(" {{URL::to('replaceproducts')}}/" + branchs_id + "/" + id)
    jQuery.ajax({
        url: " {{URL::to('replaceproducts')}}/" + branchs_id + "/" + id,
        type: 'get',
        dataType: 'html',
        cache: false,

        success: function(data) {
            console.log('done')

            $('#main_product2').modal().show();

            $("#ajax_responce_main_product_Div2").html(data);

        },
        error: function() {

        }
    });

}


function replaceproduct(id) {
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    console.log(" {{URL::to('operationproducts')}}/" + branchs_id + "/" + id)
    jQuery.ajax({
        url: " {{URL::to('operationproducts')}}/" + branchs_id + "/" + id,
        type: 'get',
        dataType: 'html',
        cache: false,

        success: function(data) {
            console.log('done')
            $('#operation_product').modal().show();

            $("#ajax_responce_operation_product_Div").html(data);
        },
        error: function() {

        }
    });

}

function hasInternet() {
    return navigator.onLine;
}

$('select[name="numbershowstatus"]').on('change', function() {
    console.log('AJAX load   work 0000');

    var selectCustomer = $(this).val();
    $('#shownumberproduct').val(selectCustomer)
})

function createnewcustomerajax() {

    console.log('+++++++++++++++++++++++++++++++++create customer ++++++++++++++++++++++++++++++++');
    var url = " {{ URL::to('createnewcustomerajax') }}";

    var token_search = $("#token_search").val();
    if ($('#name').val() == '') {
        alert("{{ __('home.enterclienname') }}")
    } else if ($('#buildnumber').val() == '') {
        alert("{{ __('home.buildnumber') }}")
    } else if ($('#plot_identification').val() == '') {
        alert("{{ __('home.plot_identification') }}")
    } else if ($('#postcode').val() == '') {
        alert("{{ __('home.postcode') }}")
    } else if ($('#StreetName').val() == '') {
        alert("{{ __('home.StreetName') }}")
    } else if ($('#city').val() == '') {
        alert("{{ __('home.city') }}")
    } else if ($('#sub_city').val() == '') {
        alert("{{ __('home.sub_city') }}")
    } else if ($('#TaxـNumber').val().length < 1) {
        alert('يجب ان يكون رقم الضريبي مكون من 15 رقم     \n    The tax number must consist of 15 digits')
    } else {

        $('#createcustomer').modal().hide();

        $.ajax({
            url: url,
            type: 'post',
            cache: false,

            data: {
                _token: token_search,
                name: $('#name').val(),
                tax_no: $('#TaxـNumber').val(),
                Balance: 0,
                city: $('#city').val() ?? "client address",
                phone: $('#phone').val(),
                email: $('#email').val(),
                notes: $('#product_notes').val(),
                Limit_credit: $('#credit_limit').val(),
                grace_period_in_days: $('#timeout_periodـinـdays').val(),
                buildnumber: $('#buildnumber').val(),
                plot_identification: $('#plot_identification').val(),
                StreetName: $('#StreetName').val(),
                sub_city: $('#sub_city').val(),
                postcode: $('#postcode').val(),
                CRN: $('#CRN').val(),
            },

            success: function(data) {
                $('#phone').val('');
                $('#TaxـNumber').val('');
                $('#name').val('')
                console.log('seccusss12111');
                console.log(data)
                $('#clientnamesearch').append($('<option >', {
                    value: data['id'],
                    text: data['name'] + data['tax_no']
                }));
                $('#clientnamesearch').val(data['id']).change();

                $('#massagesave').modal().show();
                setTimeout(() => {
                    $('#massagesave').modal('hide');

                }, 500);
            },
            error: function(response) {
                alert("{{ __('home.sorryerror') }}")

            }
        });

           }

}
$("#reciptprinter").click(function(e) {
    var url = " {{ URL::to('reciptprinter') }}";
    var token_search = $("#token_search").val();
    $.ajax({
        url: url,
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            _token: token_search,
            show_invoice_number: $('#show_invoice_number').val(),
        },
        success: function(data) {
            console.log(data)
            const winUrl = URL.createObjectURL(
                new Blob([data], {
                    type: "text/html"
                })
            );
            const win = window.open(
                winUrl,
                "win",
                `width=800,height=400,screenX=200,screenY=200`
            );

        },
        error: function(response) {
            console.log(response)
            alert("{{ __('home.sorryerror') }}")

        }
    });
});
$(document).ready(function() {

    if ($('#convert_preparation_order_to_invoice').val() != 0) {
        $('#Bank_transfer').val(0);
        $('#creaditamount').val(0);
        $('#bankamount').val(0);
        $('#cashamount').val(0);
        $("#paymodal").val("Cash").change();

        var url = "{{ URL::to('get_invoice_peeding') }}" + "/" + $('#convert_preparation_order_to_invoice')
        .val();
        console.log(url)
        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            cache: false,
            success: function(data) {

                console.log('++++++')
                console.log(data)

                document.getElementById("productsTableBody").innerHTML = "";

                data['product'].forEach(async (item) => {
                    let quantity = parseFloat(item.quantity) || 0;
                    let index = rowIndex++; // 👈 يزيد دايمًا

                    if (quantity > 0) {
                        let unitPrice = parseFloat(item.Unit_Price) || 0;
                        let taxAmount = parseFloat(item.Added_Value) ||
                        0; // الضريبة لكل منتج من الـ JSON

                        let row = createProductRow(index, {
                            product_id: item.id,
                            product_name: item.product_name,
                            product_code: item.Product_Code,
                            price: unitPrice,
                            quantity: quantity,
                            tax: taxAmount,
                            total: (unitPrice * quantity) + taxAmount,
                        });

                        $("#productsTableBody").append(row);
                        rowIndex = rowIndex + 1;

                    }
                });

                calculateTotals()

                try {
                    $('#clientnamesearch').append(
                        $('<option>', {
                            value: data['customer']['id'],
                            text: data['customer']['name']
                        })
                    );
                    $('#clientnamesearch').val(data['customer']['id']).trigger('change');
                } catch (e) {
                    console.error(e);
                }
                console.log('n')
                console.log(data['customer']['name'])

                document.getElementById('printdiv').hidden = true
                document.getElementById('saveInvoice').hidden = false

               },
            error: function(xhr) {
                console.error("Error fetching invoice:", xhr.responseText);
                alert("حدث خطأ أثناء جلب بيانات الفاتورة");
            }
        });
    }

    $('#payment_type').prop('disabled', true);
    document.getElementById('printdiv').hidden = true

    var quickScan = document.getElementById('quick_scan');
    if (quickScan) quickScan.focus();

})
$(document).on('click', '#saveInvoice', function() {
    $('#invoice_action').val('save');

    $('#formdata').submit();
});

// زر المسودة
$(document).on('click', '#saveAsDraft', function() {
    $('#invoice_action').val('draft');
    $('#formdata').submit();
});

/**
 * اختصارات لوحة مفاتيح للحفظ السريع أثناء البيع بالباركود (زي الشاشة القديمة):
 * - نقرة Ctrl (اضغط واسيب) = حفظ الفاتورة "كاش"
 * - نقرة Shift (اضغط واسيب) = حفظ الفاتورة "شبكة"
 * الاختصار بيتجاهل نفسه تلقائيًا لو في مودال مفتوح (زي إضافة عميل/منتج) أو
 * التركيز جوه أي حقل كتابة غير حقل الباركود، عشان مايحصلش حفظ فجأة وانت بتكتب.
 * ملحوظة: الفانكشن نفسها بتتأكد إن العربة مش فاضية، وبتفتح نفس نافذة تأكيد
 * الحفظ العادية (Swal) قبل ما ترسل — مفيش حفظ مباشر من غير تأكيد.
 */
function quickSaveShortcutAllowed() {
    if ($('.modal.show, .modal.in').length > 0) return false;
    var active = document.activeElement;
    if (active) {
        var tag = active.tagName;
        if ((tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') && active.id !== 'quick_scan') {
            return false;
        }
    }
    return true;
}

function quickSaveWithPayment(type) {
    if (!quickSaveShortcutAllowed()) return;
    if ($('#productsTableBody tr').length === 0) return; // مفيش أصناف اتضافت لسه

    $('#payment_type').prop('disabled', false).val(type);
    $('#invoice_action').val('save');
    $('#formdata').trigger('submit');
}

document.addEventListener('keyup', function(e) {
    if (e.key === 'Control') {
        quickSaveWithPayment('Cash');
    } else if (e.key === 'Shift') {
        quickSaveWithPayment('Shabka');
    }
});

let isSubmitting = false;

$("#formdata").on('submit', function(e) {
    e.preventDefault();

    // --- 1. التحقق من السعر والكمية لجميع الأصناف ---
    let isValid = true;
    let fieldErrorMessage = "";

    $('#productsTableBody tr').each(function() {
        let row = $(this);
        let price = row.find('.product-price').val();
        let qty = row.find('.product-quentity').val();
        let productName = row.find('.product-name').val();

        // فحص إذا كان السعر أو الكمية (فارغ، أو صفر، أو أقل)
        if (price === "" || parseFloat(price) <= 0 || qty === "" || parseFloat(qty) <= 0) {
            row.find('.product-price, .product-quentity').css('border', '2px solid red');
            isValid = false;
            fieldErrorMessage = "يرجى إدخال السعر والكمية بشكل صحيح للمنتج: " + (productName ||
                "غير محدد");
        } else {
            row.find('.product-price, .product-quentity').css('border', '');
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'بيانات ناقصة',
            text: fieldErrorMessage,
            confirmButtonText: 'تصحيح البيانات'
        });
        return; // إيقاف العملية فوراً
    }

    if ($('#productsTableBody tr').length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه | Attention',
            text: 'عذراً، لا يمكن حفظ فاتورة فارغة. يرجى مسح صنف واحد على الأقل بالباركود.',
            confirmButtonText: 'حسناً | OK'
        });
        return;
    }

    // --- 2. تجهيز بيانات الإرسال (تكملة كودك الأصلي) ---
    var activeBtnValue = $(document.activeElement).val();
    var finalAction = activeBtnValue ? activeBtnValue : $('#invoice_action').val();
    var myData = new FormData(this);
    myData.delete('action');
    myData.append('action', finalAction);

    var clientName = $("#clientnamesearch option:selected").text().trim();
    var form = this;

    let messageText = ($('#invoice_action').val() == 'save') ?
        'هل أنت متأكد من حفظ الفاتورة للعميل؟' :
        'هل أنت متأكد من حفظ المسودة للعميل؟';

    // --- 3. نافذة التأكيد ---
    Swal.fire({
        title: 'تأكيد الحفظ | Confirm Save',
        html: `
                                            <div style="font-weight: bold; font-size: 1.1em; margin-bottom: 10px;">
                                                ${messageText}
                                                <br>
                                                <span style="color: #28a745;">${clientName}</span>
                                            </div>
                                        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'نعم، حفظ | Yes, Save',
        cancelButtonText: 'إلغاء | Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (isSubmitting) return false;
            isSubmitting = true;
            $('#payment_type').prop('disabled', false);

            $('#massagesave').modal('show');
            var url = "{{ URL::to('save_invoice_sale') }}";

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(form), // تأكد من إرسال البيانات المحدثة
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($('#invoice_action').val() == 'save') {
                        // منطق الحفظ النهائي والربط مع زاتكا (كودك الأصلي)
                        $('#show_invoice_number').val(data);
                        let link = "{{ URL::to('generate_pdf') }}/" + data;
                        $('#generate_pdf').attr('href', link);

                        // إعداد رابط واتساب
                        let ph = $('#phone').val();
                        let phone = "966" + ph.substring(1);
                        let message = "يسرنا خدمتكم.. فاتورتكم رقم " + data + " : " + link;
                        $('#send_whats_app').attr('href',
                            `https://web.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(message)}`
                        );

                        if (data >= 1) {
                            $('#printdiv').prop('hidden', false);
                            $('#saveInvoice').prop('hidden', true);

                            // إرسال لزاتكا
                            document.getElementById('loading-screen').style.display =
                                'block';
                            var urlZatca = "{{ URL::to('sendzatca_fromsale') }}/" + data;

                            $.ajax({
                                url: urlZatca,
                                type: 'GET',
                                success: function(res) {
                                    document.getElementById('loading-screen')
                                        .style.display = 'none';
                                    $('#massagesave').modal('hide');
                                    if (res == 1) {
                                        new Audio('/sounds/done.mp3').play();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'تم الحفظ والإرسال لزاتكا'
                                        });
                                    } else {
                                        alert("خطأ زاتكا: " + res);
                                    }
                                }
                            });
                        }
                        document.getElementById('loading-screen').style.display = 'none';
                        $('#massagesave').modal('hide');

                    } else {
                        // منطق المسودة (الموجود في كودك)
                        $('#massagesave').modal('hide');
                        let pendingLink = "{{ URL::to('showInvoiceRecent__pending') }}/" +
                            data;
                        Swal.fire({
                            title: 'تم حفظ المسودة!',
                            html: `<p>رقم المسودة <b>#${data}</b></p><a href="${pendingLink}" target="_blank" class="btn btn-info">طباعة المسودة</a>`,
                            icon: 'success'
                        });
                    }
                },
                error: function(r) {
                    console.log(r)
                    isSubmitting = false;
                    $('#massagesave').modal('hide');
                    Swal.fire('خطأ', "تعذر الاتصال بالسيرفر", 'error');
                }
            });
        }
    });
});

$(window).on('offline', function() {
    alert("{{ __('home.sorryerror') }}")
});


function reorderRows() {
    document.querySelectorAll('#productsTableBody tr').forEach((tr, i) => {
        tr.querySelector('td:nth-child(2)').innerText = i + 1;
    });
}

function removeRow(btn) {
    btn.closest('tr').remove();
    calculateTotals()
    reorderRows();

}

let rowCounter = 0;

function openProductModal(index) {

    window.currentRow = index;
    $('#SearchProduct').modal().show();
    $('#searchaboutproduct').focus();

}
var modal = document.getElementById('SearchProduct');
modal.addEventListener('shown.bs.modal', function() {
    document.getElementById('searchaboutproduct').focus();
});


function addRow() {
    // جلب القيمة وتحويلها لرقم للتأكد من المقارنة الصحيحة
    let invoiceNumber = parseFloat($('#show_invoice_number').val());

    // التحقق إذا كانت القيمة أكبر من الصفر
    if (invoiceNumber > 0) {
        location.reload(); // إعادة تحميل الصفحة
        return; // التوقف هنا وعدم تنفيذ باقي الكود
    }

    $('#searchaboutproduct').focus();

    $('#SearchProduct').modal().show();
    $('#searchaboutproduct').focus();

}

$("#sendzatca").click(function(e) {
    document.getElementById('loading-screen').style.display = 'block'; // show loading screen

    var url = " {{ URL::to('sendzatca_fromsale') }}" + '/' + $('#show_invoice_number').val();
    console.log(url)
    document.getElementById('sendzatca').hidden = true

    token_search = $('#token_search').val();
    $.ajax({
        url: url,
        type: 'GET',
        cache: false,
        dataType: "html",

        success: function(data) {

            if (data == 1) {
                document.getElementById('loading-screen').style.display =
                    'none'; // Hide loading screen

                var audio = new Audio('/sounds/done.mp3');
                audio.play();
                document.getElementById('sendzatca').hidden = true
                $('#massagesave').modal().show();
                setTimeout(() => {
                    $('#massagesave').modal('hide');

                }, 1000);
            } else {
                $('#massagesave').modal('hide');
                document.getElementById('loading-screen').style.display =
                    'none';
                alert(data)
                $('#massagesave').modal('hide');

                document.getElementById('sendzatca').hidden = false
            }
        },
        error: function(response) {
            console.log(response)

        }
    });

});
$("#printReciept").click(function(e) {
    var url = " {{ URL::to('printInvoice') }}";
    var token_search = $("#token_search").val();
    $.ajax({
        url: url,
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            _token: token_search,
            show_invoice_number: $('#show_invoice_number').val(),
        },
        success: function(data) {
            const winUrl = URL.createObjectURL(
                new Blob([data], {
                    type: "text/html"
                })
            );
            const win = window.open(
                winUrl,
                "win",
                `width=800,height=400,screenX=200,screenY=200`
            );

        },
        error: function(response) {
            console.log(response)
            alert("{{ __('home.sorryerror') }}")

        }
    });
});

/**
 * الحسابات مبسّطة دلوقتي: مفيش خصم ولا ربح ولا تكلفة على الشاشة.
 * السعر المدخل/المسحوب بالباركود هو سعر الوحدة قبل الضريبة.
 * الضريبة بتتحسب تلقائيًا بنسبة ثابتة (avtValue) وتتبعت مخفية للسيرفر
 * عشان تفاصيل زاتكا تفضل سليمة.
 */
function calculateTotals() {
    let total = 0,
        taxTotal = 0,
        grand = 0;

    let avtsale = parseFloat($('#avtValue').val()) || 0.15;

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let price = parseFloat(r.querySelector('.product-price').value) || 0;
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;

        let subtotal = price * qty;
        let tax = subtotal * avtsale;
        let totalRow = subtotal + tax;

        r.querySelector('.product-tax').value = tax.toFixed(2);
        r.querySelector('.product-tax-rate').value = avtsale;
        r.querySelector('.product-discound').value = 0;
        r.querySelector('.product-total').value = totalRow.toFixed(2);

        total += subtotal;
        taxTotal += tax;
        grand += totalRow;
    });

    document.getElementById('totalSum').value = total.toFixed(2);
    document.getElementById('totaldiscound').value = "0.00";
    document.getElementById('totalTax').value = taxTotal.toFixed(2);
    document.getElementById('grandTotal').value = grand.toFixed(2);
}

function searchaboutproductfunction() {
    searchtext = $('#searchaboutproduct').val();
    branchs_id = $('#branchs_id').val();
    var token_search = $("#token_search").val();
    jQuery.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": searchtext,
            "locale": "{{ app()->getLocale() }}", // ✅ صح
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,
        },

        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
            document.getElementById('searchaboutproduct').focus();

        },
    });

}

$('#SearchProduct').on('show.bs.modal', function(event) {
    searchtext = '';
    branchs_id = $('#branchs_id').val();
    var token_search = $("#token_search").val();

    jQuery.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": searchtext,
            "branchs_id": branchs_id,
            "locale": "{{ app()->getLocale() }}", // ✅ صح
            "currentrow": window.currentRow,
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
            document.getElementById('searchaboutproduct').focus();

        },
    });

});
$(document).on('click', '#ajax_pagination_in_search a', function(e) {
    e.preventDefault();
    var search_by_text = $("#searchaboutproduct").val();
    var url = $(this).attr("href");
    var token_search = $("#token_search").val();
    branchs_id = $('#branchs_id').val();

    jQuery.ajax({
        url: url,
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": search_by_text,
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,

        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function() {

        }
    });
});

function checkDuplicateProduct(productId, currentIndex) {

    return false; // غير مكرر
}


/**
 * فك تشفير باركود الميزان (باركود وزن داخلي، صيغة EAN-13 القياسية للتموينات):
 * 13 رقم = بادئة رقمين (20-29) + 5 أرقام كود الصنف (PLU) + 5 أرقام السعر جاهز
 * محسوب من الميزان نفسه (آخر رقمين عشري) + رقم تحقق.
 * لو الباركود مش مطابق للصيغة دي بيرجع null والتعامل بيكمل عادي كباركود منتج ثابت.
 */
function parseScaleBarcode(code) {
    if (!/^\d{13}$/.test(code)) return null;
    var prefix = code.substring(0, 2);
    if (prefix < '20' || prefix > '29') return null;

    var pluRaw = code.substring(2, 7);
    var plu = pluRaw.replace(/^0+/, '') || '0';
    var priceDigits = code.substring(7, 12);
    var price = parseInt(priceDigits, 10) / 100;

    if (isNaN(price)) return null;

    return {
        plu: plu,
        pluRaw: pluRaw,
        price: price
    };
}

/**
 * إضافة منتج للفاتورة عن طريق الباركود (المسار الأساسي للشاشة).
 * - لو المنتج مش موجود بالنظام: يظهر تنبيه واضح ومايضيفش حاجة.
 * - لو نفس المنتج اتمسح تاني وهو أصلاً موجود في الجدول (منتج عادي وليس ميزان):
 *   تزيد الكمية بدل تكرار السطر.
 * - لو الباركود باركود ميزان (13 رقم، بادئة 20-29): بيتفك لكود الصنف (PLU) +
 *   السعر الجاهز المحسوب من الميزان، والبحث عن المنتج بيتم بكود الصنف مش
 *   بالباركود الكامل (عشان الباركود بيتغير كل وزنة)، والسعر بييجي من الباركود
 *   نفسه مش من سعر البيع المسجل للمنتج، وكل وزنة بتتضاف كسطر منفصل (مش بتتجمع
 *   مع سطر سابق) لأن كل وزنة سعرها مستقل.
 */
function handleBarcode(scanned_barcode) {
    var scaleInfo = parseScaleBarcode(scanned_barcode);
    var lookupCode = scaleInfo ? scaleInfo.plu : scanned_barcode;
    var url = "{{ URL::to('getByCodenew') }}/" + lookupCode;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json', // <-- أسرع بكثير ولا تحتاج JSON.parse
        cache: false,
        beforeSend: function() {
            barcodeEnabled = false; // إيقاف القراءة قبل البدء
        },

        complete: function() {
            barcodeEnabled = true; // ✔ إعادة التشغيل بعد الانتهاء
        },
        success: function(data) {
            console.log(productCache)

            if (!data) {
                Swal.fire({
                    icon: 'error',
                    title: 'غير مسجل | Not Registered',
                    html: 'عذرًا المنتج غير مسجل بالنظام، نرجو تسجيله أولاً.<br><small dir="ltr">Sorry, the product is not registered. Please register it first.</small>',
                    confirmButtonText: 'موافق | OK',
                    confirmButtonColor: '#e0293f'
                });
                return;
            }

            let code = data.id;
            let name = data.product_name;
            let productcode = scaleInfo ? (data.Product_Code || scaleInfo.pluRaw) : data.Product_Code;
            let sale_price = scaleInfo ? scaleInfo.price : data.sale_price;

            let table = document.getElementById('productsTableBody');
            // ---------------------------------------------
            // 1) التحقق من تكرار المنتج باستخدام Cache
            //    (باركود الميزان مبيتحققش من التكرار — كل وزنة سطر مستقل بسعرها)
            // ---------------------------------------------
            console.log('jjjjjjjjjjjjjjjjj')
            console.log($('#firstiteminput').val())
            if ($('#firstiteminput').val() == "0") {
                let rows = table.querySelectorAll('tr');

                if (rows.length == 1) {
                    console.log(rows.length)

                    table.removeChild(rows[rows.length - 1]);
                } else {
                    for (i = 1; i <= rows.length; i++) {
                        table.removeChild(rows[i - 1]);
                    }

                }
            }
            console.log('code Add')
            console.log(productCache[code])

            if (!scaleInfo && productCache[code] && document.body.contains(productCache[code])) {
                let qty = productCache[code].querySelector(".product-quentity");
                qty.value = Number(qty.value) + 1;
                calculateTotals();
                return;
            }

            // ---------------------------------------------
            // 2) إضافة صف جديد
            // ---------------------------------------------
            let index = rowIndex++; // 👈 يزيد دايمًا

            let rowHTML = createProductRow(index, {
                product_id: code,
            });

            table.insertAdjacentHTML("beforeend", rowHTML);

            // ---------------------------------------------
            // 3) تعبئة بيانات الصف الجديد
            // ---------------------------------------------
            let newRow = document.querySelector(`#productsTableBody tr[data-index='${index}']`);

            newRow.querySelector('.product-id').value = code;
            newRow.querySelector('.product-name').value = name;
            newRow.querySelector('.product-code').value = productcode;
            newRow.querySelector('.product-price').value = sale_price;
            newRow.querySelector('.product-quentity').value = 1;
            var audio = new Audio('/sounds/done.mp3');
            audio.play();

            // منتج الميزان: كل وزنة سطر مستقل، فمش بيتسجل في الـ Cache عشان
            // ميتحسبش كتكرار للوزنة اللي بعده.
            if (!scaleInfo) {
                productCache[code] = newRow;
            }
            $('#firstiteminput').val("1");

            // تحديث الإجماليات
            calculateTotals();
        },

        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'غير مسجل | Not Registered',
                html: 'عذرًا المنتج غير مسجل بالنظام، نرجو تسجيله أولاً.<br><small dir="ltr">Sorry, the product is not registered. Please register it first.</small>',
                confirmButtonText: 'موافق | OK',
                confirmButtonColor: '#e0293f'
            });
        }
    });

}
function chooseProduct(code, productcode, name, cost, sale_price, location, availablequantity, currentrow) {
    if (checkDuplicateProduct(code, currentrow)) {
        return; // منتج مكرر — توقف!
    } else {
        let index = rowIndex++; // 👈 يزيد دايمًا

        let table = document.getElementById('productsTableBody');

        console.log('index')
        console.log(index)
        index = index - 1;
        let row = createProductRow(index, {
            product_id: code,
            available_quantity: availablequantity,
        });

        table.insertAdjacentHTML("beforeend", row);
        window.currentRow = index;
        let row1 = document.querySelector(`#productsTableBody tr[data-index='${index}']`);

        row1.querySelector('.product-id').value = code;
        row1.querySelector('.product-name').value = name;
        row1.querySelector('.product-available_quentity').value = availablequantity;
        row1.querySelector('.product-code').value = productcode;
        row1.querySelector('.product-price').value = sale_price;
        $('#firstiteminput').val("1");

        calculateTotals()
    }
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: "smooth"
    });

}
</script>

@endsection