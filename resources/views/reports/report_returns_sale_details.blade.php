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
{{ __('report.report_returns_sale') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.report_returns_sale') }}

                </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">

                <div class="card-body">
                    <div class="table-responsive">
                        @if (isset($data))
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center">
                                <thead>
                                    <tr>

                                        <th class="tx-center">{{__('home.clietName')}}</th>
                                        <th class="tx-center"> {{__('home.tax_number')}} </th>

                                        <th class="tx-center">{{__('home.paymentmethod')}} </th>
                                        <th class="tx-center"> {{__('home.date')}} </th>
                                        <th class="tx-center"> {{__('home.Invoice_no')}}</th>



                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <?php

                                        $pay = '';
                                        if ($data['invoiceData']->Pay == "Cash") {
                                            $pay = __('report.cash');
                                        } elseif ($data['invoiceData']->Pay == "Shabka") {
                                            $pay = __('report.shabka');
                                        } elseif ($data['invoiceData']->Pay == "Credit") {
                                            $pay = __('report.credit');
                                        } elseif ($data['invoiceData']->Pay == "Bank_transfer") {
                                            $pay = __('home.Bank_transfer');
                                        } else {
                                            $pay = __('home.Partition of the amount');
                                        }
                                        ?>

                                        <td class="tx-12">{{$data['invoiceData']->customer->name}}</td>
                                        <td class="tx-12">{{$data['invoiceData']->customer->tax_no}}</td>
                                        <td class="tx-center">{{$pay}}</td>

                                        <td class="tx-center">{{ $data['invoiceData']->created_at}}</td>
                                        <td class="tx-center">{{ $data['invoiceData']->id}}</td>

                                    </tr>



                                </tbody>
                            </table>
                        </div>

                        <div class="table mg-t-30 my-5">
                            <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center my-5">
                                <thead>
                                    <tr>
                                        <th class="wd-center">#</th>
                                        <th class="wd-center">{{__('home.productNo')}} </th>
                                        <th class="tx-center"> {{__('home.product')}} </th>
                                        <th class="tx-center"> {{__('home.productprice')}} </th>
                                        <th class="tx-center"> {{__('home.quantity')}} </th>
                                        <th class="tx-center"> {{ __('home.price')}}</th>
                                        <th class="tx-center"> {{ __('home.discount')}}</th>
                                        <th class="tx-center"> {{__('home.total')}}</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $totalprice = 0;
                                    $totalAddedValue = 0;
                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSale = $avtSaleRate->AVT;
                                    $discountoninvoice=0;
                                    ?>

                                    @foreach ($data['salesData'] as $product)
                                    <?php $i++;
                                    $totalprice += ($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue-$product->discountoninvoice;
                                    ?>

                                    <tr>
                                        <td class="wd-20p">{{$i}}</td>
                                        <td class="wd-center" dir="ltr">{{$product->productData->Product_Code}}</td>
                                        <td class="tx-center">{{ $product->productData->product_name}}</td>
                                        <td class="tx-center">{{ $product->return_Unit_Price}}</td>
                                        <td class="tx-center">{{ $product->return_quantity}}</td>
                                        <td class="tx-center">{{ $product->return_Unit_Price*$product->return_quantity}}</td>
                                        <td class="tx-center">{{ $product->discountvalue}}</td>
                                        <td class="tx-center">{{ ($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue}}</td>

                                    </tr>
                                    @endforeach



                                </tbody>
                            </table>
                            <div class="table-responsive mg-t-20  float-left mt-3 mr-2 table-padding text-center">
                                <table class="table table-invoice table-bordered table-striped">
                                  

                                    <body>
                                        <tr>

                                            <td class="tx-">{{__('home.total')}}</td>
                                            <td class="tx-">{{ round( $totalprice,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-">{{__('home.addedValue')}}</td>
                                            <td class="tx-">{{round( $totalprice*$avtSale,2)}}</td>
                                        </tr>


                                        <tr>
                                            <td> {{__('home.the amount')}}</td>
                                            <td>{{ round( ($totalprice*$avtSale)+ $totalprice,2)}}</td>
                                        </tr>
                                    </body>

                                </table>
                            </div>

                            @endif
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
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
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
    $(document).ready(function() {

        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
</script>

@endsection