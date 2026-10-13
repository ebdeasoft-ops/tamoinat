@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<style>
    .damage-card { border: 0; border-radius: .75rem; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .damage-card .card-header { background: transparent; border-bottom: 1px solid #eef0f3; }
    .damage-card .card-title { font-weight: 600; font-size: 15px; }
    .btn-choose-product {
        background-color: #FF4F1F;
        border-color: #FF4F1F;
        color: #fff;
        font-weight: 500;
        white-space: nowrap;
    }
    .btn-choose-product:hover { background-color: #e6440f; color: #fff; }
    .parent-label { font-weight: 600; font-size: 13px; color: #4a5568; }
    .qty-badge { font-size: 13px; padding: .4em .7em; }
    #SearchProductTable thead th { background: #f8f9fb; font-size: 13px !important; }
    #SearchProductTable td.no-result { padding: 30px 0; color: #98a2b3; }
    .search-loading { padding: 24px 0; text-align: center; color: #98a2b3; }
</style>
@endsection

@section('title')
{{ __('home.product damage') }}
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-package-variant-closed tx-24 mr-2" style="color:#FF4F1F;"></i>
                <h4 class="content-title mb-0 my-auto">{{ __('home.product damage') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>خطأ</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session()->has('damageproduct'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('damageproduct') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row">
    <div class="col-xl-12">

        <!-- Form card -->
        <div class="card damage-card mg-b-20">
            <div class="card-header pb-2">
                <h6 class="card-title mb-0">
                    <i class="la la-cubes mr-1" style="color:#FF4F1F;"></i>
                    {{ __('home.product damage') }}
                </h6>
            </div>
            <div class="card-body pt-3">
                <form id="damageForm"
                      action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/product_damage_add') }}"
                      method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="row align-items-center">
                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                            <input class="form-control select2" name="productNo" id="productNo" style="display:none;">
                        </div>

                        <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                            <a class="modal-effect btn btn-choose-product d-inline-flex align-items-center"
                               data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">
                                <i class="las la-search mr-2"></i>
                                {{ __('home.chooose product') }}
                            </a>
                        </div>
                    </div>

                    <hr class="mg-y-20">

                    <div class="row">
                        <div class="col-lg-5 mg-t-20 mg-lg-t-0">
                            <label for="productnameshow" class="control-label parent-label">{{ __('home.productname') }}</label>
                            <input type="text" class="form-control parent-input" id="productnameshow" name="productnameshow" required>
                        </div>

                        <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                            <label for="lastpurchasesCost" class="control-label parent-label">{{ __('home.lastpurchasesCost') }}</label>
                            <input type="number" class="form-control" id="lastpurchasesCost" name="lastpurchasesCost" readonly>
                        </div>

                        <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                            <label for="quentity" class="control-label parent-label">{{ __('supprocesses.current_quantity') }}</label>
                            <input type="number" class="form-control" id="quentity" name="quentity" readonly>
                        </div>

                        <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                            <label for="newquentity" class="control-label parent-label">{{ __('home.quentitydamage') }}</label>
                            <input type="text" class="form-control parent-input" id="newquentity" name="newquentity"
                                   onkeyup="convertToNewQuantity()" required>
                        </div>

                        <input type="number" class="form-control" id="orderNo" name="orderNo" hidden>
                    </div>

                    <div class="d-flex justify-content-center mg-t-20">
                        <button type="submit" class="btn btn-success print-style px-5">
                            <i class="la la-save mr-1"></i> {{ __('roles.update') }}
                        </button>
                    </div>

                    <input type="hidden" id="branchs_id" name="branchs_id" value="{{ Auth()->user()->branchs_id }}">
                </form>
            </div>
        </div>

        <!-- Requested items card -->
        @if (isset($itemsRequest) && count($itemsRequest))
        <div class="card damage-card mg-b-20">
            <div class="card-header pb-2 d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="la la-list-ul mr-1" style="color:#FF4F1F;"></i>
                    {{ __('home.product') }}
                </h6>
                <a class="btn btn-sm btn-success"
                   href="{{ url('/printOrderPriceFromSupplier/' . $itemsRequest[0]->order_id) }}">
                    <i class="la la-print mr-1"></i> {{ __('home.print') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                    <table class="table table-hover table-striped mb-0 text-center" id="example1" data-page-length="50">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemsRequest as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->productData->Product_Code }}</td>
                                <td>{{ $product->productData->product_name }}</td>
                                <td><span class="badge badge-light qty-badge">{{ $product->quantity }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Search product modal -->
<input type="hidden" id="token_search" value="{{ csrf_token() }}">

<div class="modal fade" id="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="SearchProductLabel" dir="rtl" aria-hidden="true">
    <div class="modal-dialog modal-xl product-selection" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SearchProductLabel">
                    <i class="la la-search mr-1"></i> {{ __('home.searchaboutproduct') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="searchaboutproduct" class="parent-label">{{ __('home.searchaboutproduct') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="la la-search"></i></span>
                        </div>
                        <input dir="ltr" type="text" class="form-control parent-input"
                               placeholder="{{ __('home.Search By Name or Product Number') }}"
                               id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                    </div>
                </div>

                <div class="table-responsive" id="ajax_responce_serarchDiv">
                    <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%">
                        <colgroup>
                            <col style="width:5%">
                            <col style="width:14%">
                            <col style="width:28%">
                            <col style="width:10%">
                            <col style="width:10%">
                            <col style="width:13%">
                            <col style="width:10%">
                            <col style="width:10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                <th class="border-bottom-0">{{ __('home.productlocation') }}</th>
                                <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                                <th class="border-bottom-0">{{ __('home.purchaseproductwithouttax') }}</th>
                                <th class="border-bottom-0">{{ __('home.sellingproduct without tax') }}</th>
                                <th class="border-bottom-0">{{ __('home.Add') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="no-result">—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row d-flex justify-content-between pagination-row" id="ajax_pagination_in_search"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    document.addEventListener('keydown', (e) => {
        if (e.key === "F9") {
            $('#SearchProduct').modal('show');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === "Enter") {
            var searchtext = $('#product_code').val();
            $('#searchaboutproduct').val(searchtext);
            $('#SearchProduct').modal('show');
        }
    });

    $('#SearchProduct').on('shown.bs.modal', function () {
        $('#searchaboutproduct').focus();
    });

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        return strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
    }

    function convertToNewQuantity() {
        var input = document.getElementById("newquentity");
        input.value = toEnglishNumber(input.value);
    }

    function searchaboutproductfunction() {
        var searchtext = $('#searchaboutproduct').val();
        var branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();

        $("#ajax_responce_serarchDiv").html('<div class="search-loading"><i class="la la-spinner la-spin mr-1"></i> جاري البحث...</div>');

        jQuery.ajax({
            url: "{{ URL::to('ChooseProductpaginatenewupdate') }}",
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
                $("#ajax_responce_serarchDiv").html('<div class="search-loading">تعذّر تحميل النتائج، حاول مرة أخرى.</div>');
            }
        });
    }

    $(document).on('click', '#ajax_pagination_in_search a', function (e) {
        e.preventDefault();
        var search_by_text = $("#search_by_text").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function (data) {
                $("#ajax_responce_serarchDiv").html(data);
            }
        });
    });

    $('#SearchProduct').on('show.bs.modal', function () {
        var branchs_id = $('#branchs_id').val();
        jQuery.ajax({
            url: "{{ URL::to('ChooseProductpaginatenew') }}/" + branchs_id,
            type: 'get',
            dataType: 'html',
            cache: false,
            success: function (data) {
                $("#ajax_responce_serarchDiv").html(data);
            }
        });
    });

    function chooseProduct(code, name, price, sale_price, product_location, availablequantity) {
        $('#SearchProduct').modal('hide');

        $("#productNo").val(code);
        $('#productnameshow').val(name);
        $('#lastpurchasesCost').val(price);

        var branchs_id = $('#branchs_id').val();

        jQuery.ajax({
            url: "{{ URL::to('detproductbycode') }}/" + product_location + "/" + branchs_id,
            type: 'get',
            cache: false,
            dataType: "json",
            success: function (data) {
                $('#quentity').val(data['numberofpice']);
            }
        });
    }

    $('#damageForm').on('submit', function (e) {
        if (!$('#productNo').val()) {
            e.preventDefault();
            alert('{{ __('home.chooose product') }}');
        }
    });

    $(document).ready(function () {
        $('.alert').delay(4000).fadeOut(500);

        $('select[name="suppliertNosearch"], select[name="suppliernamesearch"]').on('change', function () {
            var selectclientid = $(this).val();
            if (!selectclientid) return;

            $.ajax({
                url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('#clientName').val(data['name']);
                    $('#address').val(data['location']);
                    $('#phonenumber').val(data['phone']);
                    $('#notes').val(data['comp_name']);
                },
            });
        });
    });

    $('select[name="productNo"], select[name="productname"]').on('change', function () {
        var selectclientid = $(this).val();
        if (!selectclientid) return;

        $.ajax({
            url: "{{ URL::to('getproduct') }}/" + selectclientid,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $('#productnameshow').val(data['product_name']);
                $('#quentity').val(data['numberofpice']);
            },
        });
    });
</script>
@endsection