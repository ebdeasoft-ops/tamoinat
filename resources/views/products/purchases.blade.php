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
    tr:nth-child(even) {
        background-color: #dde2ef;

        color: white
    }
</style>


@section('title')
{{ __('home.purchases') }}
@stop
@endsection
@section('page-header')
<div class="main-parent">

    <!-- breadcrumb -->
    <div style="background-color: #FF4F1F;" class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex justify-content-between">
                <div class="item1">
                    <h4 class="content-title mb-0 my-auto">
                        {{ __('home.purchases') }}
                    </h4>
                </div>



            </div>

        </div>
    </div>
    @if (session()->has('Status_Update'))
    <script>
        window.onload = function() {
            notif({
                msg: "تم تحديث حالة الدفع بنجاح",
                type: "success"
            })
        }
    </script>
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
    @if (session()->has('editpurchasein'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('editpurchasein') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

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

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">

            <div class="card mg-b-20">

                <div class="choose-product">
                    <div style="color:white;width:auto;text-align:center;border-radius:5px;font-size:1.3vw;height:45px" class="item2 p-1 m-3 d-flex justify-content-end p-2">

                       
                        &nbsp
                        <button style="background-color: #23395D;" class="modal-effect btn btn-sm btn-info  button-eng" data-effect="effect-scale" data-toggle="modal" href="#createsupplier" title="تحديد"><i style=" height: 50;font-weight:400 !important;
                                                 width: 65px;
                                                 font-size:13px" class="las"> {{__('home.addnewsupplier')}}</i>
                            <svg style="width: 18px;height:18px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M16 19h6"></path>
                                <path d="M19 16v6"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                            </svg>
                        </button>
                        &nbsp
                        <div class="last-sales">

                            <a style="color:white;background-color: #23395D;border-radius:5px;font-size:11px;width:165px;height:45px;padding:9px 6px" href="{{ url('/' . ($page = 'previousPurchasesInvoices')) }}">
                                {{ __('home.previousPurchasesInvoices') }}
                                <svg style="width:16px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path d="M17.927,5.828h-4.41l-1.929-1.961c-0.078-0.079-0.186-0.125-0.297-0.125H4.159c-0.229,0-0.417,0.188-0.417,0.417v1.669H2.073c-0.229,0-0.417,0.188-0.417,0.417v9.596c0,0.229,0.188,0.417,0.417,0.417h15.854c0.229,0,0.417-0.188,0.417-0.417V6.245C18.344,6.016,18.156,5.828,17.927,5.828 M4.577,4.577h6.539l1.231,1.251h-7.77V4.577z M17.51,15.424H2.491V6.663H17.51V15.424z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div style="border-radius: 10px" class="card m-3 p-3 pb-0">





                        <div class="row mb-3">

                            <div class="col-lg-4 mg-t-20 mg-lg-t-0 mb-2" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.shearchbysuppliername') }}</p>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch" required>

                                    <option style="font-size: 15px" value="-">{{ __('home.entersuppliername') }}
                                    </option>
                                    @foreach (App\Models\supllier::get() as $section)
                                    <option style="font-size: 15px" value="{{ $section->id }}"> {{ $section->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->





                            <input hidden type="text" class="form-control parent-input" id="clientName" name="clientName" value="{{ $data['recentsupllier']->name ?? '' }}" readonly>
                            <input hidden type="text" class="form-control parent-input" id="producttype" name="producttype" value=1 hidden>


                            <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.paymentmethod') }} </p>
                                <select class="form-control parent-input " name="pay" id="pay">




                                    <option value="Cash"> {{ __('report.cash') }}</option>
                                    <option value="Shabka"> {{ __('report.shabka') }} </option>
                                    <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>

                                    <option value="Credit"> {{ __('report.credit') }} </option>

                                </select>

                            </div>
                            <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control select2" name="branchs_id" id="branchs_id">

                                    <option value="{{ Auth()->user()->branch->id }}"> {{ Auth()->user()->branch->name }}
                                    </option>

                                    @foreach (App\Models\branchs::get() as $section)
                                    @if(Auth()->user()->branch->id!=$section->id)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->
                            <div class="col-lg-2" id="start_at">
                                    <label class="parent-label" for="exampleFormControlSelect1"> {{ __('home.date') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div><input autocomplete="off" class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}"
                                        name="date"  id="date" placeholder="YYYY-MM-DD" type="text" required>
                                    </div><!-- input-group -->
                                </div>
                            <div class="col-sm-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.enterinvoicenumber') }} </label>
                                <input autocomplete="off" type="text" class="form-control parent-input " id="Purchase_invoice_number_supplier" name="Purchase_invoice_number_supplier" title="يرجي ادخال رقم الفاتورة " onkeyup="invoiceNoConvertToNumber()">
                            </div>
                        </div>
                        <div class="row">




                            <input hidden autocomplete="off" type="text" class="form-control parent-input" id="shippingfee" name="shippingfee" value=0 onkeyup="shippingCostConvertToNumber()">



                            <input hidden autocomplete="off" type="text" class="form-control parent-input" id="Otherexpenses" name="Otherexpenses" title="  يرجي ادخال اسم الشركة  " value=0 onkeyup="otherExsepenseConvertToNumber()">



                        </div>

                        <br>


                        <div class="row mb-1">

                            <div style="margin-left:0" class="col-lg-3 mg-t-20 mg-lg-t-0 p-0">
                                <p class="mg-b-10 parent-label"> . </p>
                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                    <a style="background-color: #FBA10F;font-size:13px;width : 128px;margin-top : 5px" class="modal-effect btn btn-sm btn-info p-1 py-2" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">{{ __('home.chooose product') }}
                                        <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M21 21l-6 -6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.productNo') }} </p>
                                <input class="form-control parent-input parent-input" name="productname" id="productname" list="productsList" dir="ltr" readonly hidden>
                                <input type="text" class="form-control parent-input" id="productcode" name="productcode" readonly dir=ltr>

                            </div><!-- col-4 -->


                            <div class="col-lg-3">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.product') }}
                                </label>
                                <input autocomplete="off" type="text" class="form-control pa  rent-input" id="productnameshow" name="productnameshow" readonly>


                            </div>
                            <div class="col-lg-2" id="type">
                                <p class=" mg-b-10 parent-label"> {{ __('home.unittype') }} </p>
                                <select class="form-control parent-input" name="unit" id="unit" required>
                                    <option value="-" selected>{{ __('home.chooseOne') }}
                                    </option>

                                </select>

                            </div>
                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }}
                                </label>
                                <input autocomplete="off" type="number" class="form-control parent-input" id="quentity" name="quentity" value=1 onkeyup="convertToNumber()">
                                <input hidden type="number" class="form-control parent-input" id="Purchase_invoice_number" name="Purchase_invoice_number" value=1 onkeyup="convertToNumber()">
                            </div>

                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">





                        </div>

                        <input type="number" class="form-control parent-input" id="orderNo" name="orderNo" value="{{ $data['product'][0]['order_owner'] ?? null }}">



                        <div class="row">



                            <input type="text" hidden=true class="form-control" id="saveinvice" name="saveinvice" value=0>

                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.purachesepice') }}
                                </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="quentityprice" name="quentityprice" onkeyup="convertToNumberpurchasersPrice()">
                            </div>

                            <!-- <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label" required>{{ __('home.salepice') }} </label> -->
                                <input hidden autocomplete="off" type="text" class="form-control parent-input" id="sale_price" name="sale_price" onkeyup="convertToNumbersalePrice()">
                            <!-- </div> -->
                            <div class="col-lg-2" id="expDatediv">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('home.proDate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input autocomplete="off" class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="proDate" id="proDate" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-2" id="proDatediv">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('home.expDate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input autocomplete="off" class="form-control parent-input fc-datepicker" name="expDate" id="expDate" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-lg-4">
                                <label for="inputName" class="control-label parent-label">{{ __('home.notesClient') }}
                                </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="notes" name="notes" title="يرجي ادخال ملاحظات  " value="{{ __('home.notesClient') }}">
                            </div>
                        </div>

                        <br>

                        <div class="d-flex justify-content-center">
                            <button style="background-color: #419BB2" id="button_1" name="button_1" class="btn btn-success p-1">
                                {{ __('home.Add') }}
                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>


                    <br>

                    <?php $i = 0; ?>
                    <div class="col-xl-12">
                        <div style="border-radius: 10px" class="card mg-b-20">
                            <div class="card-header pb-0">

                            </div>
                            <div class="card-body">
                                <div class="table-responsive mg-t-40">
                                    <table class="table table-invoice border text-md-nowrap mb-0 text-center" name="example" id="example" width="100%">
                                        <col style="width:2%">
                                        <col style="width:12% ">
                                        <col style="width:23%">
                                        <col style="width:8%">
                                        <col style="width:8%">
                                        <col style="width:8%">
                                        <col style="width:10%">
                                        <col style="width:8%">
                                        <col style="width:22%">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">NO </th>
                                                <th dir=ltr class="border-bottom-0">{{ __('home.productNo') }} </th>
                                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                                <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                                                <th class="border-bottom-0">{{ __('home.purchase') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.addedValueperpice') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.salepice') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.total') }}</th>
                                                <th class="border-bottom-0">{{ __('home.operations') }} </th>


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
                                                <td>-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-md-4 mg-t-10 mg-lg-t-0">
                                            <label for="inputName" class="control-label parent-label">
                                                {{ __('home.entertotalafterdescount') }}
                                            </label>
                                            <input class="form-control parent-input" id="totaldicount" name="totaldicount" type="number" value=0 onchange="makeDiscountInvoice()">
                                        </div>

                                        <div class="col-lg-2 mg-t-10 mg-lg-t-0">
                                            <br>
                                            <button style="font-size: 15px; width: 150px;height: 35px;" class="btn btn-danger p-1 mt-2" onclick="cancelDiscountInvoice()">
                                                {{ __('home.canceldiscount') }}
                                                <svg class="svg-icon-buttons" style="width: 15 !important;height: 15 !important" viewBox="0 0 20 20">
                                                    <path fill="none" d="M12.71,7.291c-0.15-0.15-0.393-0.15-0.542,0L10,9.458L7.833,7.291c-0.15-0.15-0.392-0.15-0.542,0c-0.149,0.149-0.149,0.392,0,0.541L9.458,10l-2.168,2.167c-0.149,0.15-0.149,0.393,0,0.542c0.15,0.149,0.392,0.149,0.542,0L10,10.542l2.168,2.167c0.149,0.149,0.392,0.149,0.542,0c0.148-0.149,0.148-0.392,0-0.542L10.542,10l2.168-2.168C12.858,7.683,12.858,7.44,12.71,7.291z M10,1.188c-4.867,0-8.812,3.946-8.812,8.812c0,4.867,3.945,8.812,8.812,8.812s8.812-3.945,8.812-8.812C18.812,5.133,14.867,1.188,10,1.188z M10,18.046c-4.444,0-8.046-3.603-8.046-8.046c0-4.444,3.603-8.046,8.046-8.046c4.443,0,8.046,3.602,8.046,8.046C18.046,14.443,14.443,18.046,10,18.046z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                    </div>

                                    <br>
                                    <br>
                                    <div class="table-responsive mg-t-30 table-padding">
                                        <table style="border:1px solid black" class="table table-invoice border text-md-nowrap mb-0 text-center" id="tableTotalPrice" name="tableTotalPrice" width="50%">
                                            <col style="width:15%">
                                            <col style="width:15%">
                                            <col style="width:15%">
                                            <col style="width:20%">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.discount') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.total') }} </th>

                                                </tr>
                                            </thead>

                                            <body>
                                                <tr>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                            </body>

                                        </table>
                                    </div>
                                    <br>




                                    <div class="d-flex justify-content-between">
                                        <div class='row '>
                                            <input type="number" class="form-control parent-input " name="show_invoice_number" id="show_invoice_number" title=" رقم الفاتورة " readonly required=true hidden>

                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button style="background-color: #419BB2" id="saveInvoice" class="btn btn-success p-1">
                                                {{ __('home.invoice_save') }}
                                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                                </svg>
                                            </button>
                                            <!-- <a class="btn btn-success" href="{{ url('/' . ($page = 'printInvoice')) }}"> {{ __('home.print') }}</a> -->
                                        </div>

                                        <div style="background-color: #23395D;color:white;border-radius:5px" class="my-auto">
                                            <a style="color: white" class="btn btn p-2 p-0" href="{{ url('/' . ($page = 'purchases')) }}">
                                                {{ __('home.add_new') }}

                                            </a>
                                        </div>


                                    </div>

                                    <br />


                                </div>

                                <div class="row  d-flex justify-content-end mt-3">
                                    <form action="{{ '/' . ($page = 'printProductToSupllier') }}" method="POST" role="search" autocomplete="off">
                                        {{ csrf_field() }}



                                        <div class='col ' id="printdiv">
                                            <input type="number" class="form-control " name="show_invoice_number" id="show_invoice_number" title=" رقم الفاتورة " readonly required hidden>
                                            <input class="form-control " name="orderId" id="orderId" hidden>


                                            <button style="background-color: #23395D;font-size:15px;width: 80px!important;height:35px" type="submit" class="btn btn-success p-1 px-2 fw-bolder">
                                                {{ __('home.print') }}
                                                <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                    <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                                </svg>
                                            </button>





                                        </div>


                                    </form>




                                </div>

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
<!-- Container closed -->
</div>



</div>
<!-- /row -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
<div class="modal p-3" id="createcustomer">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('supprocesses.addproduct') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-2">
                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_name_ar') }}</label>
                        <input type="text" class="form-control parent-input" id="product_name_ar" name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}" required>
                    </div>


                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.product_code') }}</label>
                        <input type="text" class="form-control parent-input" id="product_code" name="product_code" type="text" dir="ltr" onkeyup="convertToNumber()" title="{{ __('supprocesses.product_code') }}" required>
                    </div>

                </div>

                {{-- 2 --}}
                <div class="row mb-2">
                    <div class="col-lg-4 mb-2">
                        <label for="inputName" class="control-label parent-label">{{ __('supprocesses.product_branch') }}</label>
                        <select name="Section" id="Section" class="form-control parent-input" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                            <!--placeholder-->
                            @foreach (App\Models\branchs::get() as $section)
                            <option value="{{ $section->id }}"> {{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <label for="inputName" class="control-label parent-label">{{ __('home.unit') }}</label>
                        <select name="unit" id="unit" class="form-control parent-input">
                            <!--placeholder-->
                            <div class="row">

                                <option value="piece"> {{ __('home.unitـpiece') }}</option>
                                <option value="box">{{ __('home.unit_box') }}</option>
                        </select>
                    </div>


                    <div class="col-lg-4 mb-2" style="direction: ltr !important;">

                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_location') }}</label>
                        <input dir="ltr" style="direction:LTR !important ;text-align:start!important;" type="text" class="form-control parent-input" id="product_location" name="product_location" title="{{ __('supprocesses.product_location') }}" required>
                    </div>



                </div>


                {{-- 3 --}}





                {{-- 5 --}}
                <div class="row mb-2">

                    <div class="col-lg-4 mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.minmum_quantity_stock_alart') }}</label>
                        <input type="text" class="form-control parent-input" id="minmum_quantity_stock_alart" name="minmum_quantity_stock_alart" onkeyup="minmum_quantity_stock_alartConvert()" title="{{ __('supprocesses.minmum_quantity_stock_alart') }}" value=2 required>
                    </div>







                    <div class="col-lg-8 mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('supprocesses.product_notes') }}</label>
                        <input type="text" class="form-control parent-input" id="product_notes" name="product_notes" title="{{ __('supprocesses.product_notes') }}">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <input type="text" class="form-control parent-input" id="product_name_en" name="product_name_en" title=" {{ __('supprocesses.product_name_en') }}" hidden>
                    </div>

                </div><br>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" onclick="createnewcustomerajax()">
                        {{ __('supprocesses.save_data') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg>
                    </button>
                </div>
        </div>

    </div>
</div>






<div class="modal p-3" id="createsupplier">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <div class="modal-header">
                <h6 class="modal-title"> {{__('home.addnewsupplier')}} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            {{ csrf_field() }}
            <div class="row mb-2">
                <div class="col-lg-4 mb-2">
                    <label for="inputName" class="control-label parent-label">
                        {{ __('home.entersuppliername') }}</label>
                    <input type="text" class="form-control parent-input" id="suppliername" name="suppliername" required>
                </div>

                <div class="col-lg-4 mb-2">
                    <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.phone') }}</label>
                    <input type="number" class="form-control parent-input" id="phone" name="phone" title="{{ __('supprocesses.phone') }}" required>
                </div>

                <div class="col-lg-4 mb-2">
                    <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.email') }}</label>
                    <input type="text" class="form-control parent-input" id="email" name="email" title="{{ __('supprocesses.email') }}" value='Example@gmail.com'>
                </div>

            </div>

            {{-- 2 --}}
            <div class="row mb-2">
                <div class="col-lg-4 mb-2">
                    <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.Location') }}</label>
                    <input type="test" class="form-control parent-input" id="supplierloction" name="supplierloction" title="{{ __('supprocesses.Location') }}" required>
                </div>
                <div class="col-lg-4 mb-2">
                    <label for="inputName" class="control-label parent-label">
                        {{ __('supprocesses.TaxـNumber') }}</label>
                    <input type="number" class="form-control parent-input" id="TaxـNumber" name="TaxـNumber" title="{{ __('supprocesses.TaxـNumber') }}" required>
                </div>

                <div class="col-lg-4 mb-2">
                    <label for="inputName" class="control-label parent-label">
                        {{ __('supprocesses.product_notes') }}</label>
                    <input type="text" class="form-control parent-input" id="suppliernotes" name="suppliernotes" title="{{ __('supprocesses.product_notes') }}">
                </div>

            </div><br>

            <br>
            <div class="d-flex justify-content-center">
                <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" onclick="createsupplierajax()">
                    {{ __('supprocesses.save_data') }}
                    <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                        <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                    </svg>
                </button>
            </div>
        </div>

    </div>
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

<?php
$avtSaleRate = App\Models\Avt::find(2);
$avtSaleRate = $avtSaleRate->AVT;
?>

<div class="modal fade" id="updateproductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div style="margin: 5% !important;" class="modal-dialog modal-special" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.addtopurchasesProduct') }}</h5>
                <button type="button" class="close choose-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchase')) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_orderdetails" id="id_orderdetails" value="">
                    <input type="hidden" name="avtValue" id="avtValue" value="{{$avtSaleRate}}">
                    <input type="hidden" name="ordernumberupdate" id="ordernumberupdate" value="">




                    <div class="row">

                        <div class="col">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.product') }} </label>
                            <input type="text" class="form-control parent-input" id="product_name1" name="product_name1" title="يرجي ادخال الكمية  " value="{{ $avtSaleRate }}" readonly>
                        </div>

                        <div class="col">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.purachesepice') }} </label>
                            <input type="number" class="form-control parent-input" id="purachesepice" name="purachesepice" title="يرجي ادخال الكمية  " onchange="changeAvtValue('{{ $avtSaleRate }}')" onkeyup="changeAvtValuempdale()" value=1 required>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.avt') }} </label>
                            <input type="text" class="form-control parent-input" id="avt_update" name="avt_update" title="يرجي ادخال الكمية  " value="{{ $avtSaleRate }}" readonly>
                        </div>

                        <div class="col">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }} </label>
                            <input type="number" class="form-control parent-input" id="quantity_update" name="quantity_update" title="يرجي ادخال الكمية  " value=1 required>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                <button id="updateproductalldata" name="updateproductalldata" class="btn btn-danger" data-dismiss="modal">{{ __('home.confirm') }}</button>
            </div>


        </div>
    </div>
</div>



<!-- delete -->
<div class="modal fade" id="increaseProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.addtopurchases') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchase')) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="id_increase" id="id_increase" value="">
                        <input type="hidden" name="ordernumber" id="ordernumber_increase" value="">
                        <label for="recipient-name" class="col-form-label"> {{ __('home.product') }} </label>

                        <input class="form-control parent-input" name="product_name_increasse" id="product_name_increasse" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">{{ __('home.numberofpiceIncrease') }}</label>
                        <input class="form-control parent-input" name="increasequantity" id="increasequantity" type="number">
                    </div>
            </div>
            <div class="modal-footer">
                <button id="increaseProductbutton" name="increaseProductbutton" class="btn btn-primary">{{ __('home.confirm') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>
            </form>
        </div>
    </div>
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

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchase')) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="original_quantity" id="original_quantity" value="">

                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="ordernumber" id="ordernumber" value="">
                        <label for="recipient-name" class="col-form-label"> {{ __('home.product') }} </label>

                        <input class="form-control parent-input" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">{{ __('home.numberofpicereturens') }}</label>
                        <input class="form-control parent-input" name="return_quentity" id="return_quentity" type="text">
                    </div>
            </div>
            <div class="modal-footer">
                <button id="added_product" name="added_product" class="btn btn-primary">{{ __('home.confirm') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- enter payments -->
<div class="modal" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.alert') }} </h6>
            </div>
            <div class="modal-body">






                <div class="col">
                    <span style="color:red">{{__('home.notepurchasesAlert')}}</span>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                <button id="confirmpayment" name="confirmpayment" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- main-content closed -->

@endsection
@section('js')
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
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();


    function makeDiscountInvoice() {


        invoiceId = $('#orderNo').val();

        totaldicount = $('#totaldicount').val()
        $('#totaldicount').val('')
        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.recentsave') }}")

        } else {
            $.ajax({
                url: "{{ URL::to('/makeTotalDiscontpurchases') }}/" + invoiceId + "/" + totaldicount,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success");
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
                    let c4 = row.insertCell(3);


                    // Add data to c1 and c2

                    c1.innerText = data['totalpurcgaseswithoudTax']
                    c2.innerText = data['Addedvalue'].toFixed(2)
                    c3.innerText = data['discount']
                    c4.innerText = (data['In_debt'] - data['discount']).toFixed(2)




                }

            });

        }






    }









    function cancelDiscountInvoice() {


        invoiceId = $('#orderNo').val();
        $('#totaldicount').val('')
        console.log("{{ URL::to('/cancelInvoiceDiscontpurcgases') }}/" + invoiceId)
        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.recentsave') }}")

        } else {
            $.ajax({
                url: "{{ URL::to('/cancelInvoiceDiscontpurcgases') }}/" + invoiceId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success cancel discount");
                    console.log(data);
                    if (data) {

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
                        let c4 = row.insertCell(3);


                        // Add data to c1 and c2

                        c1.innerText = data['totalpurcgaseswithoudTax']
                        c2.innerText = data['Addedvalue'].toFixed(2)
                        c3.innerText = data['discount']
                        c4.innerText = data['In_debt']





                    } else {
                        alert("{{ __('home.sorryerror') }}")
                    }
                }

            });
        }








    }
</script>
<script>
    var barcode = '';
    var interval;
    document.addEventListener('keydown', function(evt) {
        if (interval)
            clearInterval(interval);
        if (evt.code == 'Enter') {
            if (barcode)
                handleBarcode(barcode);
            barcode = '';
            return;
        }
        if (evt.key != 'Shift')
            barcode += evt.key;
        interval = setInterval(() => barcode = '', 20);
    });

    function handleBarcode(scanned_barcode) {
        branchs_id = $('#branchs_id').val();
        $.ajax({
            url: " {{ URL::to('getproductbyCodeandbranch') }}" + "/" + branchs_id + "/" + scanned_barcode,
            type: "GET",
            dataType: "json",
            success: function(data) {


                console.log(data)
                $('#unit').empty()
        if (data['item_type'] == 2) {
            $('#producttype').val('2')

            document.getElementById('expDatediv').hidden = false
            document.getElementById('proDatediv').hidden = false
        }
        uim=data['parent_uom']
        console.log(uim)
        $('#unit').append(`<option selected value="${data['uom_id']}">
                                       ${uim['name']}
                                  </option>`);
                $("#productname").val(data['id']);
                $("#productcode").val(data['barcode']);
                $('#productnameshow').val(data['name']);
                $('#sale_price').val(data['price']);

            }
            ,
                    error: function (response) {
                        console.log(response)
                        alert("عذرا المنتج غير مسجل نرجو تسجيلة اولا \n   Sorry, the product is not registered. Please register it first.")

                    }
        })
    }
</script>

<script>
    function createnewcustomerajax() {
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
        } else if ($('#product_code').val() == '') {
            alert("{{ __('supprocesses.product_code') }}")
        } else if ($('#product_location').val() == '') {
            alert("{{ __('supprocesses.product_location') }}")
        } else {


            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    product_notes: $('#product_notes').val() ?? '-',
                    minmum_quantity_stock_alart: $('#minmum_quantity_stock_alart').val(),
                    product_name_ar: $('#product_name_ar').val(),
                    product_code: $('#product_code').val(),
                    Section: $('#Section').val(),
                    unit: $('#unit').val(),
                    product_location: $('#product_location').val(),


                    success: function(data) {
                        $('#createcustomer').modal().hide();

                        $('#product_location').val('');
                        $('#product_name_ar').val('');
                        $('#product_notes').val('')
                        $('#product_code').val('')
                        console.log('seccusss12111');
                        alert('Product added successfully')

                    },
                }
            });







        }


    }

    function createsupplierajax() {
        var url = " {{ URL::to('create_addnewsupplierajax') }}";
        console.log($('#suppliernotes').val())
        console.log($('#TaxـNumber').val())
        console.log($('#supplierloction').val())
        console.log($('#email').val())
        console.log($('#phone').val())
        console.log($('#suppliername').val())
        var token_search = $("#token_search").val();
        if ($('#supplierloction').val() == '') {
            alert("{{ __('supprocesses.Location')}}")
        } else if ($('#suppliername').val() == '') {
            alert("{{  __('home.entersuppliername') }}")
        } else if ($('#TaxـNumber').val() == '') {
            alert("{{ __('supprocesses.TaxـNumber') }}")
        } else {


            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    name: $('#suppliername').val() ?? '-',
                    phone: $('#phone').val(),
                    email: $('#email').val(),
                    loction: $('#supplierloction').val(),
                    TaxـNumber: $('#TaxـNumber').val(),
                    notes: $('#suppliernotes').val() ?? '-',

                },
                success: function(data) {
                    console.log(data['name'])

                    $('#suppliernotes').val('');
                    $('#TaxـNumber').val('');
                    $('#supplierloction').val('');
                    $('#suppliername').val('');
                    $('#email').val('')
                    $('#phone').val('')
                    console.log('seccusss12111');

                    alert('Supplier added successfully')
                    $('#clientnamesearch').append($('<option>', {
                        value: data['id'],
                        text: data['name']
                    }));


                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}")

                }
            });







        }


    }
</script>

<script>
    function orderNoConvertToNumber() {
        var input = document.getElementById("purchase_invoice_no");
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
    function invoiceNoConvertToNumber() {
        var input = document.getElementById("Purchase_invoice_number_supplier");
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
    function shippingCostConvertToNumber() {
        var input = document.getElementById("shippingfee");
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
    function otherExsepenseConvertToNumber() {
        var input = document.getElementById("Otherexpenses");
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
    function convertToNumber() {
        var input = document.getElementById("quentity");
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


{{-- Update ( 24/4/2023 ) --}}

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

{{-- End Update ( 24/4/2023 ) --}}



{{-- Update ( 24/4/2023 ) --}}

<script>
    function chooseProduct(code, name, price, sale_price, location, availablequantity, parentUnitID, parentUnitName, retail_Id, retail_Name, item_type) {
        $('#SearchProduct').modal().hide();
        $('#searchaboutproduct').val('');
        $('#SearchProduct').modal().hide();
        $('#unit').empty()
        if (item_type == 2) {
            $('#producttype').val('2')

            document.getElementById('expDatediv').hidden = false
            document.getElementById('proDatediv').hidden = false
        }
        $('#unit').append(`<option selected value="${parentUnitID}">
                                       ${parentUnitName}
                                  </option>`);
        // if (retail_Id != '-') {
        //     $('#unit').append(`<option value="${retail_Id}">
        //                                ${retail_Name}
        //                           </option>`);
        //  }
        var Product_Code = code
        name = name.replaceAll("<", " ");
        location = location.replaceAll("<", " ");

        var product_sale_pice = sale_price
        $("#productname").val(Product_Code);
        $("#productcode").val(location);
        $('#productnameshow').val(name);
        $('#sale_price').val(product_sale_pice);

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
    $('#updateproductModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        console.log('jjhjhjjjj ----- jjhhhhhh')
        console.log(button.data('id'))
        console.log(button.data('ordernumber'))
        console.log(button.data('section_name'))
        console.log(button.data('description'))
        console.log(button.data('purchases'))
        var id = button.data('id')
        var ordernumber = button.data('ordernumber')
        var section_name = button.data('section_name')
        var description = button.data('description')
        var purchases = button.data('purchases')
        var modal = $(this)
        modal.find('.modal-body #id_orderdetails').val(id);
        modal.find('.modal-body #ordernumberupdate').val(ordernumber);
        modal.find('.modal-body #product_name1').val(section_name);
        modal.find('.modal-body #quantity_update').val(description);
        modal.find('.modal-body #purachesepice').val(purchases);

    })

    function changeAvtValue(avt) {

        avt = $('#avtValue').val();
        price = $('#purachesepice').val();
        $('#avt_update').val(Math.round((price * avt) * 1000) / 1000);



    }
</script>
<script>
    $('#increaseProduct').on('show.bs.modal', function(event) {
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
        modal.find('.modal-body #id_increase').val(id);
        modal.find('.modal-body #ordernumber_increase').val(ordernumber);
        modal.find('.modal-body #product_name_increasse').val(section_name);
        modal.find('.modal-body #increasequantity').val(1);

    })
</script>

<script>
    function increaseProduct(id_increase, ordernumber, increasequentity) {

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);
        console.log('increaseProduct--=-=-==--')
        var url = " {{ URL::to('increasePurchase') }}";
        token_search = $('#token_search').val();

        console.log('+++increase+++')

        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.notupadteaftersave') }}")

        } else {
            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    id: id_increase,
                    "ordernumber": ordernumber,
                    "product_name": product_name,
                    'increasequentity': increasequentity,
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
                    data['product'].forEach(async (product) => {


                        count1 = product['count'],
                            product_code = product['Product_Code']
                        product_name = product['product_name']
                        quentity = product['quantity']
                        purchasingـprice = product['purchasingـprice']
                        saleperpice = product['saleperpice']
                        addedvalue = product['Added_Value']
                        total = product['Unit_Price'] * product['quantity'] + product[
                            'Added_Value'] * product['quantity']

                        added_value_total = added_value_total + (product['Added_Value'] * product[
                            'quantity'])
                        total_purchases = total_purchases + (product['purchasingـprice'] * product[
                            'quantity'])
                        total_amount = total_amount + ((product['purchasingـprice'] * product[
                            'quantity']) + (product['Added_Value'] * product['quantity']))
                        console.log('++++increase succes++')
                        console.log(product_code)
                        console.log(product_name)
                        console.log(quentity)
                        console.log(purchasingـprice)
                        console.log(saleperpice)
                        console.log(addedvalue)
                        console.log(total)
                        console.log('data')


                        text1 =
                            ' <a style="width:40px;height:20px;" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result1 = text1.concat(product['id'], "  ", "data-section_name=", product[
                                'product_name'], "  ", "data-description=", product['quantity'],
                            "  ", "data-ordernumber=", data['orderNo'], '  ',
                            '  data-toggle="modal"   href="#exampleModal2"   title="حذف"><i class="las la-trash"></i></a>'
                        )
                        text4 =
                            ' <a  style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result4 = text4.concat(product['id'], "  ",
                            "data-section_name=", product['product_name'], "  ",
                            "data-description=", product['quantity'], "  ",
                            "data-ordernumber=", data['orderNo'], '  ', "data-purchases=", purchasingـprice, '  ',
                            '  data-toggle="modal"   href="#updateproductModal"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )

                        text2 =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result2 = text2.concat("onclick=", "increaseProduct(", product['id'], ",",
                            data['orderNo'], ",", "1", ")>",
                            '<i class="las la-plus"></i>',
                            "</button> ")


                        text =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result = text.concat("onclick=", "decreaseProdect(", product['id'], ",",
                            data['orderNo'], ",", "1", ")>",
                            '<i class="las la-minus"></i>',
                            "</button> ")



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
                            let c9 = row.insertCell(8);

                            // Add data to c1 and c2

                            c1.innerText = count1
                            c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'

                            c3.innerText = product_name
                            c4.innerText = quentity
                            c5.innerText = purchasingـprice
                            c6.innerText = addedvalue
                            c7.innerText = saleperpice
                            c8.innerText = ((purchasingـprice * quentity) + (addedvalue * quentity)).toFixed(2)
                            c9.innerHTML = result + '  ' + result2 + '   ' + result4 + ' ' + result1



                        }


                    });
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
                    let c4 = row.insertCell(3);



                    // Add data to c1 and c2

                    c1.innerText = total_purchases.toFixed(2)
                    c2.innerText = added_value_total.toFixed(2)
                    c3.innerText = data['discount']
                    c4.innerText = ((total_purchases + added_value_total) - data['discount']).toFixed(2)

                    //    update3/3/2023





                    var rowCount = table.rows.length;

                    for (var i = 0; i < rowCount; i++) {
                        var data = table.rows[i].innerText.innerText;
                        console.log('end');

                    }










                },
                error: function(response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        }


    }
</script>
<script>
    function decreaseProdect(id_decrease, ordernumber, decreasequentity) {
        event.preventDefault();
        $('#exampleModal2').modal('hide');

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);

        var url = " {{ URL::to('updatePurchase') }}";
        token_search = $('#token_search').val();

        console.log('+++ERROR+++')

        console.log(original_quantity >= return_quentity)

        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.notupadteaftersave') }}")

        } else {


            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    id: id_decrease,
                    "ordernumber": ordernumber,
                    "return_quentity": decreasequentity,
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
                    data['product'].forEach(async (product) => {

                        count1 = product['count'],
                            product_code = product['Product_Code']
                        product_name = product['product_name']
                        quentity = product['quantity']
                        purchasingـprice = product['purchasingـprice']
                        saleperpice = product['saleperpice']
                        addedvalue = product['Added_Value']
                        total = product['Unit_Price'] * product['quantity'] + product[
                            'Added_Value'] * product['quantity']

                        added_value_total = added_value_total + (product['Added_Value'] * product[
                            'quantity'])
                        total_purchases = total_purchases + (product['purchasingـprice'] * product[
                            'quantity'])
                        total_amount = total_amount + ((product['purchasingـprice'] * product[
                            'quantity']) + (product['Added_Value'] * product['quantity']))
                        console.log('++++++')
                        console.log(product_code)
                        console.log(product_name)
                        console.log(quentity)
                        console.log(purchasingـprice)
                        console.log(saleperpice)
                        console.log(addedvalue)
                        console.log(total)
                        console.log('data')


                        text1 =
                            ' <a style="width:40px;height:20px;" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result1 = text1.concat(product['id'], "  ", "data-section_name=", product[
                                'product_name'], "  ", "data-description=", product['quantity'],
                            "  ", "data-ordernumber=", data['orderNo'], '  ',
                            '  data-toggle="modal"   href="#exampleModal2"   title="حذف"><i class="las la-trash"></i></a>'
                        )
                        text4 =
                            ' <a  style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result4 = text4.concat(product['id'], "  ",
                            "data-section_name=", product['product_name'], "  ",
                            "data-description=", product['quantity'], "  ",
                            "data-ordernumber=", data['orderNo'], '  ', "data-purchases=", purchasingـprice, '  ',
                            '  data-toggle="modal"   href="#updateproductModal"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        text =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result = text.concat("onclick=", "decreaseProdect(", product['id'], ",",
                            data['orderNo'], ",", "1", ")>",
                            '<i class="las la-minus"></i>',
                            "</button> ")


                        text2 =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result2 = text2.concat("onclick=", "increaseProduct(", product['id'], ",",
                            data['orderNo'], ",", "1", ")>",
                            '<i class="las la-plus"></i>',
                            "</button> ")


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
                            let c9 = row.insertCell(8);

                            // Add data to c1 and c2

                            c1.innerText = count1
                            c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                            c3.innerText = product_name
                            c4.innerText = quentity
                            c5.innerText = purchasingـprice
                            c6.innerText = addedvalue
                            c7.innerText = saleperpice
                            c8.innerText = ((purchasingـprice * quentity) + (addedvalue * quentity)).toFixed(2)
                            c9.innerHTML = result + '  ' + result2 + '   ' + result4 + ' ' + result1



                        }


                    });
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
                    let c4 = row.insertCell(3);


                    // Add data to c1 and c2

                    c1.innerText = total_purchases.toFixed(2)
                    c2.innerText = added_value_total.toFixed(2)
                    c3.innerText = data['discount']
                    c4.innerText = ((total_purchases + added_value_total) - data['discount']).toFixed(2)

                    //    update3/3/2023





                    var rowCount = table.rows.length;

                    for (var i = 0; i < rowCount; i++) {
                        var data = table.rows[i].innerText.innerText;
                        console.log('end');

                    }










                },
                error: function(response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        }

    }
</script>



<script>
    $(document).ready(function() {

        document.getElementById('expDatediv').hidden = true
        document.getElementById('proDatediv').hidden = true


        document.getElementById('printdiv').hidden = true





        // Update ( 24/4/2023 )

        $("#saveInvoice").click(function(e) {
            if ($('#saveinvice').val() == 0) {
                $('#paymentmethod').modal('show');
                console.log('show')

            } else {
                alert("{{ __('home.recentsave') }}")
            }


        })

        $("#confirmpayment").click(function(e) {




                if ($('#saveinvice').val() == 0) {
                    $('#saveinvice').val(1)
console.log(" {{URL::to('savepurchase')}}/" + $('#show_invoice_number').val() + "/" + $('#pay').val() + "/" + $('#clientnamesearch').val())
                    $.ajax({
                        url: " {{URL::to('savepurchase')}}/" + $('#show_invoice_number').val() + "/" + $('#pay').val() + "/" + $('#clientnamesearch').val(),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            invoicenumber = $('#show_invoice_number').val()
                            // alert(invoicenumber)
                            document.getElementById('printdiv').hidden = false

                            $('#orderId').val(invoicenumber)
                            alert("{{__('home.seccesSave')}}")

                        },
                        error: function(response) {
                            console.log(response)
                            alert("{{ __('home.sorryerror') }}")
                            $('#saveinvice').val(0)


                        }
                    });
                } else {
                    alert("{{ __('home.recentsave') }}")
                }
            }



        );






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
                        $('#clientName').val(data['location']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                    },
                });
            } else {
                alert("{{ __('home.sorryerror') }}")
            }
        });
    });


    //addProduct


    // Update ( 24/4/2023 )






    // End Update ( 24/4/2023 )



    $("#button_1").click(function(e) {
        event.preventDefault();

        let table = document.getElementById("example");


        var _token = $("#token_search").val();
        console.log(_token);

        var url = " {{ URL::to('Addproducttopurchases') }}";


        clientnamesearch = $('#clientnamesearch').val();
        clientName = $('#clientName').val();
        pay = $('#pay').val();
        quentityprice = $('#quentityprice').val();
        purchase_invoice_no = $('#purchase_invoice_no').val();
        shippingfee = $('#shippingfee').val();
        Otherexpenses = $('#Otherexpenses').val();
        productname = $('#productname').val();
        productnameshow = $('#productnameshow').val();
        orderNo = $('#orderNo').val();
        branchs_id = $('#branchs_id').val();
        unit = $('#unit').val();
        quentity = $('#quentity').val();
        quentityprice = $('#quentityprice').val();
        sale_price = $('#sale_price').val();
        notes = $('#notes').val();
        expDate = $('#expDate').val();
        proDate = $('#proDate').val();
        data=$('#date').val();
        console.log(unit)
        console.log(data)
        console.log(proDate)
        console.log(expDate)
        console.log(proDate < expDate)
        Purchase_invoice_number_supplier = $('#Purchase_invoice_number_supplier').val();
        console.log('+++++++++++++++++++')
        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.notupadteaftersave') }}")

        } else if ((proDate == '') && $('#producttype').val() == '2') {
            alert("{{ __('home.proDate') }}")
        } else if ((expDate == '') && $('#producttype').val() == '2') {
            alert("{{ __('home.expDate') }}")
        } else if ((expDate < proDate) && $('#producttype').val() == '2') {
            alert("{{ __('home.alertdate') }}")
        } else if (clientnamesearch == '-') {
            alert("{{ __('home.entersuppliername') }}")

        } else if (productname == '' || productname == '' || sale_price ==
            '') {
            alert("{{ __('home.pleaseChooseProduct') }}")

        } else if (Purchase_invoice_number_supplier == '') {
            alert("{{ __('home.enterinvoicenumber') }}")

        } else if ($('#quentity').val() == '') {
            alert("{{ __('home.quantity') }}")

        } else if ($('#quentityprice').val() == '') {
            alert("{{ __('home.purachesepice') }}")

        } else if (shippingfee == '' || Otherexpenses == '') {
            alert("{{ __('home.pleaseCompleteEmpty') }}")

        } else {
            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    "_token": _token,
                    "clientnamesearch": clientnamesearch,
                    "clientName": clientName,
                    "pay": pay,
                    "purchase_invoice_no": purchase_invoice_no,
                    "shippingfee": shippingfee,
                    "Otherexpenses": Otherexpenses,
                    "productname": productname ?? '',
                    "productnameshow": productnameshow ?? '',
                    "orderNo": orderNo,
                    "unit": unit,
                    "proDate": proDate,
                    "expDate": expDate,
                    "branchs_id": branchs_id,
                    "quentity": quentity,
                    "quentityprice": quentityprice,
                    "sale_price": sale_price,
                    'quentityprice': quentityprice,
                    "notes": notes,
                    "data":data,
                    "Purchase_invoice_number_supplier": Purchase_invoice_number_supplier
                },


                success: function(data) {


                    // const map =(JSON.parse(response));
                    console.log(data)

                    $('#orderNo').val(data['orderNo'])
                    $('#Purchase_invoice_number_supplier').val(data[
                        'Purchase_invoice_number_supplier'])
                    $('#purchase_invoice_no').val(data['purchase_invoice_no'])
                    $('#shippingfee').val(data['shipping fee'])
                    $('#Otherexpenses').val(data['Other expenses'])
                    $('#show_invoice_number').val(data['orderNo'])


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
                    data['product'].forEach(async (product) => {


                        count1 = product['count'],
                            product_code = product['Product_Code']
                        product_name = product['product_name']
                        quentity = product['quantity']
                        purchasingـprice = product['purchasingـprice']
                        saleperpice = product['saleperpice']
                        addedvalue = product['Added_Value']
                        total = (product['purchasingـprice'] * product['quantity']) + (
                            product['Added_Value'] * product['quantity'])
                        added_value_total = added_value_total + (product[
                            'Added_Value'] * product['quantity'])
                        total_purchases = total_purchases + (product[
                            'purchasingـprice'] * product['quantity'])
                        total_amount = total_amount + ((product['purchasingـprice'] *
                            product['quantity']) + (product['Added_Value'] *
                            product['quantity']))

                        text1 =
                            ' <a style="width:40px;height:20px;" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result1 = text1.concat(product['id'], "  ",
                            "data-section_name=", product['product_name'], "  ",
                            "data-description=", product['quantity'], "  ",
                            "data-ordernumber=", data['orderNo'], '  ',
                            '  data-toggle="modal"   href="#exampleModal2"   title="حذف"><i class="las la-trash"></i></a>'
                        )
                        text4 =
                            ' <a  style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result4 = text4.concat(product['id'], "  ",
                            "data-section_name=", product['product_name'], "  ",
                            "data-description=", product['quantity'], "  ",
                            "data-ordernumber=", data['orderNo'], '  ', "data-purchases=", purchasingـprice, '  ',
                            '  data-toggle="modal"   href="#updateproductModal"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )

                        text =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result = text.concat("onclick=", "decreaseProdect(", product[
                                'id'], ",", data['orderNo'], ",", "1", ")>",
                            '<i class="las la-minus"></i>',
                            "</button> ")


                        text2 =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result2 = text2.concat("onclick=", "increaseProduct(", product[
                                'id'], ",", data['orderNo'], ",", "1", ")>",
                            '<i class="las la-plus"></i>',
                            "</button> ")

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
                            let c9 = row.insertCell(8);

                            // Add data to c1 and c2

                            c1.innerText = count1
                            c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                            c3.innerText = product_name
                            c4.innerText = quentity
                            c5.innerText = purchasingـprice
                            c6.innerText = addedvalue
                            c7.innerText = saleperpice
                            c8.innerText = ((purchasingـprice * quentity) + (addedvalue *
                                quentity)).toFixed(2)
                            c9.innerHTML = result + '  ' + result2 + '   ' + result4 + ' ' + result1



                        }


                    });
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
                    let c4 = row.insertCell(3);


                    // Add data to c1 and c2

                    c1.innerText = total_purchases.toFixed(2)
                    c2.innerText = added_value_total.toFixed(2)
                    c3.innerText = data['discount']
                    c4.innerText = ((total_purchases + added_value_total) - data['discount']).toFixed(2)
                    //    update3/3/2023





                    var rowCount = table.rows.length;

                    for (var i = 0; i < rowCount; i++) {
                        var data = table.rows[i].innerText.innerText;
                        console.log('end');

                    }




                    $('#productname').val('');
                    $('#productnameshow').val('');
                    $('#quentity').val('');
                    $('#quentityprice').val('');
                    $('#sale_price').val('');
                    $("#productcode").val('');







                },
                error: function(response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        }
    });



    $("#updateproductalldata").click(function(e) {
        event.preventDefault();
        // $('#updateproductModal').modal().hide();

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);

        var url = " {{ URL::to('updateproductalldatapurchases') }}";
        token_search = $('#token_search').val();
        id = $('#id').val();
        return_quentity = $('#return_quentity').val();
        original_quantity = $('#original_quantity').val();
        console.log('+++ERROR+++')
        console.log($('#id_orderdetails').val())
        console.log($('#purachesepice').val())
        console.log($('#quantity_update').val())
        console.log($('#ordernumberupdate').val())
        console.log(original_quantity >= return_quentity)
        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.notupadteaftersave') }}")

        } else {

            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    id: $('#id_orderdetails').val(),
                    pricepurchases: $('#purachesepice').val(),
                    quantity: $('#quantity_update').val(),
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
                    data['product'].forEach(async (product) => {


                        count1 = product['count'],
                            product_code = product['Product_Code']
                        product_name = product['product_name']
                        quentity = product['quantity']
                        purchasingـprice = product['purchasingـprice']
                        saleperpice = product['saleperpice']
                        addedvalue = product['Added_Value']
                        total = product['Unit_Price'] * product['quantity'] + product[
                            'Added_Value'] * product['quantity']

                        added_value_total = added_value_total + (product[
                            'Added_Value'] * product['quantity'])
                        total_purchases = total_purchases + (product[
                            'purchasingـprice'] * product['quantity'])
                        total_amount = total_amount + ((product['purchasingـprice'] *
                            product['quantity']) + (product['Added_Value'] *
                            product['quantity']))
                        console.log('++++++')
                        console.log(product_code)
                        console.log(product_name)
                        console.log(quentity)
                        console.log(purchasingـprice)
                        console.log(saleperpice)
                        console.log(addedvalue)
                        console.log(total)
                        console.log('data')

                        text1 =
                            ' <a style="width:40px;height:20px;" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result1 = text1.concat(product['id'], "  ",
                            "data-section_name=", product['product_name'], "  ",
                            "data-description=", product['quantity'], "  ",
                            "data-ordernumber=", data['orderNo'], '  ',
                            '  data-toggle="modal"   href="#exampleModal2"   title="حذف"><i class="las la-trash"></i></a>'
                        )
                        text4 =
                            ' <a  style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                        result4 = text4.concat(product['id'], "  ",
                            "data-section_name=", product['product_name'], "  ",
                            "data-description=", product['quantity'], "  ",
                            "data-ordernumber=", data['orderNo'], '  ', "data-purchases=", purchasingـprice, '  ',
                            '  data-toggle="modal"   href="#updateproductModal"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )

                        text =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result = text.concat("onclick=", "decreaseProdect(", product[
                                'id'], ",", data['orderNo'], ",", "1", ")>",
                            '<i class="las la-minus"></i>',
                            "</button> ")


                        text2 =
                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                        result2 = text2.concat("onclick=", "increaseProduct(", product[
                                'id'], ",", data['orderNo'], ",", "1", ")>",
                            '<i class="las la-plus"></i>',
                            "</button> ")

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
                            let c9 = row.insertCell(8);

                            // Add data to c1 and c2

                            c1.innerText = count1
                            c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                            c3.innerText = product_name
                            c4.innerText = quentity
                            c5.innerText = purchasingـprice
                            c6.innerText = addedvalue
                            c7.innerText = saleperpice
                            c8.innerText = ((purchasingـprice * quentity) + (addedvalue *
                                quentity)).toFixed(2)
                            c9.innerHTML = result + '  ' + result2 + '   ' + result4 + ' ' + result1





                        }


                    });
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
                    let c4 = row.insertCell(3);


                    // Add data to c1 and c2

                    c1.innerText = total_purchases.toFixed(2)
                    c2.innerText = added_value_total.toFixed(2)
                    c3.innerText = data['discount']
                    c4.innerText = ((total_purchases + added_value_total) - data['discount']).toFixed(2)

                    //    update3/3/2023





                    var rowCount = table.rows.length;

                    for (var i = 0; i < rowCount; i++) {
                        var data = table.rows[i].innerText.innerText;
                        console.log('end');

                    }










                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        }

    });


    //update
    $("#added_product").click(function(e) {
        event.preventDefault();
        $('#exampleModal2').modal('hide');

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);

        var url = " {{ URL::to('updatePurchase') }}";
        token_search = $('#token_search').val();
        id = $('#id').val();
        return_quentity = $('#return_quentity').val();
        original_quantity = $('#original_quantity').val();
        console.log('+++ERROR+++')
        console.log($('#id').val())
        console.log($('#product_name').val())
        console.log($('#return_quentity').val())
        console.log($('#original_quantity').val())
        console.log(original_quantity >= return_quentity)
        if ($('#saveinvice').val() == 1) {
            alert("{{ __('home.notupadteaftersave') }}")

        } else {
            if (original_quantity >= return_quentity) {



                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        id: $('#id').val(),
                        "ordernumber": $('#id').val(),
                        return_quentity: $('#return_quentity').val(),
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
                        data['product'].forEach(async (product) => {


                            count1 = product['count'],
                                product_code = product['Product_Code']
                            product_name = product['product_name']
                            quentity = product['quantity']
                            purchasingـprice = product['purchasingـprice']
                            saleperpice = product['saleperpice']
                            addedvalue = product['Added_Value']
                            total = product['Unit_Price'] * product['quantity'] + product[
                                'Added_Value'] * product['quantity']

                            added_value_total = added_value_total + (product[
                                'Added_Value'] * product['quantity'])
                            total_purchases = total_purchases + (product[
                                'purchasingـprice'] * product['quantity'])
                            total_amount = total_amount + ((product['purchasingـprice'] *
                                product['quantity']) + (product['Added_Value'] *
                                product['quantity']))
                            console.log('++++++')
                            console.log(product_code)
                            console.log(product_name)
                            console.log(quentity)
                            console.log(purchasingـprice)
                            console.log(saleperpice)
                            console.log(addedvalue)
                            console.log(total)
                            console.log('data')

                            text1 =
                                ' <a style="width:40px;height:20px;" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                            result1 = text1.concat(product['id'], "  ",
                                "data-section_name=", product['product_name'], "  ",
                                "data-description=", product['quantity'], "  ",
                                "data-ordernumber=", data['orderNo'], '  ',
                                '  data-toggle="modal"   href="#exampleModal2"   title="حذف"><i class="las la-trash"></i></a>'
                            )
                            text4 =
                                ' <a  style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                            result4 = text4.concat(product['id'], "  ",
                                "data-section_name=", product['product_name'], "  ",
                                "data-description=", product['quantity'], "  ",
                                "data-ordernumber=", data['orderNo'], '  ', "data-purchases=", purchasingـprice, '  ',
                                '  data-toggle="modal"   href="#updateproductModal"   title="تعديل"><i class="las la-align-justify"></i></a>'
                            )

                            text =
                                '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                            result = text.concat("onclick=", "decreaseProdect(", product[
                                    'id'], ",", data['orderNo'], ",", "1", ")>",
                                '<i class="las la-minus"></i>',
                                "</button> ")


                            text2 =
                                '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons" data-dismiss="modal"'
                            result2 = text2.concat("onclick=", "increaseProduct(", product[
                                    'id'], ",", data['orderNo'], ",", "1", ")>",
                                '<i class="las la-plus"></i>',
                                "</button> ")

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
                                let c9 = row.insertCell(8);

                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = quentity
                                c5.innerText = purchasingـprice
                                c6.innerText = addedvalue
                                c7.innerText = saleperpice
                                c8.innerText = ((purchasingـprice * quentity) + (addedvalue *
                                    quentity)).toFixed(2)
                                c9.innerHTML = result + '  ' + result2 + '   ' + result4 + ' ' + result1





                            }


                        });
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
                        let c4 = row.insertCell(3);


                        // Add data to c1 and c2

                        c1.innerText = total_purchases.toFixed(2)
                        c2.innerText = added_value_total.toFixed(2)
                        c3.innerText = data['discount']
                        c4.innerText = ((total_purchases + added_value_total) - data['discount']).toFixed(2)

                        //    update3/3/2023





                        var rowCount = table.rows.length;

                        for (var i = 0; i < rowCount; i++) {
                            var data = table.rows[i].innerText.innerText;
                            console.log('end');

                        }










                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                });
            } else {
                alert("{{ __('home.returnquantitymorethenpurchase') }}")

            }
        }
    });




    //endProduct


    //update


    //end update

    //increase quantity
    // id_increase   ordernumber_increase  product_name_increasse  increasequantity











    //end increase


    const tbodyEl = document.querySelector("tbody");
    const tableEl = document.querySelector("table");

    function onDeleteRow(e) {
        if (!e.target.classList.contains("deleteBtn")) {
            return;
        }

        const btn = e.target;
        console.log('start')

        console.log(btn.closest("tr").data)
        console.log('end')
        alert('delete')
        btn.closest("tr").remove();
    }

    tableEl.addEventListener("click", onDeleteRow);


    //end added
</script>




<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });
        $('#orderNo').hide();

    });
</script>

@endsection