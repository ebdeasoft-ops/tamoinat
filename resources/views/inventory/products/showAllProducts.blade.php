@extends('layouts.master')
@section('css')
<style>
    .qe-scan { display:flex; gap:16px; align-items:center; background:linear-gradient(135deg,#16213e 0%,#23395d 100%); border-radius:14px; padding:16px 20px; margin-bottom:14px; }
    .qe-scan-icon { width:52px; height:52px; border-radius:12px; background:rgba(255,255,255,.1); color:#fff; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
    .qe-scan-body { flex:1; min-width:0; }
    .qe-scan-body label { color:#e2e8f0; font-weight:700; font-size:13px; margin-bottom:6px; display:block; }
    .qe-scan-row { display:flex; gap:8px; }
    .qe-scan-row input { flex:1; height:46px; border-radius:10px; border:2px solid #3b82f6; padding:0 14px; font-size:17px; outline:none; background:#fff; }
    .qe-scan-row input:focus { box-shadow:0 0 0 4px rgba(96,165,250,.3); }
    .qe-btn-primary { background:#2563eb !important; color:#fff !important; border:0; border-radius:10px; font-weight:700; padding:0 18px; }
    .qe-scan .qe-btn-primary { height:46px; }
    .qe-btn-primary:hover { background:#1d4ed8 !important; }
    .qe-msg { font-size:12.5px; font-weight:700; margin-top:6px; min-height:0; }
    .qe-msg.err { color:#fecaca; } .qe-msg.ok { color:#bbf7d0; }
    .qe-modal .qe-msg.err { color:#b91c1c; } .qe-modal .qe-msg.ok { color:#15803d; }
    .qe-result { display:flex; align-items:center; gap:18px; flex-wrap:wrap; border:1px solid #dbeafe; background:#f8fbff; border-radius:12px; padding:14px 18px; margin-bottom:18px; }
    .qe-result-main { flex:1; min-width:220px; }
    .qe-result-name { font-size:17px; font-weight:800; color:#0f172a; }
    .qe-result-meta { display:flex; flex-wrap:wrap; gap:14px; font-size:12.5px; color:#64748b; margin-top:4px; }
    .qe-result-meta b { color:#334155; }
    .qe-result-prices { display:flex; gap:10px; }
    .qe-result-prices div { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:6px 14px; text-align:center; min-width:96px; }
    .qe-result-prices small { display:block; color:#64748b; font-size:11px; }
    .qe-result-prices b { font-size:16px; color:#0f172a; }
    .qe-btn-edit { background:#059669 !important; color:#fff !important; border:0; border-radius:10px; font-weight:800; height:44px; padding:0 20px; }
    .qe-btn-edit:hover { background:#047857 !important; }
    .qe-btn-edit small { opacity:.8; font-weight:500; }
    .qe-modal .modal-header { background:#16213e; color:#fff; border-radius:.3rem .3rem 0 0; }
    .qe-modal .modal-header .modal-title, .qe-modal .modal-header .close { color:#fff; }
    .qe-f { margin-bottom:12px; }
    .qe-f label { font-size:12.5px; font-weight:700; color:#334155; margin-bottom:4px; }
    .qe-f .form-control { height:40px; border-radius:8px; }
    .qe-info { display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px; background:#f8fafc; border-radius:8px; padding:8px 12px; font-size:13px; color:#475569; }
    .qe-all { display:flex; align-items:center; gap:8px; margin:12px 0 0; font-size:13px; font-weight:600; color:#334155; cursor:pointer; }
    .qe-all input { width:16px; height:16px; }
    @media (max-width:767px) { .qe-scan { flex-direction:column; align-items:stretch; } .qe-scan-icon { display:none; } }
</style>
<!-- Internal Data table css -->
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

@section('title')
{{ __('home.stock') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->

    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">&nbsp;&nbsp;{{ __('home.stock') }}</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>

    <!-- breadcrumb -->
    @endsection
    @section('content')

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <input type="hidden" id="token_search" value="{{ csrf_token() }}">

<div class="modal fade" id="qe_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content qe-modal">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fa fa-edit"></i> {{ app()->getLocale() == 'ar' ? 'تعديل المنتج' : 'Edit product' }} <span id="qe_m_title"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="qe_form" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" id="qe_id">
                    <div class="row">
                        <div class="col-md-6 qe-f"><label for="qe_product_name">{{ app()->getLocale() == 'ar' ? 'اسم المنتج' : 'Product name' }} *</label><input type="text" class="form-control" id="qe_product_name" name="product_name" required></div>
                        <div class="col-md-6 qe-f"><label for="qe_name_en">{{ app()->getLocale() == 'ar' ? 'الاسم بالإنجليزي' : 'English name' }}</label><input type="text" class="form-control" id="qe_name_en" name="name_en" dir="ltr"></div>
                        <div class="col-md-4 qe-f"><label for="qe_Product_Code">{{ app()->getLocale() == 'ar' ? 'رقم القطعة (الباركود)' : 'Code (barcode)' }} *</label><input type="text" class="form-control" id="qe_Product_Code" name="Product_Code" dir="ltr" required></div>
                        <div class="col-md-4 qe-f"><label for="qe_refnumber">{{ app()->getLocale() == 'ar' ? 'الرقم البديل' : 'Alternative code' }}</label><input type="text" class="form-control" id="qe_refnumber" name="refnumber" dir="ltr"></div>
                        <div class="col-md-4 qe-f"><label for="qe_unit">{{ app()->getLocale() == 'ar' ? 'الوحدة' : 'Unit' }}</label>
                            <select class="form-control" id="qe_unit" name="unit">
                                @foreach (['piece','carton','bag','sack','box','case','dozen','kg','gram','liter','ml','bottle','jar','roll','tray','gallon','meter','unit'] as $u)
                                    <option value="{{ $u }}">{{ __('home.unit_' . ($u == 'unit' ? 'generic' : $u)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 qe-f"><label for="qe_purchasing_price">{{ app()->getLocale() == 'ar' ? 'سعر الشراء' : 'Purchase price' }} *</label><input type="number" class="form-control" id="qe_purchasing_price" name="purchasing_price" step="0.01" min="0" dir="ltr" required></div>
                        <div class="col-md-4 qe-f"><label for="qe_sale_price">{{ app()->getLocale() == 'ar' ? 'سعر البيع' : 'Sale price' }} *</label><input type="number" class="form-control" id="qe_sale_price" name="sale_price" step="0.01" min="0" dir="ltr" required></div>
                        <div class="col-md-4 qe-f"><label for="qe_Wholesale_price">{{ app()->getLocale() == 'ar' ? 'سعر الجملة' : 'Wholesale price' }}</label><input type="number" class="form-control" id="qe_Wholesale_price" name="Wholesale_price" step="0.01" min="0" dir="ltr"></div>
                        <div class="col-md-4 qe-f"><label for="qe_product_group">{{ app()->getLocale() == 'ar' ? 'الفئة' : 'Category' }}</label>
                            <select class="form-control" id="qe_product_group" name="product_group">
                                @foreach (App\Models\products_group::get() as $g)
                                    <option value="{{ $g->id }}">{{ app()->getLocale() == 'ar' ? ($g->group_ar ?: $g->group_en) : ($g->group_en ?: $g->group_ar) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 qe-f"><label for="qe_Product_Location">{{ app()->getLocale() == 'ar' ? 'الموقع في المخزن' : 'Location' }}</label><input type="text" class="form-control" id="qe_Product_Location" name="Product_Location" ></div>
                        <div class="col-md-4 qe-f"><label for="qe_minmum_quantity_stock_alart">{{ app()->getLocale() == 'ar' ? 'تنبيه عند أقل كمية' : 'Low-stock alert' }}</label><input type="number" class="form-control" id="qe_minmum_quantity_stock_alart" name="minmum_quantity_stock_alart" min="0" step="1" dir="ltr"></div>
                        <div class="col-md-12 qe-f"><label for="qe_notes">{{ app()->getLocale() == 'ar' ? 'ملاحظات' : 'Notes' }}</label><input type="text" class="form-control" id="qe_notes" name="notes" ></div>
                    </div>
                    <div class="qe-info">
                        <span><i class="fa fa-cubes"></i> {{ app()->getLocale() == 'ar' ? 'الكمية الحالية' : 'Current qty' }}: <b id="qe_m_qty"></b></span>
                        <small>{{ app()->getLocale() == 'ar' ? 'الكمية بتتعدل من الجرد / تسوية المخزون' : 'Quantity is changed via stock adjustment' }}</small>
                    </div>
                    <label class="qe-all" id="qe_all_wrap"><input type="checkbox" id="qe_all" checked> {{ app()->getLocale() == 'ar' ? 'تطبيق الاسم والأسعار على نفس المنتج في باقي الفروع' : 'Apply name & prices to this product in other branches' }} <b id="qe_m_others"></b></label>
                    <div class="qe-msg" id="qe_m_msg"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn qe-btn-primary" id="qe_save"><i class="fa fa-check"></i> {{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save changes' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">


                    {{ csrf_field() }}



                    <?php $i = 0; ?>
                    <div class="col-xl-12">
                        <div style="border-radius: 10px" class="card mg-b-20">
                            <div class="card-body p-5">

                                {{-- تعديل سريع بالباركود --}}
                                <div class="qe-scan">
                                    <div class="qe-scan-icon"><i class="fa fa-barcode"></i></div>
                                    <div class="qe-scan-body">
                                        <label for="qe_code">{{ app()->getLocale() == 'ar' ? 'امسح باركود المنتج للتعديل السريع' : 'Scan a product barcode to edit it' }}</label>
                                        <div class="qe-scan-row">
                                            <input type="text" id="qe_code" autocomplete="off" dir="ltr" placeholder="{{ app()->getLocale() == 'ar' ? 'امسح الباركود أو اكتب رقم القطعة واضغط Enter...' : 'Scan or type the product code and press Enter...' }}">
                                            <button type="button" id="qe_find" class="btn qe-btn-primary"><i class="fa fa-search"></i> {{ app()->getLocale() == 'ar' ? 'بحث' : 'Find' }}</button>
                                        </div>
                                        <div class="qe-msg" id="qe_msg"></div>
                                    </div>
                                </div>

                                {{-- نتيجة البحث --}}
                                <div class="qe-result" id="qe_result" style="display:none">
                                    <div class="qe-result-main">
                                        <div class="qe-result-name" id="qe_r_name"></div>
                                        <div class="qe-result-meta">
                                            <span><i class="fa fa-barcode"></i> <b id="qe_r_code" dir="ltr"></b></span>
                                            <span><i class="fa fa-store"></i> <b id="qe_r_branch"></b></span>
                                            <span><i class="fa fa-cubes"></i> {{ app()->getLocale() == 'ar' ? 'الكمية' : 'Qty' }}: <b id="qe_r_qty"></b></span>
                                            <span><i class="fa fa-map-marker-alt"></i> <b id="qe_r_loc"></b></span>
                                        </div>
                                    </div>
                                    <div class="qe-result-prices">
                                        <div><small>{{ app()->getLocale() == 'ar' ? 'سعر الشراء' : 'Purchase price' }}</small><b id="qe_r_buy"></b></div>
                                        <div><small>{{ app()->getLocale() == 'ar' ? 'سعر البيع' : 'Sale price' }}</small><b id="qe_r_sell"></b></div>
                                    </div>
                                    @can('Product data change')
                                        <button type="button" class="btn qe-btn-edit" id="qe_edit"><i class="fa fa-edit"></i> {{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }} <small>(Enter)</small></button>
                                    @endcan
                                </div>

                                <div class="main-parent-filter p-2 mb-3"
                                    style="border: 1px dashed #23395D; border-radius: 8px; background-color: #f8f9fa;">
                                    <div class="row align-items-end mx-0">

                                        <div class="col-lg-2 col-md-6 mb-2">
                                            <label for="searchaboutproduct"
                                                style="font-weight: bold; font-size: 12px; color: #23395D;"
                                                class="control-label parent-label mb-1">
                                                {{__('home.searchaboutproduct')}}
                                            </label>
                                            <input dir="ltr" type="text"
                                                class="form-control form-control-sm parent-input"
                                                placeholder="{{ __('home.Search By Name or Product Number') }}"
                                                id="searchaboutproduct" name="searchaboutproduct"
                                                onkeyup="filterProducts()">
                                        </div>

                                        <div class="col-lg-2 col-md-6 mb-2">
                                            <label for="product_group" class="control-label parent-label mb-1"
                                                style="font-size: 12px;">{{ __('home.groups') }}</label>
                                            <select name="product_group" id="product_group"
                                                class="form-control form-control-sm select2">
                                                <option value="">-</option>
                                                @foreach (App\Models\products_group::get() as $section)
                                                <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 mg-t-10 mg-lg-t-0">
                                            <label for="inputName" class="control-label parent-label">
                                                {{ __('home.Location') }} </label>
                                            <input class="form-control parent-input" id="Location" name="Location"
                                                title="يرجي ادخال الكمية  "
                                                onkeyup="searchaboutproduct_location_function()">
                                        </div>
                                        <div class="col-lg-3 col-md-6 mb-2">
                                            <label for="branchs_id" class="parent-label mb-1" style="font-size: 12px;">
                                                {{ __('users.branch') }} </label>
                                            <select class="form-control form-control-sm select2" name="branchs_id"
                                                id="branchs_id">
                                                <option value="-"> - </option>
                                                @foreach (App\Models\branchs::get() as $section)
                                                <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @can('System setting')
                                        <div class="col-lg-3 col-md-6 mb-2">
                                            <label class="parent-label mb-1"
                                                style="opacity:0; display: block;">.</label>
                                            <button id="unified_export_btn"
                                                class="btn btn-sm btn-success-gradient btn-block shadow-sm"
                                                style="height: 31px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                <i class="fa fa-file-excel ml-2"></i> {{ __('export_excel') }}
                                            </button>
                                        </div>
                                        @endcan

                                    </div>
                                </div>


                                <br>
                                <div id="ajax_responce_serarchDiv"
                                    class="table our-table border mb-0 table-responsive text-center">
                                    <table class="table text-md-nowrap text-center our-table" id="example2" width="100%"
                                        style="border: 2px solid rgba(0,0,0,.3);">
                                        <col style="width:5%">
                                        <col style="width:15%">
                                        <col style="width:20%">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:15%">
                                        <col style="width:15%">


                                        <thead>
                                            <tr>
                                                <th style="font-size: 15px" class="border-bottom-0">#</th>
                                                <th style="font-size: 15px" class="border-bottom-0">
                                                    {{__('home.productNo')}}
                                                </th>
                                                <th style="font-size: 15px" class="border-bottom-0"
                                                    style="text-align:center">{{__('home.product')}}
                                                </th>
                                                <th style="font-size: 15px" class="border-bottom-0"
                                                    style="text-align:center">{{__('home.branch')}}
                                                </th>
                                                <th style="font-size: 15px" class="border-bottom-0">
                                                    {{__('home.productlocation')}}
                                                </th>
                                                <th style="font-size: 15px" class="border-bottom-0">
                                                    {{__('home.quantity')}}
                                                </th>
                                                <th style="font-size: 13px" class="border-bottom-0">
                                                    {{__('home.purchaseproductwithouttax')}}
                                                </th>
                                                <th style="font-size: 13px" class="border-bottom-0">
                                                    {{__('home.sellingproduct without tax')}}</th>



                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <?php $i = 0;
                                            ?>

                                            <?php $i++ ?>

                                            <tr>
                                                <td id="tableData" dir=ltr>-</td>
                                                <td id="tableData" dir=ltr>-</td>
                                                <td id="tableData" data-target="product_name">-</td>
                                                <td id="tableData" data-target="product_name">-</td>
                                                <td id="tableData" data-target="numberofpice">-</td>
                                                <td id="tableData" data-target="numberofpice">-</td>
                                                <td id="tableData" data-target="numberofpice">-</td>
                                                <td id="tableData" data-target="numberofpice">-</td>
                                            </tr>

                                        </tbody>
                                    </table>

                                    <div>

                                    </div>



                                </div>

                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
</div>

<div class="modal p-3" id="delete_quotation">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.alert') }} </h6><button aria-label="Close"
                        class="close close-special" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('home.Are_you_sure_delete') }}</label>
                    </div>


                </div>

                <input type="text" hidden class="form-control parent-input" name="delete_id" id="delete_id">

                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('home.cancel') }}</button>
                    <button id="delete_quotation_function" name="delete_quotation_function" data-dismiss="modal"
                        class="btn btn-danger">{{ __('home.confirm') }}</button>
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
                {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button>
                --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
            </div>

        </div>


    </div>
</div>

</div>
@endsection
@section('js')

<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<script>
$(document).ready(function() {

    // 1. دالة موحدة لطلبات AJAX لتقليل التكرار
    function performAjax(url, method, data, successCallback) {
        data["_token"] = $("#token_search").val();
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successCallback,
            error: (err) => console.error("AJAX Error:", err)
        });
    }



    // 2. تحميل البيانات الأولي
    performAjax("{{ URL::to('searchAllproductpaginatenew_by_post') }}", 'post', {
        "searchtext": '',
        "locale" : "{{ app()->getLocale() }}", // ✅ صح
        "branchs_id": "{{ auth()->user()->branchs_id ?? 1 }}"
    }, (data) => $("#ajax_responce_serarchDiv").html(data));

    // 3. الفلترة الموحدة (الفرع، المجموعة، النص)
    $('#branchs_id, select[name="product_group"], #searchaboutproduct').on('change keyup', filterProducts);

    // 4. الترقيم (Pagination)
    $(document).on('click', '#ajax_pagination_in_search a', function(e) {
        e.preventDefault();
        performAjax($(this).attr("href"), 'post', {
            "searchtext": $("#searchaboutproduct").val(),
            "branchs_id": $("#branchs_id").val()
        }, (data) => $("#ajax_responce_serarchDiv").html(data));
    });

    // 5. زر التصدير (Export)
    $('#unified_export_btn').on('click', function(e) {
        e.preventDefault();
        let branchId = $('#branchs_id').val();

        if (!branchId) return alert('الرجاء اختيار فرع أولاً');
        window.location.href = "{{ url('Stocktaking') }}/" + branchId;
    });

    // 6. اختيار منتج (جلب الكمية)
    $('select[name="searchproductNo"]').on('change', function() {
        let id = $(this).val();
        if (id) {
            performAjax("{{ URL::to('getproduct') }}/" + id, 'get', {}, (data) => {
                $('#quentity').val(data.numberofpice);
            });
        }
    });

    // 7. حذف منتج
    $("#delete_quotation_function").click(function() {
        let id = $('#delete_id').val();
        if (id) {
            performAjax("{{ URL::to('delete_product') }}/" + id, 'get', {}, (data) => {
                $("#ajax_responce_serarchDiv").html(data);
                $('#delete_quotation').modal('hide');
            });
        }
    });
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
// دالة الفلترة في النطاق العام لتعمل مع onkeyup مباشرة
function filterProducts() {
    let data = {
        "searchtext": $('#searchaboutproduct').val(),
         "locale" : "{{ app()->getLocale() }}", // ✅ صح
        "branchs_id": $('#branchs_id').val(),
        "group_id": $('select[name="product_group"]').val(),
        "_token": $("#token_search").val()
    };

    $.ajax({
        url: "{{ URL::to('searchAllproductpaginatenew_by_post') }}",
        type: 'post',
        data: data,

        success: function(data) {
            console.log(data);
            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function(err) {
            console.error("AJAX Error:", err);
        }
    });
}
</script>


<script>
(function () {
    var URLS = { lookup: @json(url('/products/quick-lookup')), update: @json(url('/products/quick-update')) };
    var CSRF = $('#token_search').val();
    var current = null;
    var money = function (n) { return (Math.round((Number(n) || 0) * 100) / 100).toFixed(2); };
    var $code = $('#qe_code');
    function msg(el, text, ok) { $(el).text(text || '').removeClass('ok err').addClass(text ? (ok ? 'ok' : 'err') : ''); }
    function focusScan() { setTimeout(function () { $code.trigger('focus').select(); }, 50); }

    function showResult(p) {
        current = p;
        $('#qe_r_name').text(p.product_name);
        $('#qe_r_code').text(p.Product_Code);
        $('#qe_r_branch').text(p.branch || '-');
        $('#qe_r_qty').text(+p.numberofpice);
        $('#qe_r_loc').text(p.Product_Location || '-');
        $('#qe_r_buy').text(money(p.purchasing_price));
        $('#qe_r_sell').text(money(p.sale_price));
        $('#qe_result').show();
    }

    function find() {
        var code = $.trim($code.val());
        if (!code) return;
        msg('#qe_msg', '');
        var branch = $('#branchs_id').val();
        $.getJSON(URLS.lookup, { code: code, branch_id: branch && branch !== '-' ? branch : '' })
            .done(function (r) {
                if (!r.found) { $('#qe_result').hide(); current = null; msg('#qe_msg', r.message, false); focusScan(); return; }
                showResult(r.product);
                if ($('#qe_edit').length) openEdit(); else focusScan();
            })
            .fail(function () { msg('#qe_msg', @json(app()->getLocale() == 'ar' ? 'تعذر البحث، حاول مرة أخرى' : 'Search failed, try again'), false); });
    }

    function openEdit() {
        if (!current) return;
        var p = current;
        $('#qe_id').val(p.id);
        ['product_name','name_en','Product_Code','refnumber','purchasing_price','sale_price','Wholesale_price','Product_Location','minmum_quantity_stock_alart','notes'].forEach(function (f) {
            $('#qe_' + f).val(p[f] == null ? '' : p[f]);
        });
        if (!$('#qe_unit option[value="' + p.unit + '"]').length && p.unit) $('#qe_unit').append(new Option(p.unit, p.unit));
        $('#qe_unit').val(p.unit || 'piece');
        $('#qe_product_group').val(p.product_group);
        $('#qe_m_title').text('— ' + p.product_name);
        $('#qe_m_qty').text(+p.numberofpice);
        $('#qe_all_wrap').toggle(p.other_branches > 0);
        $('#qe_m_others').text(p.other_branches > 0 ? '(' + p.other_branches + ')' : '');
        msg('#qe_m_msg', '');
        $('#qe_modal').modal('show');
        setTimeout(function () { $('#qe_sale_price').trigger('focus').select(); }, 400);
    }

    $code.on('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); find(); } });
    $('#qe_find').on('click', find);
    $('#qe_edit').on('click', openEdit);
    $('#qe_modal').on('hidden.bs.modal', focusScan);

    $('#qe_form').on('submit', function (e) {
        e.preventDefault();
        var id = $('#qe_id').val(), $btn = $('#qe_save').prop('disabled', true);
        var data = $(this).serializeArray();
        data.push({ name: '_token', value: CSRF });
        data.push({ name: 'all_branches', value: $('#qe_all').is(':checked') && $('#qe_all_wrap').is(':visible') ? 1 : 0 });
        $.post(URLS.update + '/' + id, $.param(data))
            .done(function (r) {
                showResult(r.product);
                $('#qe_modal').modal('hide');
                msg('#qe_msg', r.message, true);
                $code.val('');
                if (typeof filterProducts === 'function') filterProducts();
            })
            .fail(function (x) {
                var r = x.responseJSON || {};
                var m = r.message || '';
                if (r.errors) m = Object.values(r.errors).map(function (a) { return a[0]; }).join(' — ');
                msg('#qe_m_msg', m || @json(app()->getLocale() == 'ar' ? 'تعذر الحفظ' : 'Save failed'), false);
            })
            .always(function () { $btn.prop('disabled', false); });
    });

    focusScan();
})();
</script>
@endsection
