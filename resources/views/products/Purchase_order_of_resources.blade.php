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

@section('title')
{{__('home.Purchase_order_of_resources')}}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{__('home.Purchase_order_of_resources')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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
    @if (session()->has('nodataprint'))
    <div class="alert alert-warning  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ __('home.nodataprint') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div style="border-radius:5px;margin-bottom:0" class="card mg-b-20 p-3">


                <div style="border-radius: 5px" class="card pb-0 p-3">

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'AddproducttoSupllier')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}




                        <div class="row">

                            <div class="col-lg-4" id="type">
                                <p class="mg-b-10 parent-label"> {{__('home.shearchbysuppliername')}}</p>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch" required>
                                    <option value="{{ $type ?? 'حدد نوع الفواتير' }}" selected>
                                        {{ $type ?? __('home.entersuppliername')}}
                                    </option>

                                    @foreach (App\Models\supllier::get() as $section)
                                    <option style="font-size: 15px" value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->





                            <div class="col">
                                <label for="inputName" class="control-label parent-label"> {{__('home.phone')}}</label>
                                <input type="number" class="form-control parent-input " id="phonenumber" name="phonenumber" title="يرجي ادخال رقم الجوال " readonly>
                            </div>
                            <div class="col">
                                <label for="inputName" class="control-label parent-label"> {{__('home.Location')}} </label>
                                <input type="text" class="form-control parent-input" id="address" name="address" readonly>
                            </div>


                        </div>


                        <br>

                        <div class="row">


                            <!-- col-4 -->



                            <input type="hidden" id="token_search" value="{{csrf_token() }}">
                            <input type="hidden" id="productNo">


                            <div class="col-lg-3 mg-lg-t-30" style=" height: 100; width: 300px;padding:0;">

                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                    <a style="background-color: #FBA10F;font-size: 13px;width : 128px;margin-top:5px" class="modal-effect btn btn-sm btn-info p-1 py-2" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">{{__('home.chooose product')}}
                                        <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M21 21l-6 -6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>



                            <div class="col-lg-4">
                                <label for="inputName" class="control-label parent-label"> {{__('home.productname')}} </label>
                                <input type="text" class="form-control parent-input" id="productnameshow" name="productnameshow" readonly required>

                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label for="inputName" class="control-label parent-label"> {{__('home.quantity')}} </label>
                                <input type="number" class="form-control parent-input" id="quentity" name="quentity">
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="inputName" class="control-label parent-label" required> {{__('home.thelastpurchase')}} </label>
                                <input type="number" class="form-control parent-input" id="quentityprice" name="quentityprice" required>
                                <input type="number" class="form-control parent-input" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}" hidden>
                            </div>
                        </div>
                        <br>
                        <input type="number" class="form-control parent-input" id="orderNo" name="orderNo" hidden>

                        <div class="d-flex justify-content-center">
                            <button style="font-size: 15px;background-color: #419BB2;" type="submit" id="button_1" class="btn btn-success p-1">
                                {{__('home.Add')}}
                                <svg style="width: 20px;height:20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </button>
                        </div>


                        <br>



                </div>
            </div>
            </form>


            <br>




            <?php $i = 0; ?>
            <div class="col-xl-12 py-3">
                <div style="border-radius: 5px" class="card mg-b-20 p-2 pt-5">
                    <div class="card-body">
                        <div class="">
                            <table id="example" class="table key-buttons our-table text-md-nowrap text-center table-responsive" name='prodyctsavaliable'>
                                <div class="table-responsive hoverable-table">
                                    <col style="width:2%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:10%">
                                    <col style="width:10%">
                                    <col style="width:10%">
                                    <col style="width:13%">
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0"># </th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.product')}}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.price')}}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.addedValue')}}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.total')}}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{__('home.operations')}}</th>



                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>

                                        </tr>
                                    </tbody>
                            </table>
                            <br>
                            <div class="table-responsive mg-t-30 table-padding">
                                <table class="table border text-md-nowrap mb-0 our-table table-striped text-center" id="tableTotalPrice" name="tableTotalPrice" width="50%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{ __('home.the amount') }}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                            <th style="font-size: 15px;padding:8px" class="border-bottom-0">{{ __('home.total') }} </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>*</td>
                                            <td>*</td>
                                            <td>*</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-2">

                                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() .'/'. ($page = 'printProductToSupllierOrder')) }}" method="POST" role="search" autocomplete="off">
                                    {{ csrf_field() }}


                                    <div class="d-flex justify-content-between">
                                        <div class='row '>
                                            <input type="number" class="form-control " name="show_invoice_number" id="show_invoice_number" title=" رقم الفاتورة " readonly required=true hidden>

                                        </div>
                                        <div class="d-flex justify-content-center my-2">
                                            <button class="btn btn-success print-style p-1">
                                                {{__('home.print')}}
                                                <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                    <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>


                                    </div>
                                </form>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <br />
            </form>

        </div>


    </div>


</div>
</div>
<!-- row closed -->
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.RETURNSPURCHASEpart') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchaseOrder')) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="original_quantity" id="original_quantity" value="">

                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="ordernumber" id="ordernumber" value="">
                        <label for="recipient-name" class="col-form-label"> {{ __('home.product') }} </label>

                        <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">{{ __('home.numberofpicereturens') }}</label>
                        <input class="form-control" name="return_quentity" id="return_quentity" type="text">

                    </div>

                    <div class="modal-footer">
                        <button id="added_product" name="added_product" class="btn btn-primary">{{__('home.confirm')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Container closed -->
</div>



{{-- Update ( 24/4/2023 ) --}}

<div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
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
                        <label for="inputName" style="font-weight: bold" class="control-label parent-label"> {{__('home.searchaboutproduct')}} </label>
                        <input dir="ltr" type="text" class="form-control parent-input" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                    </div>
                    <br>
                    <div class="table-responsive" id="ajax_responce_serarchDiv">
                        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
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
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.productlocation')}}</th>

                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                    <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                    <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>
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

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>

                </div>


            </div>
        </div>

    </div>
</div>

{{-- End Update ( 24/4/2023 ) --}}



@endsection
@section('js')
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
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
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>
<script>
    $('#exampleModal2').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        console.log('jjhjhjjjj ----- jjhhhhhh')
        console.log(button.data('id'))
        console.log(button.data('ordernumber'))
        console.log(button.data('section_name'))
        console.log(button.data('description'))
        var id = button.data('id')
        var ordernumber = button.data('ordernumber')
        var section_name = button.data('section_name')
        var description = button.data('description')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #ordernumber').val(ordernumber);
        modal.find('.modal-body #product_name').val(section_name);
        modal.find('.modal-body #return_quentity').val(description);
        modal.find('.modal-body #original_quantity').val(description);

    })
</script>
<script>
    function convertToNumbersalePrice() {
        var input = document.getElementById("sale_price");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>
<script>
    function convertToNumberpurchasersPrice() {
        var input = document.getElementById("quentityprice");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>
<script>
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();


        branchs_id = $('#branchs_id').val();
        console.log(branchs_id)
        jQuery.ajax({
            url: " {{URL::to('searchChooseProductpaginatenew')}}/" + searchtext + "/" + branchs_id,
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

    }
</script>


{{-- Update ( 24/4/2023 ) --}}





<script>
    function chooseProduct(code, name, price, sale_price, location, availablequantity) {
        $('#SearchProduct').modal().hide();


        console.log('------')
        console.log(code)
        console.log(name)
        console.log(price)
        name = name.replaceAll("<", " ");
        location = location.replaceAll("<", " ");

        var Product_Code = code
        var product_name = name
        var product_sale_pice = price
        $("#productname").val(location);
        $('#productnameshow').val(name);
        $('#quentityprice').val(price);
        $('#productNo').val(code);

    }
</script>

{{-- End Update ( 24/4/2023 ) --}}


<script>
    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
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
            success: function(data) {
                console.log(data)
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });
    $('#SearchProduct').on('show.bs.modal', function(event) {
        branchs_id = $('#branchs_id').val();
        console.log(branchs_id)
        jQuery.ajax({
            url: " {{URL::to('ChooseProductpaginatenew')}}/" + branchs_id,
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

    })
</script>
<script>
    function increaseProduct(id_increase, ordernumber, increasequentity) {

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);
        console.log('+++incr---->>>---ease+++')

        console.log(id_increase)
        console.log(ordernumber)
        console.log(increasequentity)

        console.log('+++increase+++')
        var url = " {{URL::to('updatePurchaseOrderToIncrease')}}";
        token_search = $('#token_search').val();




        $.ajax({
            url: url,
            type: 'post',
            cache: false,

            data: {
                _token: token_search,
                id: id_increase,
                ordernumber: ordernumber,
                increasequentity: 1,
            },


            success: function(data) {

                // const map =(JSON.parse(response));



                console.log('+++increase+++')
                console.log(data)
                var tableHeaderRowCount = 1;

                var rowCount = table.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    table.deleteRow(tableHeaderRowCount);
                }
                count1 = 0;
                added_value_total = 0;
                total_purchases = 0;
                total_amount = 0;
                data.forEach(async (product) => {

                    $('#orderNo').val(data['orderNo'])

                    count1 = product['count'],
                        product_code = product['productCode']
                    product_name = product['product_name']
                    quentity = product['quantity']
                    purchasingـprice = product['purchasingـprice']
                    saleperpice = product['saleperpice']
                    addedvalue = product['Added_Value']
                    total = product['total']

                    added_value_total = added_value_total + (product['Added_Value'] * product['quantity'])
                    total_purchases = total_purchases + (product['purchasingـprice'] * product['quantity'])
                    total_amount = total_amount + ((product['purchasingـprice'] * product['quantity']) + (product['Added_Value'] * product['quantity']))



                    text1 = '<button style="width:40px;height:20px" type="button"  class="btn btn-danger mt-2" data-dismiss="modal"'
                    result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i class="las la-trash trash-table"></i>', "</button> ")

                    text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                    text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")



                    if (quentity > 0) {


                        let table = document.getElementById("example");
                        let row = table.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        let c8 = row.insertCell(7);

                        // Add data to c1 and c2

                        c1.innerText = count1
                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                        c3.innerText = product_name
                        c4.innerText = quentity
                        c5.innerText = purchasingـprice
                        c6.innerText = addedvalue
                        c7.innerText = Math.round(total * 100, 2) / 100
                        c8.innerHTML = result + ' ' + result2 + '  ' + result1




                    }


                });
                $("#productname").val('');
                $('#productnameshow').val('');
                $('#quentityprice').val('price');
                $('#productNo').val('');
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;

                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);


                // Add data to c1 and c2

                c1.innerText = Math.round(total_purchases * 100, 2) / 100
                c2.innerText = Math.round(added_value_total * 100, 2) / 100
                c3.innerText = Math.round(total_amount * 100, 2) / 100

                //    update3/3/2023





                var rowCount = table.rows.length;

                for (var i = 0; i < rowCount; i++) {
                    var data = table.rows[i].innerText.innerText;
                    console.log('end');

                }










            },
            error: function(response) {
                alert("{{ __('home.sorryerror')}}")

            }
        });


    }
</script>
<script>
    function decreaseProdect(id_decrease, ordernumber, decreasequentity) {
        event.preventDefault();
        $('#exampleModal2').modal('hide');

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);

        var url = " {{URL::to('updatePurchaseOrder')}}";
        token_search = $('#token_search').val();

        console.log('+++Decrease+++')
        console.log('+++id+++')

        console.log(id_decrease)
        console.log(ordernumber)
        console.log(decreasequentity)

        console.log('+++Decr--->ease+++')



        $.ajax({
            url: url,
            type: 'post',
            cache: false,

            data: {
                _token: token_search,
                id: id_decrease,
                ordernumber: ordernumber,
                return_quentity: decreasequentity,
            },


            success: function(data) {

                // const map =(JSON.parse(response));



                console.log('++++++')
                console.log(data)
                var tableHeaderRowCount = 1;

                var rowCount = table.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    table.deleteRow(tableHeaderRowCount);
                }
                count1 = 0;
                added_value_total = 0;
                total_purchases = 0;
                total_amount = 0;
                data.forEach(async (product) => {

                    $('#orderNo').val(data['orderNo'])

                    count1 = product['count'],
                        product_code = product['productCode']
                    product_name = product['product_name']
                    quentity = product['quantity']
                    purchasingـprice = product['purchasingـprice']
                    saleperpice = product['saleperpice']
                    total = product['total']
                    addedvalue = product['Added_Value']
                    total = product['total']

                    added_value_total = added_value_total + (product['Added_Value'] * product['quantity'])
                    total_purchases = total_purchases + (product['purchasingـprice'] * product['quantity'])
                    total_amount = total_amount + ((product['purchasingـprice'] * product['quantity']) + (product['Added_Value'] * product['quantity']))



                    text1 = '<button style="width:40px;height:20px"  class="btn btn-danger mt-2" data-dismiss="modal"'
                    result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i class="las la-trash trash-table"></i>', "</button> ")

                    text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                    text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")


                    if (quentity > 0) {


                        let table = document.getElementById("example");
                        let row = table.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        let c8 = row.insertCell(7);

                        // Add data to c1 and c2

                        c1.innerText = count1
                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                        c3.innerText = product_name
                        c4.innerText = quentity
                        c5.innerText = purchasingـprice
                        c6.innerText = addedvalue
                        c7.innerText = Math.round(total * 100, 2) / 100
                        c8.innerHTML = result + ' ' + result2 + '  ' + result1




                    }


                });
                $("#productname").val('');
                $('#productnameshow').val('');
                $('#quentityprice').val('price');
                $('#productNo').val('');
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;

                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);


                // Add data to c1 and c2

                c1.innerText = Math.round(total_purchases * 100, 2) / 100
                c2.innerText = Math.round(added_value_total * 100, 2) / 100
                c3.innerText = Math.round(total_amount * 100, 2) / 100

                //    update3/3/2023





                var rowCount = table.rows.length;

                for (var i = 0; i < rowCount; i++) {
                    var data = table.rows[i].innerText.innerText;
                    console.log('end');

                }










            },
            error: function(response) {
                alert("{{ __('home.sorryerror')}}")

            }
        });

    }
</script>





<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

        function selectProduct(val) {
            alert(val);
        }

        $('select[name="clientNosearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['location']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                    },
                });
            } else {
                alert("{{ __('home.sorryerror')}}")
            }
        });




        // Update ( 24/4/2023 )




        $("#nextPage").click(function(e) {
            url = $('#nextPageValue').val().split('page=')[1];
            $.ajax({
                url: " {{ URL::to('goToSaleBypage') }}" + "?page=" + url,
                type: "GET",
                dataType: "json",
                success: function(data) {


                    $('#previousPagevalue').val(data['prev_page_url'])
                    $('#nextPageValue').val(data['next_page_url'])
                    $('#currentpage').val(data['current_page'])
                    let table = document.getElementById("SearchProductTable");
                    var tableHeaderRowCount = 1;

                    // table.classList.add('table-bordered');
                    // table.classList.add('table-striped');



                    var rowCount = table.rows.length;

                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        table.deleteRow(tableHeaderRowCount);
                    }
                    data['data'].forEach(async (product) => {
                        Product_id = product['id'],
                            Product_Code = product['Product_Code'],
                            id = product['id'],
                            product_name = product['product_name'],
                            purchasingـprice = product['purchasingـprice']
                        sale_price = product['sale_price']
                        numberofpice = product['numberofpice']
                        Product_Location = product['Product_Location']
                        button = '';





                        text =
                            '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';
                        name = product_name.replaceAll(" ", "<");
                        Product_Code_1 = Product_Code.replaceAll(" ", "<");
                        button = text.concat("chooseProduct(", id, ",", "'", name, "'",
                            ",", purchasingـprice, ",", sale_price, ",", "'",
                            Product_Code_1, "'", ",", numberofpice, ")",
                            ">{{ __('home.Add') }}</button>");




                        let row = table.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);

                        c1.innerText = Product_id

                        c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                        c3.innerHTML = product_name
                        c4.innerText = numberofpice
                        c5.innerText = purchasingـprice
                        c6.innerText = sale_price

                        c7.innerHTML = button







                    });

                },
            });
        });

        $("#previousPage").click(function(e) {

            url = $('#previousPagevalue').val().split('page=')[1];

            if (url != '') {
                $.ajax({
                    url: " {{ URL::to('goToSaleBypage') }}" + "?page=" + url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {

                        $('#previousPagevalue').val(data['prev_page_url'])
                        $('#nextPageValue').val(data['next_page_url'])
                        $('#currentpage').val(data['current_page'])
                        let table = document.getElementById("SearchProductTable");
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        data['data'].forEach(async (product) => {


                            Product_id = product['id'],
                                Product_Code = product['Product_Code'],
                                id = product['id'],
                                product_name = product['product_name'],
                                purchasingـprice = product['purchasingـprice']
                            sale_price = product['sale_price']
                            numberofpice = product['numberofpice']
                            Product_Location = product['Product_Location']
                            button = '';





                            text =
                                '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                            name = product_name.replaceAll(" ", "<");
                            Product_Code_1 = Product_Code.replaceAll(" ", "<");

                            button = text.concat("chooseProduct(", id, ",", "'", name,
                                "'", ",", purchasingـprice, ",", sale_price, ",",
                                "'", Product_Code_1, "'", ",", numberofpice, ")",
                                ">{{ __('home.Add') }}</button>");




                            let row = table.insertRow(-1); // We are adding at the end

                            let c1 = row.insertCell(0);
                            let c2 = row.insertCell(1);
                            let c3 = row.insertCell(2);
                            let c4 = row.insertCell(3);
                            let c5 = row.insertCell(4);
                            let c6 = row.insertCell(5);
                            let c7 = row.insertCell(6);

                            c1.innerText = Product_id

                            c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                            c3.innerHTML = product_name
                            c4.innerText = numberofpice
                            c5.innerText = purchasingـprice
                            c6.innerText = sale_price

                            c7.innerHTML = button








                        });

                    },
                });
            } else {
                alert('url null not fount pervoius')
            }
        });


        // End Update ( 24/4/2023 )




        $("#button_1").click(function(e) {
            event.preventDefault();

            let table = document.getElementById("example");

            var url = " {{URL::to('AddproducttoSupllier')}}";

            var _token = $("#token_search").val();




            if ($('#productnameshow').val() == '') {
                alert("{{ __('home.pleaseChooseProduct')}}")

            } else if ($('#address').val() == '') {
                alert("{{ __('home.entersuppliername')}}")

            } else if ($('#quentity').val() == '') {
                alert("{{ __('home.pleaseCompleteEmpty') }}")

            } else {
                console.log('+++++++++Data++++++++++')
                console.log(_token);
                console.log($('#clientnamesearch').val());
                console.log($('#clientNosearch').val());
                console.log($('#quentity').val());
                console.log($('#productNo').val());
                console.log($('#quentityprice').val());
                console.log($('#orderNo').val())

                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        "_token": _token,
                        "clientnamesearch": $('#clientnamesearch').val(),
                        "clientNosearch": $('#clientNosearch').val(),
                        "productnameshow": $('#productnameshow').val(),
                        "quentity": $('#quentity').val(),
                        "productNo": $('#productNo').val(),
                        "quentityprice": $('#quentityprice').val(),
                        "orderNo": $('#orderNo').val()
                    },


                    success: function(data) {


                        // const map =(JSON.parse(response));

                        console.log('+++HI mOHAMED+++')


                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        count1 = 0;
                        added_value_total = 0;
                        total_purchases = 0;
                        total_amount = 0;
                        data.forEach(async (product) => {
                            $('#show_invoice_number').val(product['orderNo'])

                            $('#orderNo').val(product['orderNo'])

                            count1 = product['count'],
                                product_code = product['productCode']
                            product_name = product['product_name']
                            quentity = product['quantity']
                            purchasingـprice = product['purchasingـprice']
                            addedvalue = product['Added_Value']
                            total = (product['total'])
                            added_value_total = product['totalAdded_Value']
                            total_purchases = (product['totalPrice'])
                            total_amount = added_value_total + total_purchases,

                                text1 = '<button style="width:40px;height:20px"  class="btn btn-danger mt-2" data-dismiss="modal"'
                            result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i  class="las la-trash trash-table"></i>', "</button> ")

                            text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                            result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                            text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                            result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")

                            if (quentity > 0) {
                                console.log(result2)

                                let table = document.getElementById("example");
                                let row = table.insertRow(-1); // We are adding at the end

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);
                                let c8 = row.insertCell(7);

                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = quentity
                                c5.innerText = purchasingـprice
                                c6.innerText = addedvalue
                                c7.innerText = Math.round(total * 100, 2) / 100
                                c8.innerHTML = result + ' ' + result2 + '  ' + result1




                            }


                        });
                        $("#productname").val('');
                        $('#productnameshow').val('');
                        $('#quentityprice').val('price');
                        $('#productNo').val('');
                        let tableTotalPrice = document.getElementById("tableTotalPrice");
                        var tableHeaderRowCount = 1;

                        var rowCount = tableTotalPrice.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            tableTotalPrice.deleteRow(tableHeaderRowCount);
                        }
                        let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);


                        // Add data to c1 and c2

                        c1.innerText = Math.round(total_purchases * 100, 2) / 100
                        c2.innerText = Math.round(added_value_total * 100, 2) / 100
                        c3.innerText = Math.round(total_amount * 100, 2) / 100











                        $('#productname').val('');
                        $('#productnameshow').val('');
                        $('#quentity').val('');
                        $('#quentityprice').val('');
                        $('#sale_price').val('');






                    },
                    error: function(response) {
                        console.log(response)
                        alert("{{ __('home.sorryerror')}}")

                    }
                });
            }
        });







        $('select[name="clientNosearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);

                        $('#phonenumber').val(data['phone'] == null ? '05---------' : data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });

        $('select[name="clientnamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");

                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone'] == null ? '05---------' : data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });
</script>


@endsection