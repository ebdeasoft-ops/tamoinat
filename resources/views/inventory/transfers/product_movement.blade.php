@extends('layouts.master')
@section('css')
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

<style>
    .addproduct-section {
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 12px;
        margin-bottom: 18px;
        overflow: hidden;
    }

    .addproduct-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #23395D;
        color: #fff;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 15px;
    }

    .addproduct-section-title .icon-badge {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #419BB2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .addproduct-section-body {
        padding: 18px 16px 6px;
    }

    .addproduct-field label.parent-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #23395D;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .addproduct-field label.parent-label .field-icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: rgba(65, 155, 178, 0.12);
        color: #23395D;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }

    .addproduct-field .form-control,
    .addproduct-field .select2-container .select2-selection--single {
        border-radius: 8px;
        border: 1px solid #dfe3ea;
        min-height: 42px;
    }

    .addproduct-field .select2-container .select2-selection--single {
        display: flex;
        align-items: center;
        padding: 0 8px;
    }

    .addproduct-field .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        padding-inline-start: 4px;
    }

    .addproduct-field .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }

    .addproduct-field .form-control:focus {
        border-color: #419BB2;
        box-shadow: 0 0 0 3px rgba(65, 155, 178, 0.15);
    }

    .addproduct-hint {
        display: block;
        color: #8992a3;
        font-size: 12px;
        margin-top: 4px;
    }

    /* أسعار: زرار "شامل الضريبة" وسطر القراءة التلقائية */
    .price-tax-wrap {
        position: relative;
    }

    .price-tax-wrap .price-tax-input {
        padding-inline-end: 84px;
    }

    .price-tax-switch {
        position: absolute;
        top: 50%;
        inset-inline-end: 8px;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 3px 8px;
        border-radius: 20px;
        background: #eef2f8;
        color: #667085;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        transition: all .15s ease-in-out;
        white-space: nowrap;
    }

    .price-tax-switch input {
        margin: 0 2px 0 0;
        cursor: pointer;
    }

    .price-tax-switch.is-active {
        background: linear-gradient(90deg, #23395D, #419BB2);
        color: #fff;
    }

    .price-tax-readout {
        color: #419BB2 !important;
        font-weight: 600;
    }

    #division_unit_count_row {
        transition: all .15s ease-in-out;
    }

    .addproduct-photo-box {
        border: 1px dashed #c9d1e0;
        border-radius: 10px;
        padding: 10px;
        background: #f8fafc;
    }

    .addproduct-choose-box {
        background: #f8f9fb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
    }

    .addproduct-choose-btn {
        background: linear-gradient(90deg, #FF4F1F, #ff7847) !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600;
    }

    .addproduct-save-btn {
        background: linear-gradient(90deg, #23395D, #419BB2);
        border: none;
        border-radius: 10px;
        padding: 10px 34px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
</style>

@section('title')
    {{__('supprocesses.product_movement')}}@stop
@endsection
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto"><i class="fas fa-dolly-flatbed"></i>
                        {{__('supprocesses.product_movement')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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
            @if (session()->has('productupdatedlocation'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <br>

                    <strong>{{ session()->get('productupdatedlocation') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">



                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <!-- row -->
            <div class="row">

                <div class="col-xl-12">
                    <div class="card mg-b-20">


                        <div class="card-header pb-0">

                            <form
                                action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'product_movement')) }}"
                                method="POST" enctype="multipart/form-data" role="search" autocomplete="off">
                                {{ csrf_field() }}

                                <div class="card-body box-shadow-0">
                                    <div class="addproduct-choose-box">
                                        <div class="row row-sm">
                                            <div class="col-lg-6 addproduct-field">
                                                <label class="parent-label" style="font-size: 1.05rem;">
                                                    <span class="field-icon"><i class="fas fa-store-alt"></i></span>
                                                    {{ __('users.branch') }}
                                                </label>
                                                <select class="form-control select2" style="width:100%" name="branchs_id"
                                                    id="branchs_id">
                                                    <option value="{{ Auth()->user()->branch->id }}">
                                                        {{ Auth()->user()->branch->name }} </option>
                                                    @foreach (App\Models\branchs::get() as $section)
                                                        @if(Auth()->user()->branch->id != $section->id)
                                                            <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6 d-flex align-items-end mt-3 mt-lg-0">
                                                <a style="border: none;"
                                                    class="modal-effect btn text-white btn-block py-2 addproduct-choose-btn"
                                                    data-effect="effect-scale" data-toggle="modal" href="#SearchProduct">
                                                    <i class="las la-search"></i> {{ __('home.chooose product') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- القسم الأول: البيانات الأساسية --}}
                                    <div class="addproduct-section">
                                        <div class="addproduct-section-title">
                                            <span class="icon-badge"><i class="fas fa-barcode"></i></span>
                                            {{ __('home.basic_product_data') }}
                                        </div>
                                        <div class="addproduct-section-body">
                                            <div class="row row-sm mb-3">
                                                <div class="col-lg-4 addproduct-field" id="type">
                                                    <label class="parent-label"> {{ __('home.productNo') }} </label>
                                                    <input type="text" class="form-control" id="productcode" name="productcode"
                                                        dir="ltr">
                                                    <input hidden name="productname" id="productname">
                                                </div>
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label"> {{__('home.productname')}} </label>
                                                    <input type="text" class="form-control" id="productnameshow"
                                                        name="productnameshow" required>
                                                </div>
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label"> {{ __('home.refnumber') }} </label>
                                                    <input type="text" class="form-control" id="refnumber" name="refnumber">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @can('System setting')
                                        {{-- القسم الثاني: الأسعار --}}
                                        <div class="addproduct-section">
                                            <div class="addproduct-section-title">
                                                <span class="icon-badge"><i class="fas fa-money-bill-wave"></i></span>
                                                {{ __('home.prices_and_category') }}
                                            </div>
                                            <div class="addproduct-section-body">
                                                <div class="row row-sm mb-3">
                                                    <div class="col-lg-4 addproduct-field">
                                                        <label class="parent-label"> {{ __('home.purachesepice') }} </label>
                                                        <div class="price-tax-wrap">
                                                            <input type="text" inputmode="decimal"
                                                                class="form-control price-tax-input" id="purachesepice"
                                                                name="purachesepice" data-field="purachesepice" required>
                                                            <label class="price-tax-switch" data-for="purachesepice">
                                                                <input type="checkbox" class="price-tax-toggle"
                                                                    data-field="purachesepice">
                                                                <span>{{ __('home.with_tax') }}</span>
                                                            </label>
                                                        </div>
                                                        <small class="addproduct-hint price-tax-readout"
                                                            id="purachesepice_readout">&nbsp;</small>
                                                    </div>
                                                    <div class="col-lg-4 addproduct-field">
                                                        <label class="parent-label"> {{ __('home.Wholesale_price') }} </label>
                                                        <div class="price-tax-wrap">
                                                            <input type="text" inputmode="decimal"
                                                                class="form-control price-tax-input" id="Wholesale_price"
                                                                name="Wholesale_price" data-field="Wholesale_price" required>
                                                            <label class="price-tax-switch" data-for="Wholesale_price">
                                                                <input type="checkbox" class="price-tax-toggle"
                                                                    data-field="Wholesale_price">
                                                                <span>{{ __('home.with_tax') }}</span>
                                                            </label>
                                                        </div>
                                                        <small class="addproduct-hint price-tax-readout"
                                                            id="Wholesale_price_readout">&nbsp;</small>
                                                    </div>
                                                    <div class="col-lg-4 addproduct-field">
                                                        <label class="parent-label"> {{ __('home.sellingproduct without tax') }}
                                                        </label>
                                                        <div class="price-tax-wrap">
                                                            <input type="text" inputmode="decimal"
                                                                class="form-control price-tax-input" id="product_price"
                                                                name="product_price" data-field="product_price" required>
                                                            <label class="price-tax-switch" data-for="product_price">
                                                                <input type="checkbox" class="price-tax-toggle"
                                                                    data-field="product_price">
                                                                <span>{{ __('home.with_tax') }}</span>
                                                            </label>
                                                        </div>
                                                        <small class="addproduct-hint price-tax-readout"
                                                            id="product_price_readout">&nbsp;</small>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="avtValue" value="{{ $avtSaleRate ?? 0.15 }}">
                                            </div>
                                        </div>
                                    @endcan

                                    {{-- القسم الثالث: التصنيف والربط --}}
                                    <div class="addproduct-section">
                                        <div class="addproduct-section-title">
                                            <span class="icon-badge"><i class="fas fa-sitemap"></i></span>
                                            {{ __('home.groups') }} / {{ __('home.parent_product') }}
                                        </div>
                                        <div class="addproduct-section-body">
                                            <div class="row row-sm mb-3">
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label">
                                                        <span class="field-icon"><i class="fas fa-tags"></i></span>
                                                        {{ __('home.groups') }}</label>
                                                    <select name="product_group" id="product_group"
                                                        class="form-control select2">
                                                        @foreach (App\Models\products_group::get() as $section)
                                                            <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label">
                                                        <span class="field-icon"><i class="fas fa-boxes"></i></span>
                                                        {{ __('home.parent_product') }}</label>
                                                    <select name="MAINproduct" id="MAINproduct" class="form-control select2">
                                                        <option value=0>{{ __('home.no_parent_product') }}</option>
                                                    </select>
                                                    <small class="addproduct-hint">{{ __('home.parent_product_hint') }}</small>
                                                </div>
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label">
                                                        <span class="field-icon"><i class="fas fa-cube"></i></span>
                                                        {{ __('home.unit') }}</label>
                                                    <select name="unit" id="unit" class="form-control select2">
                                                        <option value="">{{ __('home.select_unit') }}</option>
                                                        <option value="carton">{{ __('home.unit_carton') }}</option>
                                                        <option value="bag">{{ __('home.unit_bag') }}</option>
                                                        <option value="sack">{{ __('home.unit_sack') }}</option>
                                                        <option value="piece">{{ __('home.unit_piece') }}</option>
                                                        <option value="box">{{ __('home.unit_box') }}</option>
                                                        <option value="case">{{ __('home.unit_case') }}</option>
                                                        <option value="dozen">{{ __('home.unit_dozen') }}</option>
                                                        <option value="kg">{{ __('home.unit_kg') }}</option>
                                                        <option value="gram">{{ __('home.unit_gram') }}</option>
                                                        <option value="liter">{{ __('home.unit_liter') }}</option>
                                                        <option value="ml">{{ __('home.unit_ml') }}</option>
                                                        <option value="bottle">{{ __('home.unit_bottle') }}</option>
                                                        <option value="jar">{{ __('home.unit_jar') }}</option>
                                                        <option value="roll">{{ __('home.unit_roll') }}</option>
                                                        <option value="tray">{{ __('home.unit_tray') }}</option>
                                                        <option value="gallon">{{ __('home.unit_gallon') }}</option>
                                                        <option value="meter">{{ __('home.unit_meter') }}</option>
                                                        <option value="unit">{{ __('home.unit_generic') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- تظهر بس لو المنتج ده مرتبط بمنتج أب (كرتون) من قائمة MAINproduct --}}
                                            <div class="row row-sm mb-3" id="division_unit_count_row" style="display:none;">
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label">
                                                        <span class="field-icon"><i class="fas fa-layer-group"></i></span>
                                                        {{ __('home.division_unit_count') }}</label>
                                                    <input type="number" min="1" step="1" class="form-control"
                                                        id="division_unit_count" name="division_unit_count" value="1">
                                                    <small
                                                        class="addproduct-hint">{{ __('home.division_unit_count_hint') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- القسم الرابع: المخزون والملاحظات --}}
                                    <div class="addproduct-section">
                                        <div class="addproduct-section-title">
                                            <span class="icon-badge"><i class="fas fa-map-marker-alt"></i></span>
                                            {{ __('home.storage_and_notes') }}
                                        </div>
                                        <div class="addproduct-section-body">
                                            <div class="row row-sm mb-3">
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label"> {{__('supprocesses.current_location')}}
                                                    </label>
                                                    <input type="text" class="form-control bg-light" id="current_location"
                                                        name="current_location" readonly>
                                                </div>
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label"> {{__('supprocesses.new_location')}} </label>
                                                    <input type="text" class="form-control" id="new_location"
                                                        name="new_location" required>
                                                </div>
                                                <div class="col-lg-4 addproduct-field">
                                                    <label class="parent-label"> {{ __('supprocesses.product_notes') }} </label>
                                                    <input type="text" class="form-control" id="product_notes"
                                                        name="product_notes" required value='-'>
                                                </div>
                                            </div>

                                            <div class="row row-sm mt-2">
                                                <div class="col-md-12 addproduct-field">
                                                    <label class="parent-label"><i class="fas fa-image"></i>
                                                        {{__('home.photo')}}</label>
                                                    <div class="addproduct-photo-box">
                                                        <input autocomplete="off" onchange="readURL(this)" type="file"
                                                            id="Item_img" name="Item_img" class="form-control-file">
                                                        @error('active')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="number" id="product_no" name="product_no" hidden>
                                    <input  id="MAINproduct_Input" name="MAINproduct_Input" hidden>
                                    <input hidden type="number" id="quentity" name="quentity">
                                    <input hidden id="user_id" name="user_id" value="{{Auth()->user()->discount_allow_limit}}">
                                </div>
                        </div>
                    </div> <br>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn text-white addproduct-save-btn"> <i class="fa fa-check-circle"></i>
                            {{__('roles.update')}} </button>
                    </div>


                    <br>



                </div>
            </div>



            <br>





            </table>

        </div>
        </div>


        </div>
        </div>
        <!-- row closed -->
        </div>
        <!-- Container closed -->
        </div>
        <div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl product-selection" role="document">
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
                                <input dir="ltr" type="text" class="form-control parent-input"
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
                                    <col style="width:10%">
                                    <col style="width:13%">
                                    <col style="width:10%">
                                    <col style="width:10%">

                                    <thead>
                                        <tr>
                                            <th style="font-size: 15px" class="border-bottom-0">#</th>
                                            <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                            <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                                {{__('home.product')}}</th>
                                            <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                                {{__('home.branch')}}</th>
                                            <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                                {{__('home.productlocation')}}</th>

                                            <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                            <th style="font-size: 13px" class="border-bottom-0">
                                                {{__('home.purchaseproductwithouttax')}}</th>
                                            <th style="font-size: 13px" class="border-bottom-0">
                                                {{__('home.sellingproduct without tax')}}</th>
                                            <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        <?php $i = 0;
        $data = 'm'; ?>

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
                                            <td id="tableData">- </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div>

                                </div>
                                <div class="row d-flex justify-content-between pagination-row">



                                </div>

                            </div>
                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{__('home.cancel')}}</button>
                            </div>

                        </div>


                    </div>
                </div>

            </div>


        </div>
    @endsection
@section('js')

    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>


    <script>

        document.addEventListener('keydown', (e) => {
            if (e.key === "F9") {
                $('#SearchProduct').modal().show();

            }
        })
        document.addEventListener('keydown', (e) => {
            searchtext = $('#product_code').val();

            if (e.ctrlKey && e.keyCode == '38') {
                searchtext = $('#product_code').val();
                $('#searchaboutproduct').val(searchtext);
                // document.getElementById("searchaboutproduct").focus();
                $('#SearchProduct').modal().show();



            }
        })
        $('#SearchProduct').on('shown.bs.modal', function () {
            $('#searchaboutproduct').focus();
        })

        function getproduct() { }



        function searchaboutproductfunction() {
            searchtext = $('#searchaboutproduct').val();
            branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "locale": "{{ app()->getLocale() }}", // ✅ صح
                    "branchs_id": branchs_id,
                },
                success: function (data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },

            });

        }
        $(document).on('click', '#ajax_pagination_in_search a ', function (e) {
            e.preventDefault();
            searchtext = $('#searchaboutproduct').val();
            branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();
            var url = $(this).attr("href");

            jQuery.ajax({
                url: url,
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "branchs_id": branchs_id,
                },
                success: function (data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },
                error: function () {

                }
            });
        });
        $('#SearchProduct').on('show.bs.modal', function (event) {
            searchtext = $('#searchaboutproduct').val();
            branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();


            console.log(branchs_id)
            jQuery.ajax({
                url: "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": '',
                    "locale": "{{ app()->getLocale() }}", // ✅ صح
                    "branchs_id": branchs_id,
                },
                success: function (data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },
                error: function () {

                }
            });

        })
    </script>

    <script>
        function chooseProduct(code, name, price, sale_price, product_location, availablequantity, productcode, MAINproductname, maincode) {
            $('#SearchProduct').modal().hide();

            // ملحوظة: عرض "المنتج الأب" بيتم تحت في نجاح طلب getproduct/{code}
            // (بنجيب اسم الأب الحقيقي من قاعدة البيانات مش من بيانات صف نتيجة البحث)
            searchtext = product_location;
            console.log(" {{URL::to('getproduct')}}/" + code)
            jQuery.ajax({
                url: " {{URL::to('getproduct')}}/" + code,
                type: 'get',
                cache: false,
                dataType: "json",
                success: function (data) {
                    $('#product_group').val(data['product_group']).change();
                    $('#uploadedimg').attr('src', "{{ URL::asset('assets/admin/uploads') }}" + '/' + data['photo']);
                    refnumber = data['refnumber']
                    numberofpice = data['numberofpice']

                    $('#MAINproduct_Input').val(data['main_product']);
                    $('#refnumber').val(refnumber);
                    $('#quentity').val(numberofpice);
                    if (typeof resetPriceTaxField === 'function') {
                        resetPriceTaxField('Wholesale_price', data['Wholesale_price']);
                    } else {
                        $('#Wholesale_price').val(data['Wholesale_price']);
                    }
                    $('#product_notes').val(data['notes']);

                    if (data['unit']) {
                        $('#unit').val(data['unit']).trigger('change');
                    }
                    if (data['division_unit_count']) {
                        $('#division_unit_count').val(data['division_unit_count']);
                    }
                    // نتحقق إن main_product رقم صحيح فعلاً قبل أي استخدام -- أي قيمة تانية (نص/فاضي) تتعامل كـ"بدون أب"
                    var isValidParentId = data['main_product']
                        && /^[0-9]+$/.test(String(data['main_product']))
                        && String(data['main_product']) != String(data['id']);

                    if (isValidParentId) {
                        $('#division_unit_count_row').show();

                        // نجيب اسم المنتج الأب الحقيقي من قاعدة البيانات (مش من صف نتيجة البحث)
                        // باستخدام روت getProductdJsonDecode/{id} اللي بيرجع بيانات منتج بالـ id بتاعه
                        jQuery.ajax({
                            url: "{{URL::to('getProductdJsonDecode')}}/" + data['main_product'],
                            type: 'get',
                            cache: false,
                            dataType: 'json',
                            success: function (parentData) {
                                console.log('بيانات المنتج الأب:', parentData);
                                console.log('*/*/*/*/*/*/*/*/*/*/')

                                var parentId = parentData['id'];
                                var parentName = parentData['product_name']
                                    || parentData['productnameshow']
                                    || parentData['productname']
                                    || ('#' + parentId);
                                var $mainSelect = $('#MAINproduct');
                           $('#MAINproduct').val(parentId)
                                    $mainSelect.append(
                                        $('<option selected>', { value: parentId }).text(parentName)
                                    );
                                
                                $mainSelect.trigger('change');
                            },
                            error: function () {
                                console.log('تعذر جلب اسم المنتج الأب رقم ' + data['main_product']);
                            }
                        });
                    } else {
                        $('#division_unit_count_row').hide();
                    }




                }

            }
            )
            var Product_Code = code
            var product_name = name
            var product_sale_pice = price
            $('#productnameshow').val(name);
            $('#current_location').val(availablequantity);
            $('#new_location').val(availablequantity);
            if (typeof resetPriceTaxField === 'function') {
                resetPriceTaxField('product_price', sale_price);
            } else {
                $('#product_price').val(sale_price);
            }
            $('#product_no').val(code);
            $('#productcode').val(product_location);
            if (typeof resetPriceTaxField === 'function') {
                resetPriceTaxField('purachesepice', price);
            } else {
                $('#purachesepice').val(price);
            }

        }
    </script>

    <script>
        document.addEventListener('keydown', (e) => {
            searchtext = $('#productcode').val();

            if (e.key === "Enter") {
                $('#searchaboutproduct').val(searchtext);
                // document.getElementById("searchaboutproduct").focus();
                $('#SearchProduct').modal().show();




            }
        })
        $('#SearchProduct').on('shown.bs.modal', function () {
            $('#searchaboutproduct').focus();
            $('#searchaboutproduct').val($('#productcode').val());
            $('#searchaboutproduct').keyup()
        })
        $(document).ready(function () {
            $('#productcode').focus();
            user_id = $('#user_id').val();
            if (user_id == 1) {

            }
            $(function () {
                var timeout = 4000; // in miliseconds (3*1000)
                $('.alert').delay(timeout).fadeOut(500);
            });

        });

        // بحث حي (AJAX) عن المنتج الأب -- زي بالظبط اللي في صفحة إضافة منتج جديد
        try { $('#MAINproduct').select2('destroy'); } catch (e) { }
        $('#MAINproduct').select2({
            placeholder: 'ابحث عن المنتج الأب (الكرتون)',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('itemcards.search') }}",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term,
                        branchs_id: $('#branchs_id').val()
                    };
                },
                processResults: function (data) {
                    $('#MAINproduct_Input').val( item.id);

                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: item.product_name
                        }))
                    };
                }
            }
        });

        // لو الفرع اتغيّر، نفضّي اختيار المنتج الأب الحالي عشان منسيبش منتج من فرع تاني متسجل بالغلط
        $('#branchs_id').on('change', function () {
            $('#MAINproduct').val(null).trigger('change');
        });

        // إظهار/إخفاء حقل "عدد الوحدات في الكرتون" حسب اختيار المنتج الأب
        $('#MAINproduct').on('change', function () {
            var mainProductId = $(this).val();
            if (mainProductId && mainProductId != '0') {
                $('#division_unit_count_row').slideDown(150);
            } else {
                $('#division_unit_count_row').slideUp(150);
                $('#division_unit_count').val(1);
            }
        });
    </script>

    <script>
        /*
         * إدخال السعر شامل الضريبة أو بدونها (للحقول: purachesepice / Wholesale_price / product_price).
         * القيمة اللي بتتبعت فعليًا للسيرفر في الحقل نفسه (name=...) هي دايمًا "بدون ضريبة"
         * زي ما الـ backend متوقع بالظبط -- مفيش أي تعديل في update_product_movement().
         *
         * لو الحقل في وضع "شامل الضريبة": اللي المستخدم شايفه ومكتوبه في الصندوق هو السعر شامل الضريبة،
         * وتحت الصندوق بيظهر سطر صغير بيقول "= كذا بدون ضريبة" بيتحدث لحظيًا.
         * لحظة إرسال الفورم (submit) بيتم تحويل القيمة المكتوبة (شامل) لقيمة بدون ضريبة قبل ما تتبعت.
         */
        (function () {
            var TAX_RATE = parseFloat($('#avtValue').val()) || 0.15;
            var PRICE_TAX_FIELDS = ['purachesepice', 'Wholesale_price', 'product_price'];
            var LABEL_EXCL_READOUT = @json(__('home.price_excl_readout'));
            var LABEL_INCL_READOUT = @json(__('home.price_incl_readout'));

            function toNumber(val) {
                var n = parseFloat(String(val).replace(',', '.'));
                return isNaN(n) ? 0 : n;
            }

            function formatMoney(n) {
                return (Math.round(n * 100) / 100).toFixed(2);
            }

            // القيمة "الحقيقية" بدون ضريبة المخزّنة دايمًا في data-excl-value، بغض النظر عن وضع العرض الحالي
            function getExclValue($input) {
                var stored = $input.data('excl-value');
                if (stored === undefined || stored === '' || stored === null) {
                    return toNumber($input.val());
                }
                return toNumber(stored);
            }

            function setExclValue($input, exclVal) {
                $input.data('excl-value', exclVal);
            }

            function refreshReadout(field) {
                var $input = $('#' + field);
                var $toggle = $('.price-tax-toggle[data-field="' + field + '"]');
                var $readout = $('#' + field + '_readout');
                var exclVal = getExclValue($input);
                var inclVal = exclVal * (1 + TAX_RATE);

                if ($toggle.is(':checked')) {
                    $readout.text(LABEL_EXCL_READOUT.replace(':value', formatMoney(exclVal)));
                } else {
                    $readout.text(LABEL_INCL_READOUT.replace(':value', formatMoney(inclVal)));
                }
            }

            // يرجّع الحقل لوضع "بدون ضريبة" (يُستخدم عند تعبئة السعر تلقائيًا من اختيار منتج)
            window.resetPriceTaxField = function (field, exclVal) {
                var $input = $('#' + field);
                var $toggle = $('.price-tax-toggle[data-field="' + field + '"]');
                var $switchLabel = $('.price-tax-switch[data-for="' + field + '"]');
                exclVal = toNumber(exclVal !== undefined ? exclVal : $input.val());

                $toggle.prop('checked', false);
                $switchLabel.removeClass('is-active');
                setExclValue($input, exclVal);
                $input.val(exclVal ? formatMoney(exclVal) : '');
                refreshReadout(field);
            };

            PRICE_TAX_FIELDS.forEach(function (field) {
                var $input = $('#' + field);
                setExclValue($input, toNumber($input.val()));
                refreshReadout(field);

                // المستخدم بيكتب في الصندوق (سواء كان الوضع شامل أو بدون ضريبة)
                $input.on('input', function () {
                    var $toggle = $('.price-tax-toggle[data-field="' + field + '"]');
                    var typed = toNumber($input.val());
                    var exclVal = $toggle.is(':checked') ? (typed / (1 + TAX_RATE)) : typed;
                    setExclValue($input, exclVal);
                    refreshReadout(field);
                });

                // تبديل وضع الإدخال (شامل الضريبة / بدون ضريبة)
                $('.price-tax-toggle[data-field="' + field + '"]').on('change', function () {
                    var $toggle = $(this);
                    var $switchLabel = $('.price-tax-switch[data-for="' + field + '"]');
                    var exclVal = getExclValue($input);

                    $switchLabel.toggleClass('is-active', $toggle.is(':checked'));

                    if ($toggle.is(':checked')) {
                        $input.val(exclVal ? formatMoney(exclVal * (1 + TAX_RATE)) : '');
                    } else {
                        $input.val(exclVal ? formatMoney(exclVal) : '');
                    }
                    refreshReadout(field);
                });
            });

            // قبل إرسال الفورم: نتأكد إن القيمة المرسلة فعليًا في كل حقل هي "بدون ضريبة"
            $('form').on('submit', function () {
                PRICE_TAX_FIELDS.forEach(function (field) {
                    var $input = $('#' + field);
                    if ($input.length) {
                        var exclVal = getExclValue($input);
                        $input.val(exclVal || $input.val());
                    }
                });
            });
        })();
    </script>






@endsection