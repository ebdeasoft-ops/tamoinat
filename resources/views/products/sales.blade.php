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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<style>
.custom-save-btn {
    background-color: #419BB2 !important;
    color: white !important;
    /* هنا نتحكم في الحجم */
    min-width: 250px;
    padding: 10px 30px !important;

    font-size: 18px !important;
    font-weight: bold !important;
    border-radius: 50px !important;
    /* شكل بيضاوي احترافي */
    border: none !important;
    box-shadow: 0 4px 15px rgba(65, 155, 178, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-save-btn:hover {
    background-color: #357e91 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(65, 155, 178, 0.4);
}

/* لضمان التمركز في الموبايل أيضاً */
@media (max-width: 768px) {
    .custom-save-btn {
        width: 80%;
        /* يأخذ مساحة أكبر في الشاشات الصغيرة */
    }
}

/* التنسيق العام للمدخلات في هذا القسم */
.custom-input,
.select2-container--default .select2-selection--single {
    border: 1px solid #cbd5e1 !important;
    border-radius: 10px !important;
    height: 45px !important;
    padding: 8px 12px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
    background-color: #ffffff !important;
}

/* تأثير عند الضغط أو اختيار الحقل */
.custom-input:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #419BB2 !important;
    box-shadow: 0 0 0 3px rgba(65, 155, 178, 0.1) !important;
    background-color: #fff !important;
}

/* تحسين شكل الـ Label */
.form-label {
    font-size: 14px !important;
    margin-bottom: 8px !important;
    color: #475569 !important;
}

/* تخصيص الـ Select2 ليتناسب مع الحقول الأخرى */
.select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 28px !important;
    color: #1e293b !important;
    font-weight: 600 !important;
}

/* الصناديق الجانبية (المبلغ، الخصم، الضريبة) */
.summary-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.summary-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 2px;
    color: #64748b;
}

.summary-input {
    background: transparent !important;
    border: none !important;
    font-size: 18px !important;
    font-weight: 800 !important;
    color: #1e293b !important;
    padding: 0 !important;
    height: auto !important;
    text-align: center !important;
}

/* تحسين الصندوق الأسود ليظهر بوضوح أكبر */
.total-display-card {
    background: #c8ccd4 !important;
    border: 2px solid #e53e3e !important;
    border-radius: 12px;
    padding: 8px;
    text-align: center;
}

.total-label {
    color: #fc8181 !important;
    font-size: 15px !important;
    font-weight: 800;
    margin-bottom: 0px;
}

.grand-total-input {
    background: transparent !important;
    border: none !important;
    color: #ffffff !important;
    font-size: 30px !important;
    font-weight: 900 !important;
    text-align: center !important;
    text-shadow: 0 0 8px rgba(229, 62, 62, 0.5);
}

/* =========================
   Modern UI + Inner Borders
   ========================= */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    color: white;
    font-size: 24px;
    display: none;
}

#loading-animation {
    border: 4px solid white;
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* الخط العام */
body,
table,
td,
th,
input,
button,
.form-control {
    font-family: "Tajawal", sans-serif;
    font-weight: 600 !important;
    color: #000 !important;
}

/* جدول بحدود داخلية */
table {
    border-collapse: collapse !important;
    width: 100%;
}

table thead th {
    background: #f1f3f9;
    padding: 12px;
    font-weight: 700 !important;
    color: #000 !important;
    text-align: center;
    border: 1px solid #d6d6d6 !important;
    /* حدود داخلية */
}

/* الصفوف */
#productsTableBody tr {
    background: #ffffff;
    transition: background 0.2s ease;
}

#productsTableBody tr:hover {
    background: #f7f9ff;
}

/* حدود داخلية للـ <td> */
#productsTableBody td {
    border: 1px solid #e0e0e0 !important;
    padding: 10px !important;
    vertical-align: middle;
}

/* الحقول */
.form-control {
    border-radius: 6px !important;
    border: 1px solid #c8ccd4 !important;
    transition: .2s;
}

.form-control:focus {
    border-color: #478bff !important;
    box-shadow: 0 0 0 2px rgba(71, 139, 255, 0.25);
}

/* أزرار اختيار المنتج */
.btn-info,
.btn-primary {
    background: linear-gradient(135deg, #478bff, #357bff) !important;
    border: none !important;
    color: #fff !important;
    border-radius: 6px !important;
    font-weight: 700 !important;
}

.btn-info:hover,
.btn-primary:hover {
    background: linear-gradient(135deg, #3f79ff, #296bff) !important;
    transform: translateY(-1px);
}

/* زر الحذف */
.btn-danger {
    background: linear-gradient(135deg, #ff5b5b, #ff3b3b) !important;
    border: none !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #ff4343, #ff2020) !important;
    transform: scale(1.05);
}

/* زر إضافة صف */
#addProductBtn,
.btn-add-product {
    background: linear-gradient(135deg, #1a73e8, #0d5bd8) !important;
    padding: 8px 20px;
    border-radius: 8px !important;
    color: #fff !important;
    font-weight: 700 !important;
    border: none;
}

#addProductBtn:hover {
    transform: translateY(-2px);
}

/* المودال */
.modal-content {
    border-radius: 12px !important;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: #f1f3f9;
    border-bottom: 1px solid #ddd !important;
}

.modal-title {
    color: #000 !important;
    font-weight: 700 !important;
}

.modal-body {
    background: #fff;
}

/* جدول البحث داخل المودال */
#productsTable th,
#productsTable td {
    border: 1px solid #dcdcdc !important;
}

#productsTable tr:hover {
    background: #eef3ff !important;
}

/* تحسين الـ div */
td .d-flex {
    align-items: center;
}

.barcode-scanner-group {
    border: 2px solid #e2e8f0;
    /* لون هادئ للإطار */
    border-radius: 15px !important;
    /* حواف دائرية ناعمة */
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background-color: #fff;
}

/* تحسين شكل الأيقونة */
.barcode-scanner-group .input-group-text {
    background-color: #419BB2 !important;
    /* لون البراند الخاص بك */
    color: white !important;
    padding-left: 20px;
    padding-right: 20px;
    transition: 0.3s;
}

/* حقل الإدخال نفسه */
#quick_scan {
    font-size: 1.1rem !important;
    font-weight: 600;
    color: #2d3748;
    padding: 12px 15px;
}

#quick_scan:focus {
    box-shadow: none;
    /* إلغاء ظل بوتستراب الافتراضي */
}

/* تأثير عند وضع المؤشر داخل الحقل */
.barcode-scanner-group:focus-within {
    border-color: #419BB2;
    transform: translateY(-2px);
    /* حركة بسيطة للأعلى */
    box-shadow: 0 10px 15px -3px rgba(65, 155, 178, 0.2) !important;
}

/* زر المسح (X) */
.barcode-scanner-group .btn-outline-primary {
    border-radius: 0 !important;
    padding-right: 15px;
    color: #a0aec0 !important;
}

.barcode-scanner-group .btn-outline-primary:hover {
    color: #e53e3e !important;
    /* يتحول للأحمر عند المسح */
    background: transparent !important;
}

/* نص المساعدة الصغير */
.quick-scan-container small {
    font-size: 0.85rem;
    color: #718096 !important;
    margin-right: 10px;
}
</style>
@section('title')
{{ __('home.sales') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="main-parent">
    <div style="justify-content: space-between !important" class="breadcrumb-header parent-heading ">
        <div class="my-auto" style="width:100%">
            <div class="d-flex" style="width:100%">



                <div class="d-flex">
                    <h4 class="conte  nt-title mb-0 my-auto">{{ __('home.sales') }}</h4>
                    <span class="text-muted mt-1 tx-13 mr-2 mb-0">
                    </span>
                </div>
                <div class="last-sales" style="width:70%"></div>


                <div class="choose-product">
                    <button style="background-color: #23395D;"
                        class="modal-effect btn btn-sm btn-info p-2 m-1 button-eng" data-effect="effect-scale"
                        data-toggle="modal" href="#createcustomer">
                        {{ __('home.addnewcustomer') }}
                    </button>
                </div>






            </div>

        </div><!-- col-4 -->
    </div><!-- col-4 -->
</div><!-- col-4 -->
</div><!-- col-4 -->








@endsection
@section('content')

<center>
    <div id="loading-screen">
        <div id="loading-animation"></div>
        &nbsp; <p> جارٍ إرسال الفاتورة، يرجى الانتظار <br>Invoice is being sent, please wait</p>
    </div>
</center>
<!-- row -->
<div class="row">

    <div class="col-xl-12">
        <div class="card mg-b-20">


            <div class="card-header pb-0">


                <?php
    $avtSaleRate = App\Models\Avt::find(1);
    $avtSaleRate = $avtSaleRate->AVT;
                                                                                                                                            ?>

                <form enctype="multipart/form-data" method="POST" role="search" name="form-name" id='formdata'
                    autocomplete="off">
                    {{ csrf_field() }}

                    <div class='row g-3 mt-3 align-items-end'
                        style="background-color: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0;">

                        <div class="col-lg-4">
                            <label for="clientnamesearch" class="form-label fw-bold text-dark">
                                <i class="fas fa-user-circle text-primary me-1"></i> {{ __('home.chooseclient') }}
                            </label>
                            <select class="form-control select2 custom-select" name="clientnamesearch"
                                id="clientnamesearch">
                                @foreach (App\Models\customers::get() as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ App::getLocale() == 'ar' ? $customer->name . ' - ' . $customer->tax_no : $customer->comp_name . ' - ' . $customer->tax_no}}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-wallet text-success me-1"></i> {{ __('home.paymentmethod') }}
                            </label>
                            <select class="form-select custom-input shadow-none" name="payment_type" id='payment_type'
                                required>
                                <option id="cash_id" value="Cash"> {{ __('report.cash') }}</option>
                                <option value="Shabka"> {{ __('report.shabka') }} </option>
                                <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                                <option value="Credit"> {{ __('report.credit') }} </option>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label for="p_o" class="form-label fw-bold text-dark">P.O</label>
                            <input autocomplete="off" type="text" class="form-control custom-input" id="p_o" name="p_o"
                                value="-">
                        </div>

                        <div class="col-lg-4">
                            <label for="notes" class="form-label fw-bold text-dark">
                                <i class="fas fa-pen-alt text-muted me-1"></i> {{ __('home.notesClient') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control custom-input" id="notes"
                                name="notes" onchange="makenoteoninvoice()" value="-">
                        </div>

                    </div>

                    <div style="border-radius: 10px" class="card p-3 my-3">





                        <?php $i = 0; ?>


                        <table class="table-responsive table table-bordered">
                            <thead class="table-dark">
                                <col style="width:0.5%">
                                <col style="width:2%">
                                <col style="width:20%">
                                <col style="width:30%">
                                <col style="width:12%">
                                <col style="width:16%">
                                <col style="width:12%">
                                <col style="width:8%">
                                <thead>
                                    <tr>
                                        <th>- </th>

                                        <th> # </th>
                                        <th>{{ __('home.productNo') }} </th>
                                        <th>{{ __('home.product') }}</th>
                                        <th> {{ __('home.productprice') }} </th>
                                        <th>{{ __('home.quantity') }}</th>
                                        <th>{{ __('home.total') }}</th>
                                        <th>{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                            <tbody id="productsTableBody">

                            </tbody>
                        </table>

                        <button type="button" class="addProductBtn" style="background-color: #419BB2 ;width:8%"
                            onclick="addRow()"> {{ __('supprocesses.addproduct') }}</button>
                        <br>

                        <div class="quick-scan-container mb-4 mt-2">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <div class="input-group input-group-lg shadow-sm barcode-scanner-group">
                                        <span class="input-group-text border-0">
                                            <i class="fas fa-barcode fa-lg"></i>
                                        </span>

                                        <input type="text" id="quick_scan" name="quick_scan"
                                            class="form-control border-0" placeholder="{{ __('home.placeholder') }}"
                                            autocomplete="off">

                                        <button class="btn btn-success border-0 px-4" type="button"
                                            id="btn_execute_scan" onclick="executeProductSearch()" title="إضافة المنتج">
                                            <i class="fas fa-plus-circle me-1"></i>
                                            {{ __('home.Add') ?? 'إضافة' }}
                                        </button>


                                    </div>

                                    <small class="mt-2 d-block animate__animated animate__fadeIn text-muted">
                                        <i class="fas fa-info-circle text-primary me-1"></i>
                                        {{ __('home.presss') }} <strong>Enter</strong> {{ __('home.Add') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <input hidden type="text" id="discound_on_invoice" name="discound_on_invoice"
                            oninput='calculateTotalDiscount()' class="form-control">


                        <div class="row mt-3">

                            <div class="row total-section align-items-end g-3 mb-4">
                                <div class="col-md-3">
                                    <div class="summary-box shadow-sm">
                                        <label class="summary-label">{{ __('home.the amount') }}</label>
                                        <input readonly type="text" id="totalSum" name="totalSum"
                                            class="form-control summary-input">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="summary-box shadow-sm border-warning">
                                        <label class="summary-label text-warning">{{ __('home.discount') }}</label>
                                        <input readonly type="text" id="totaldiscound" name="totaldiscound"
                                            class="form-control summary-input text-warning">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="summary-box shadow-sm border-info">
                                        <label class="summary-label text-info">{{ __('home.addedValue') }}</label>
                                        <input readonly type="text" id="totalTax" name="totalTax"
                                            class="form-control summary-input text-info">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="total-display-card shadow">
                                        <label class="total-label">{{ __('home.total') }}</label>
                                        <input type="text" id="grandTotal" name="grandTotal" readonly
                                            class="form-control grand-total-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <button type='submit' id="saveInvoice" name="saveInvoice" tabindex="-1"
                                    class="btn custom-save-btn">
                                    <i class="fas fa-check-double me-2"></i>
                                    {{ __('home.invoice_save') }}
                                </button>
                            </div>
                        </div>
                    </div>



                    <br>






                    <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                        <input type="text" hidden=true class="form-control" id="invoice_number" name="invoice_number"
                            value="{{ $data['invoice_id'] ?? '' }}">

                        <input type="text" hidden=true class="form-control" id="saveinvice" name="saveinvice" value=0>

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
                        <br>
                        <br>

                        <input type="text" class="form-control " name="show_invoice_number" id="show_invoice_number"
                title=" رقم الفاتورة " hidden>



                </form>

            </div>

         
   <div class="row mt-4 mb-5">

<center>



                            <div class=" justify-content-center" id="printdiv">



                                <button type="button"
                                    style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px"
                                    id="printReciept" class="btn btn-success p-1 px-2 fw-bolder">
                                    {{ __('home.print') }}
                                    <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path
                                            d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555">
                                        </path>
                                    </svg>
                                </button>

                                <button style="background-color: grey;" class="modal-effect btn btn-sm btn-info"
                                    id="sendzatca">
                                    {{ __('home.uploadzatca') }}&nbsp;<i class="fa-regular fa-paper-plane"></i>
                                </button>
                                <button style="background-color: grey;" class="modal-effect btn btn-sm btn-info"
                                    id="dwonloadxml">
                                    {{ __('home.zatcapass') }}&nbsp;<i class="fa-solid fa-download"></i> </button>



                            </div>



        </div>
</center>
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



<div class="modal fade product-selection" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="card-body">

                    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                        <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                            {{__('home.searchaboutproduct')}} </label>
                        <input autocomplete="off" type="text" class="form-control parent-input"
                            placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct"
                            name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
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
                                    <th style="font-size: 15px" class="border-bottom-0">
                                        {{__('home.productNo')}}
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

                                    <th style="font-size: 15px" class="border-bottom-0">
                                        {{__('home.quantity')}}
                                    </th>
                                    <th style="font-size: 13px" class="border-bottom-0">
                                        {{__('home.purchaseproductwithouttax')}}
                                    </th>
                                    <th style="font-size: 13px" class="border-bottom-0">
                                        {{__('home.sellingproduct without tax')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}
                                    </th>



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
                            class="btn btn-primary">{{__('home.confirm')}}</button> --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                </div>

            </div>


        </div>
    </div>

</div>

<input hidden=true class="form-control" id="firstiteminput" name="firstiteminput" value="0">
<?php
    $avtSaleRate = App\Models\Avt::find(1);
    $avtSaleRate = $avtSaleRate->AVT;
                                                                                                            ?>
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
                    <!-- <div class="col-lg-3 col-md-3">
                                                                                                            <label for="inputName" class="control-label parent-label"> {{ __('home.current balance') }} </label>
                                                                                                            <input type="text" class="parent-input form-control" id="balance" name="balance"
                                                                                                                title="يرجي ادخال الكمية  " value="{{ $data['customer']->Balance ?? '0' }}"
                                                                                                                >
                                                                                                        </div> -->
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
                            name="TaxـNumber" onkeyup="TaxـNumberConvert()" title="{{ __('supprocesses.TaxـNumber') }}">
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
                            title="{{ __('supprocesses.product_notes') }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.region') }}</label>
                        <input type="text" class="form-control parent-input" id="sub_city" name="sub_city"
                            title="{{ __('supprocesses.product_notes') }}" required>
                    </div>

                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.StreetName') }}</label>
                        <input type="text" class="form-control parent-input" id="StreetName" name="StreetName"
                            title="{{ __('supprocesses.product_notes') }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.plot_identification') }}</label>
                        <input type="text" class="form-control parent-input" id="plot_identification"
                            name="plot_identification" title="{{ __('supprocesses.product_notes') }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.buildnumber') }}</label>
                        <input type="text" class="form-control parent-input" id="buildnumber" name="buildnumber"
                            title="{{ __('supprocesses.product_notes') }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.postcode') }}</label>
                        <input type="text" class="form-control parent-input" id="postcode" name="postcode"
                            title="{{ __('home.postcode') }}" value='' required>
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
                            id="updateinvoicebyidforsale" name="updateinvoicebyidforsale"
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
<div class="modal p-3" id="notregister">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>

                {{ csrf_field() }}
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                        <center>
                            <label style="font-size:22px;color:red;">
                                هذه المنتجات غير مسجل بالنظام <br> These products are not registered in the
                                system.</label>



                        </center>

                    </div>


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



<div class="modal p-3" id="createproduct">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('supprocesses.addproduct') }} </h6><button aria-label="Close"
                        class="close close-special" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <br>
                <label style="font-size:18px; color:red;font-weight:bold;">&nbsp;&nbsp;<input
                        style="font-size:16px; color:yellow;" type="checkbox" value=0
                        id="translate_status">&nbsp;&nbsp;{{__('home.active_translate')}}</label>
                <br>


                <div class="row mb-2">
                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_name_ar') }}</label>
                        <input autocomplete=off type="text" class="form-control parent-input" id="product_name_ar"
                            name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}"
                            onkeyup="translateNameToEnglish()" required>
                    </div>


                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_name_en') }}</label>
                        <input autocomplete=off type="text" class="form-control parent-input" id="product_name_en"
                            name="product_name_en" title="{{ __('supprocesses.product_name_en') }}"
                            onkeyup="translateNameToArbic()" required>
                    </div>


                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_code') }}</label>
                        <input type="text" class="form-control parent-input" id="product_code_create"
                            name="product_code_create" type="text" dir="ltr" onkeyup="convertToNumber()"
                            title="{{ __('supprocesses.product_code') }}">
                    </div>

                </div>

                {{-- 2 --}}
                <div class="row mb-2">
                    <div class="col-lg-3 mb-2">
                        <label for="inputName"
                            class="control-label parent-label">{{ __('supprocesses.product_branch') }}</label>
                        <select name="Section" id="Section" class="form-control parent-input"
                            onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                            <!--placeholder-->
                            <option value="{{ Auth()->user()->branch->id }}"> {{ Auth()->user()->branch->name }}
                            </option>

                            @foreach (App\Models\branchs::get() as $section)
                            @if(Auth()->user()->branch->id != $section->id)
                            <option value="{{ $section->id }}"> {{ $section->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 mb-2">
                        <label for="inputName" class="control-label parent-label">{{ __('home.MAINproduct') }}</label>
                        <br>
                        <select style="width:100%!important" name="MAINproduct" class="form-control select2">
                            <!--placeholder-->
                            <option value=0> {{ __('home.noreplace') }}</option>


                        </select>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.refnumber') }}</label>
                        <input type="text" class="form-control parent-input" id="refnumber" name="refnumber">
                    </div>
                    <select hidden name="unit" id="unit" class="form-control parent-input">
                        <!--placeholder-->
                        <div class="row">

                            <option value="piece"> {{ __('home.unitـpiece') }}</option>
                            <option value="box">{{ __('home.unit_box') }}</option>
                    </select>




                </div>


                {{-- 3 --}}

                <div class="row">
                    <div class="col-lg-4">
                        <label for="inputName" class="control-label parent-label"> {{ __('home.purachesepice') }}
                        </label>
                        <input autocomplete="off" type="text" class="form-control parent-input" id="cost_price"
                            name="cost_price" value=0 onkeyup="convertToNumberpurchasersPrice()">
                    </div>
                    <div class="col-lg-4">
                        <label for="inputName" class="control-label parent-label" required>{{ __('home.salepice') }}
                        </label>
                        <input autocomplete="off" type="text" class="form-control parent-input" id="sale_price_create"
                            value=0 name="sale_price_create" onkeyup="convertToNumbersalePrice()">
                    </div>
                    <div class="col-lg-4">
                        <label for="inputName" class="control-label parent-label" required>{{ __('home.quantity') }}
                        </label>
                        <input autocomplete="off" type="text" class="form-control parent-input" id="quantity_create"
                            value=0 name="quantity_create" onkeyup="convertToNumbersalePrice()">
                    </div>

                </div>



                {{-- 5 --}}
                <div class="row mb-2">
                    <div class="col-lg-4 mb-2" style="direction: ltr !important;">

                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_location') }}</label>
                        <input dir="ltr" style="direction:LTR !important ;text-align:start!important;" type="text"
                            class="form-control parent-input" id="product_location_create"
                            name="product_location_create" value='-' title="{{ __('supprocesses.product_location') }}"
                            required>
                    </div>









                    {{-- 3 --}}





                    {{-- 5 --}}


                    <div class="col-lg-4 mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.minmum_quantity_stock_alart') }}</label>
                        <input type="text" class="form-control parent-input" id="minmum_quantity_stock_alart"
                            name="minmum_quantity_stock_alart" onkeyup="minmum_quantity_stock_alartConvert()"
                            title="{{ __('supprocesses.minmum_quantity_stock_alart') }}" value=2 required>
                    </div>







                    <div class="col-lg-4 mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_notes') }}</label>
                        <input type="text" class="form-control parent-input" id="product_notes" name="product_notes"
                            title="{{ __('supprocesses.product_notes') }}">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <input type="text" class="form-control parent-input" id="product_name_en" name="product_name_en"
                            title=" {{ __('supprocesses.product_name_en') }}" hidden>
                    </div>

                </div><br>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                        onclick="createnewproductajax()">
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





@endsection
@section('js')
<!-- Internal Data tables -->

<!-- Internal Data tables -->
<!-- Internal Data tables -->
<!--Internal  Datatable js -->
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    
    
document.getElementById('quick_scan').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        // هذا السطر هو السر: يمنع إرسال الفورم نهائياً
        e.preventDefault();

    }
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
let rowIndex = 0;

$("#dwonloadxml").click(function(e) {
    var url = " {{ URL::to('dwonloadxml') }}" + '/' + $('#show_invoice_number').val();
    console.log(url)

    window.open(url, '_blank');

})

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
                document.getElementById('dwonloadxml').hidden = false
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

let barcodeEnabled = true;



var barcode = '';
var interval;
let productCache = {};

document.addEventListener('keydown', function(evt) {
    if (!barcodeEnabled) return; // 🛑 يمنع القراءة أثناء انشغال الكود

    if (interval)
        clearInterval(interval);

    if (evt.code == 'Enter') {
        if (barcode) {
            
            // 🎯 الفلتر الذكي لمنع قراءة الـ QR Code
            // إذا كان النص المقروء طويل جداً (أكبر من 20 حرف) أو يحتوي على روابط، يتم تجاهله فوراً
            if (barcode.length > 20 || barcode.includes('http://') || barcode.includes('https://')) {
                console.log("تم تجاهل المدخلات (غالباً QR Code):", barcode);
                barcode = ''; // تصفير المتغير
                return; // الخروج من الدالة دون تنفيذ أي شيء
            }

            if ($('#saveinvice').val() == 1) {
                $('#invoice_number').val('');
                $('#saveinvice').val('0');
                handleBarcode(barcode);
            } else {
                handleBarcode(barcode);
            }
        }

        $("#quick_scan").blur();
        barcode = '';
        return;
    }

    if (evt.key != 'Shift')
        barcode += evt.key;

    interval = setInterval(() => barcode = '', 20);
});




function executeProductSearch() {
    let inputField = document.getElementById('quick_scan');
    let productCode = inputField.value.trim();

    if (productCode === "") {
        return;
    }
    document.getElementById('quick_scan').value = ''
    handleBarcode(productCode);
    this.blur()


}
// 1. تعريف الملفات الصوتية مرة واحدة خارج الدالة (في أعلى ملف الـ JS) لضمان خفة وسرعة الأداء
const soundDone = new Audio('/sounds/done.mp3');
const soundNotRegister = new Audio('/sounds/notregister.mp3');
const soundIncrease = new Audio('/sounds/increasequentity.mp3');

function handleBarcode(scanned_barcode) {
    // 🎯 فلتر حماية: إذا كان النص المقروء طويل جداً (QR Code) أو يحتوي على روابط، يتم إيقافه فوراً ومسحه
    if (scanned_barcode.length > 20 || scanned_barcode.includes('http://') || scanned_barcode.includes('https://')) {
        console.log("تم حجب المدخلات في الدالة (غالباً QR Code):", scanned_barcode);
        return; // الخروج الفوري ومنع الاتصال بالسيرفر
    }

    var url = "{{ URL::to('getByCodenew') }}/" + scanned_barcode;

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
                // تشغيل الصوت السريع المحمل مسبقاً
                soundNotRegister.currentTime = 0;
                soundNotRegister.play();

                $('#notregister').modal().show();
                setTimeout(() => {
                    $('#notregister').modal('hide');
                }, 1500);

                return;
            }

            let code = data.id;
            let name = data.name;
            let productcode = data.barcode;
            let sale_price = data.price;
            let price_with_tax = data.price_with_tax;

            let table = document.getElementById('productsTableBody');

            // ---------------------------------------------
            // 1) التحقق من تكرار المنتج باستخدام Cache
            // ---------------------------------------------
            console.log('jjjjjjjjjjjjjjjjj')
            console.log($('#firstiteminput').val())
            if ($('#firstiteminput').val() == "0") {
                document.getElementById('saveInvoice').hidden = false
                document.getElementById('sendzatca').hidden = true
                document.getElementById('printdiv').hidden = true
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
            
            if (code != 59264 && code != 59265 && code != 59266 && code != 59570) {
                if (productCache[code] && document.body.contains(productCache[code])) {
                    let qty = productCache[code].querySelector(".product-quentity");
                    qty.value = Number(qty.value) + 1;
                    calculateTotals();

                    // تشغيل الصوت السريع المحمل مسبقاً لزيادة الكمية
                    soundIncrease.currentTime = 0;
                    soundIncrease.play();
                    return;
                }
            }

            // ---------------------------------------------
            // 3) إضافة صف جديد (نسخة محسّنة وسريعة)
            // ---------------------------------------------
            let index = rowIndex++; // 👈 يزيد دايمًا

            let rowHTML = `
                <tr data-index="${index}">
                    <td>
                        <input type="hidden" name="products[${index}][product_id]" class="product-id">
                    </td>

                    <td class="align-middle text-center">${index + 1}</td>

                    <td><input type="text" class="form-control product-code" readonly></td>
                    <td><input type="text" class="form-control product-name" readonly></td>
                    <td><input type="text" name="products[${index}][price]" class="form-control product-price" oninput="calculateTotals()" value="0"></td>
                    <td>  
                        <div class="input-group input-group-sm" style="width:100%;">
                            <button class="btn btn-secondary rounded-circle" style="width:32px; height:32px;" type="button" onclick="minusFunctionIndex(this)">−</button>
                            &nbsp;
                            <input type="number" 
                                   name="products[${index}][quentity]" 
                                   step="any" 
                                   min="0.01" 
                                   class="form-control product-quentity" 
                                   style="width:70px; text-align:center;" 
                                   oninput="if(this.value < 0) this.value = Math.abs(this.value); if(this.value == 0) this.value = ''; calculateTotals();" 
                                   value="1">
                            &nbsp;
                            <button class="btn btn-secondary rounded-circle" style="width:32px; height:32px;" type="button" onclick="plusFunctionIndex(this)">+</button>
                        </div>
                    </td>
                    <td><input type="text" class="form-control product-total" readonly value="0"></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">حذف</button></td>
                </tr>
            `;

            table.insertAdjacentHTML("beforeend", rowHTML);

            // ---------------------------------------------
            // 4) تعبئة بيانات الصف الجديد
            // ---------------------------------------------
            let newRow = document.querySelector(`#productsTableBody tr[data-index='${index}']`);

            newRow.querySelector('.product-id').value = code;
            newRow.querySelector('.product-name').value = name;
            newRow.querySelector('.product-code').value = productcode;
            newRow.querySelector('.product-price').value = price_with_tax;
            
            // تشغيل الصوت السريع المحمل مسبقاً لإضافة منتج جديد بنجاح
            soundDone.currentTime = 0;
            soundDone.play();

            // حفظه في Cache
            productCache[code] = newRow;
            $('#firstiteminput').val("1");

            // تحديث الإجماليات
            calculateTotals();
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth"
            });
        },

        error: function() {
            // تشغيل الصوت السريع عند حدوث خطأ بالسيرفر
            soundNotRegister.currentTime = 0;
            soundNotRegister.play();
            
            Swal.fire({
                title: "غير مسجل | Not Registered",
                text: "عذراً، المنتج غير مسجل نرجو تسجيله أولاً.\nSorry, the product is not registered. Please register it first.",
                icon: "error",
                confirmButtonText: "موافق | OK",
                confirmButtonColor: "#ff4f1f"
            });
        }
    });
}



document.addEventListener("keydown", function(e) {
    if (e.key === "F8") {
        e.preventDefault(); // يمنع أي وظيفة افتراضية
        window.open(window.location.href, "_blank");
    }
});

document.addEventListener("keydown", function(e) {
    if (e.key === "+") {
        plusFunction();
        calculateTotals()

    } else if (e.key === "-") {
        minusFunction();
        calculateTotals()


    }
});

function plusFunction() {
    console.log("دست +");
    console.log(rowIndex);
    let row = document.querySelector(`#productsTableBody tr[data-index='${rowIndex-1}']`);
    if (!row) return;
    row.querySelector('.product-quentity').value = (row.querySelector('.product-quentity').value * 1) + 1;

}

function minusFunction() {
    console.log("دست -");
    console.log(rowIndex);

    let row = document.querySelector(`#productsTableBody tr[data-index='${rowIndex-1}']`);
    if (!row) return;
    row.querySelector('.product-quentity').value = (row.querySelector('.product-quentity').value * 1) - 1;
}







function createnewproductajax() {
    console.log('+++++++++++++++++++++++++++++++++create customer ++++++++++++++++++++++++++++++++');
    var url = " {{ URL::to('addnewProductajax') }}";
    console.log($('#product_notes').val())
    console.log($('#minmum_quantity_stock_alart').val())
    console.log($('#product_name_ar').val())
    console.log($('#product_code').val())
    console.log($('#Section').val())
    console.log($('#unit').val())
    console.log($('#product_location').val())
    var token_search = $("#token_search").val();
    if ($('#product_name_ar').val() == '') {
        alert("{{ __('supprocesses.product_name_ar') }}")
    } else if ($('#product_location_create').val() == '') {
        alert("{{ __('supprocesses.product_location') }}")
    } else {

        // تصحيح: نقل success و error خارج كائن data
        $.ajax({
            url: url,
            type: 'post',
            cache: false,
            data: {
                _token: token_search,
                product_notes: $('#product_notes').val() ?? '-',
                minmum_quantity_stock_alart: $('#minmum_quantity_stock_alart').val(),
                product_name_ar: $('#product_name_ar').val(),
                product_name_en: $('#product_name_en').val(),
                product_code: $('#product_code_create').val(),
                Section: $('#Section').val(),
                unit: $('#unit').val(),
                product_location: $('#product_location_create').val(),
                refnumber: $('#refnumber').val(),
                product_group: $('#product_group').val(),
                numberofpice: $('#quantity_create').val(),
                cost_price: $('#cost_price').val(),
                sale_price_create: $('#sale_price_create').val(),
            },
            success: function(data) {
                $('#createcustomer').modal('hide');
                $('#quantity_create').val(0)
                $('#cost_price').val(0)
                $('#sale_price_create').val(0)
                $('#product_location').val('');
                $('#product_name_ar').val('');
                $('#product_notes').val('')
                $('#product_code').val('')
                $('#massagesave').modal().show();
                setTimeout(() => {
                    $('#massagesave').modal('hide');
                }, 1000);
            },
            error: function(response) {
                console.log(response);
                alert("{{ __('home.sorryerror') }}");
            }
        });
    }
}

function translateNameToArbic() {
    const checkbox = document.getElementById('translate_status');

    if (checkbox && checkbox.checked) {
        var wordEnglish = $('#product_name_en').val();

        jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" +
                encodeURIComponent(wordEnglish),
            type: 'get',
            cache: false,

            success: function(request_result) {
                if (request_result && request_result[0] && request_result[0][0]) {
                    $('#product_name_ar').val(request_result[0][0][0])
                }
            },
            error: function() {
                // خطأ في الترجمة: نترك الصمت أو نعرض رسالة إن رغبت
            }
        });
    }
}

$('select[name="numbershowstatus"]').on('change', function() {
    console.log('AJAX load   work 0000');

    var selectCustomer = $(this).val();
    $('#shownumberproduct').val(selectCustomer)
})

function translateNameToEnglish() {
    const checkbox = document.getElementById('translate_status');

    if (checkbox && checkbox.checked) {
        var wordarbic = $('#product_name_ar').val();

        jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=ar&tl=en&q=" +
                encodeURIComponent(wordarbic),
            type: 'get',
            cache: false,

            success: function(request_result) {
                if (request_result && request_result[0] && request_result[0][0]) {
                    $('#product_name_en').val(request_result[0][0][0])
                }
            },
            error: function() {

            }
        });
    }
}

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
    } else if ($('#TaxـNumber').val().length != 15) {
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

document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey)) {
        $("#payment_type").val("Cash").change();

        $("#formdata").trigger("submit");
    }


})


document.addEventListener('keydown', (e) => {

    if ((e.key === "Shift")) {
        $("#payment_type").val("Shabka").change();
        $("#formdata").trigger("submit");

    }


})

function resetProductsTable() {
    let table = document.getElementById('productsTableBody');

    let index = 0;

    let row = `
                                                                            <tr data-index="${index}">
                                                                                <td><input type="hidden" name="products[${index}][product_id]" class="product-id form-control"></td>
                                                                                <td class="align-middle text-center">${index + 1}</td>

                                                                                <td class="text-start">
                                                                                    <div class="d-flex gap-2">
                                                                                        <input type="text" class="form-control product-code" placeholder="اختر منتج" readonly>
                                                                                        <button type="button" class="btn btn-sm btn-info p-1"
                                                                                                style="background-color: #FBA10F;font-size:13px;width:40px"
                                                                                                onclick="openProductModal(${index})">   <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                                                                                                            class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                                                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                                                                            <path d="M21 21l-6 -6"></path>
                                                                                                                        </svg></button>
                                                                                    </div>
                                                                                </td>

                                                                                <td class="text-start">
                                                                                    <div class="d-flex gap-2">
                                                                                        <input type="text" class="form-control product-name" placeholder="اختر منتج" readonly>
                                                                                        <button type="button" class="btn btn-sm btn-info p-1"
                                                                                                style="background-color: #FBA10F;font-size:13px;width:40px"
                                                                                                onclick="openProductModal(${index})">   <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                                                                                                            class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                                                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                                                                            <path d="M21 21l-6 -6"></path>
                                                                                                                        </svg></button>
                                                                                    </div>
                                                                                </td>

                                                                                <td><input type="text" name="products[${index}][price]"
                                                                                        class="form-control product-price" value="0" min="0" oninput="calculateTotals()"></td>

                 <td>  <div class="input-group input-group-sm" style="width:100%;">
        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="minusFunctionIndex(this)">−</button>
            &nbsp;

<input type="number" 
       name="products[${index}][quentity]" 
       step="any" 
       min="0.01" 
       class="form-control product-quentity" 
       style="width:70px; text-align:center;" 
       oninput="if(this.value < 0) this.value = Math.abs(this.value); if(this.value == 0) this.value = ''; calculateTotals();" 
       value="1">
       &nbsp;

        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>
    
    </td>

                                                             
                                                                                <td><input type="text" class="form-control product-total" readonly value="0"></td>

                                                                                <td>
                                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button>
                                                                                </td>
                                                                            </tr>`;

    table.insertAdjacentHTML("beforeend", row);
    window.currentRow = index;
    rowIndex = 0;
    document.getElementById('sendzatca').hidden =
        true
    document.getElementById('printdiv').hidden =
        true
    document.getElementById('saveInvoice').hidden =
        false
    document.getElementById('dwonloadxml').hidden =
        true

    $('#firstiteminput').val("0")
    productCache = {};

}

$(document).ready(function() {
    document.getElementById('printdiv').hidden = true
    $(window).keydown(function(event){
    // إذا ضغط المستخدم (أو الماسح) على Enter
    if(event.keyCode == 13) {
      // نتحقق إذا كان التركيز ليس على زر الحفظ
      event.preventDefault();
      return false;
    }
  });
    //update 4-2-2026
        // $(window).keydown(function(event){
        //     // إذا كانت الإشارة القادمة هي "Enter" (التي يرسلها قارئ الـ QR Code)
        //     if(event.keyCode == 13) {
        //         // منع المتصفح من إرسال النموذج (الحفظ)
        //         event.preventDefault();
                
        //         // رسالة للتأكد (اختيارية - تظهر في الكونسول فقط)
        //         console.log("تم منع الحفظ التلقائي بنجاح");
                
        //         return false;
        //     }
        // });
        
        //end update 4-2-2026
    $("#quick_scan").blur();

    let isProcessing = false;

    function unlock() {
        isProcessing = false;
    }
$("#formdata").on('submit', function(e) {
    // 1. منع السلوك الافتراضي للمتصفح فوراً لحصار الحدث
    e.preventDefault();

    // 2. التحقق إذا كان الجدول فارغاً تماماً من المنتجات
// 2. التحقق إذا كان الجدول فارغاً تماماً من المنتجات
if ($('#productsTableBody tr').length === 0) {
    Swal.fire({
        title: "تنبيه | Attention",
        text: "عذراً، لا يمكن حفظ فاتورة فارغة. يرجى إضافة منتجات أولاً.\nSorry, cannot save an empty invoice. Please add products first.",
        icon: "warning", 
        confirmButtonText: "موافق | OK",
        confirmButtonColor: "#ff4f1f" // لون زر التأثير البرتقالي المتناسق مع تصميمك
    });
    return false; // إيقاف التنفيذ نهائياً
}

    // 3. التحقق من قفل الحفظ المتكرر أو الحقل الافتراضي
    if (isProcessing || $('#firstiteminput').val() == 0) {
        return false; 
    }

    // قفل التنفيذ لبدء المعالجة والرفع
    isProcessing = true; 
    $('#massagesave').modal().show();

    var url = " {{ URL::to('save_invoice_sale') }}";
    $.ajax({
        url: url,
        type: 'post',
        cache: false,
        contentType: false,
        processData: false,
        data: new FormData(this),
        success: function(data) {
            $('#show_invoice_number').val(data);

            if (data >= 1) {
                console.log(data);
                setTimeout(() => {
                    $('#massagesave').modal('hide');
                }, 500);

                document.getElementById('loading-screen').style.display = 'block'; // show loading screen

                var urlZatca = " {{ URL::to('sendzatca_fromsale') }}" + '/' + $('#show_invoice_number').val();
                console.log(urlZatca);
                document.getElementById('sendzatca').hidden = true;

                token_search = $('#token_search').val();
                $.ajax({
                    url: urlZatca,
                    type: 'GET',
                    cache: false,
                    dataType: "html",
                    success: function(data) {
                        if (data == 1) {
                            document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen

                            var audio = new Audio('/sounds/done.mp3');
                            audio.play();

                            document.getElementById('printdiv').hidden = false;
                            document.getElementById('sendzatca').hidden = true;
                        } else {
                            document.getElementById('loading-screen').style.display = 'none';
                            alert(data);
                            setTimeout(() => {
                                $('#massagesave').modal('hide');
                            }, 500);
                            document.getElementById('sendzatca').hidden = false;
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        document.getElementById('loading-screen').style.display = 'none';
                    }
                });

            } else {
                alert("{{ __('home.sorryerror') }}");
            }

            $('#firstiteminput').val("0");
            unlock();
        },
        error: function(response) {
            console.log(response);
            isProcessing = false; // إعادة فتح القفل في حال فشل السيرفر لكي يتمكن المستخدم من المحاولة مجدداً
        }
    });
});










    // جعل فتح المودال جاهزاً للبحث عند التشغيل
    $('#SearchProduct').on('show.bs.modal', function(event) {
        branchs_id = $('#branchs_id').val();
        console.log(" {{URL::to('ChooseProductpaginatenewSale_new')}}/" + branchs_id + "/" + window
            .currentRow)
        jQuery.ajax({
            url: " {{URL::to('ChooseProductpaginatenewSale_new')}}/" + branchs_id + "/" + window
                .currentRow
                .currentRow,
            type: 'get',
            dataType: 'html',
            cache: false,

            success: function(data) {
                console.log('done')
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });

}); // نهاية document.ready (تبقي كل ما يتعلق بالتهيئة داخلها)

// ملاحظة: تأكد أن هذا الحدث موجود بعد document.ready وإلا سيتم التقاطه أيضاً
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
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function() {

        }
    });
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
    window.currentRow = rowIndex;

    $('#SearchProduct').modal('show');
}

var modal = document.getElementById('SearchProduct');
if (modal) {
    // ملاحظة: يستخدم Bootstrap أحداث jQuery؛ addEventListener قد لا يتفاعل مع shown.bs.modal دائماً
    modal.addEventListener && modal.addEventListener('shown.bs.modal', function() {
        var el = document.getElementById('searchaboutproduct');
        el && el.focus();
    });
}

function addRow() {
    let table = document.getElementById('productsTableBody');
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
    let index = rowIndex++; // 👈 يزيد دايمًا
    window.currentRow = index
    let rowHTML = `
                            <tr data-index="${index}">
                                <td>
                                    <input type="hidden" name="products[${index}][product_id]" class="product-id">
                                </td>

                                <td class="align-middle text-center">${index + 1}</td>

                                <td><input type="text" class="form-control product-code" readonly></td>
                                <td><input type="text" class="form-control product-name" readonly></td>

                                <td><input type="text" name="products[${index}][price]" class="form-control product-price" oninput="calculateTotals()" value="0"></td>

  <td>  <div class="input-group input-group-sm" style="width:100%;">
        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="minusFunctionIndex(this)">−</button>
&nbsp;

<input type="number" 
       name="products[${index}][quentity]" 
       step="any" 
       min="0.01" 
       class="form-control product-quentity" 
       style="width:70px; text-align:center;" 
       oninput="if(this.value < 0) this.value = Math.abs(this.value); if(this.value == 0) this.value = ''; calculateTotals();" 
       value="1">
       &nbsp;
        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;"type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>
    
    </td>

                                <td><input type="text" class="form-control product-total" readonly value="0"></td>

                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">حذف</button></td>
                            </tr>
                        `;

    table.insertAdjacentHTML("beforeend", rowHTML);
    $('#firstiteminput').val('1')
    $('#searchaboutproduct').focus();

    $('#SearchProduct').modal().show();
    $('#searchaboutproduct').focus();
}

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

function calculateTotalDiscount() {
    avtsale = $('#avtValue').val();
    totalbefore_discount = document.getElementById('totalSum').value;
    let discountTotal = 0;

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let discound = 0;
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;
        let price = parseFloat(r.querySelector('.product-price').value) || 0;

        let totalRow = price * qty;
        r.querySelector('.product-total').value = totalRow.toFixed(2);
        net_row_withoud_tax = ((totalRow * 100) / ((avtsale * 100) + 100)).toFixed(2);
        taxTotal += (net_row_withoud_tax * avtsale).toFixed(2);
        grand += (totalRow);
        r.querySelector('.product-total').value = totalRow.toFixed(2);

    });
    discountTotal += $('#discound_on_invoice').val() * 1;

    document.getElementById('totaldiscound').value = discountTotal.toFixed(2);
    document.getElementById('totalTax').value = taxTotal.toFixed(2);
    document.getElementById('grandTotal').value = grand.toFixed(2);
}

function calculateTotals() {

    let discountTotal = 0;
    let grand = 0;
    avtsale = $('#avtValue').val();

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let discound = 0;
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;
        let price = parseFloat(r.querySelector('.product-price').value) || 0;

        let totalRow = price * qty;
        r.querySelector('.product-total').value = totalRow.toFixed(2);

        grand += (totalRow);
        r.querySelector('.product-total').value = totalRow.toFixed(2);

    });

    net_row_withoud_tax = ((grand * 100) / ((avtsale * 100) + 100)).toFixed(4);
    taxTotal = (net_row_withoud_tax * avtsale).toFixed(2);
    document.getElementById('totalSum').value = (net_row_withoud_tax * 1).toFixed(2);
    document.getElementById('totaldiscound').value = discountTotal.toFixed(2);
    document.getElementById('totalTax').value = (taxTotal * 1).toFixed(2);
    document.getElementById('grandTotal').value = (grand * 1).toFixed(2);
}

function searchaboutproductfunction() {
    searchtext = $('#searchaboutproduct').val();
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    jQuery.ajax({
        url: " {{URL::to('searchChooseProductpaginatenewSale_new')}}/" + searchtext + "/" + branchs_id + "/" +
            window.currentRow,
        type: 'get',
        dataType: 'html',
        cache: false,

        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function() {

        }
    });
}

function chooseProduct(code, name, productcode, cost, sale_price, location, availablequantity, currentrow) {
    console.log('window.location');
    console.log(location);
    console.log('availablequantity');
    console.log(availablequantity);
    console.log('currentrow');
    console.log(currentrow);


    let row = document.querySelector(`#productsTableBody tr[data-index='${currentrow}']`);
    if (!row) return;
    row.querySelector('.product-id').value = code;
    row.querySelector('.product-name').value = productcode;
    row.querySelector('.product-code').value = name;
    row.querySelector('.product-price').value = sale_price;
    $('#firstiteminput').val("1")

    calculateTotals()
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: "smooth"
    });
}
</script>

@endsection