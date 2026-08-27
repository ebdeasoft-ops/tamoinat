@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        border-style: solid;

    }
</style>
@endsection
@section('title')
{{ __('home.print') }}
@stop
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class=" main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">

                <div class="invoice-header">

<div class="billed-from">
    <br>
    &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; <span style="font-size:25px">{{Nameen}}</span>
    <br>
    <p dir=ltr> {{describtionen}} &nbsp;&nbsp;&nbsp;&nbsp;</p>
    <span dir=ltr>{{STen}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <p dir=ltr> {{Taxen}} </p>

</div>
<div class="row">
<?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

</div>


<div class="billed-from">
    <br>

    &nbsp; &nbsp; &nbsp; <span style="font-size:25px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->
                    @if (isset($Invoices))
                    <br>
                    <br>
                    <br>

                    <div class="col-lg-3" id="start_at">
                        <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                        <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                        ?>
                        <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                    </div>
                    <br>
                    <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-responsive">
                            <?php
                            $userId = 0;
                            $count = 0;
                            ?>
                            <?php
                            $userId = 0;
                            $startat = '';
                            $endat = '';
                            $totalprice = 0;
                            $totaladdedvalue = 0;
                            ?>
                            @foreach ($Invoices as $invoice)
                            <?php

                            if ($count == 0) {
                                $userId = $invoice->user_id;
                                $startat = $invoice->created_at;
                            }
                            $endat = $invoice->created_at;
                            $count++;

                            ?>

                            <br>


                            {{--
                                    <span class="text-danger">{{ __('report.invoiceNo') }} : {{ $invoice->orderId }}
                            </span>
                            <br>
                            <span class="text-danger">{{ __('home.paymentmethod') }} : </span>
                            @if ($invoice->Pay == 'Cash')
                            {{ __('report.cash') }}
                            @elseif($invoice->Pay == 'Shabka')
                            {{ __('report.shabka') }}
                            @elseif($invoice->Pay == 'Bank_transfer')
                            {{ __('home.Bank_transfer') }}
                            @else
                            {{ __('report.credit') }}
                            @endif

                            <br>
                            <span class="text-danger">{{ __('home.suppliername') }} :
                                {{ $invoice->supllier->name }}</span> --}}







                            <div class="table-responsive hoverable-table">
                                <table class="table table-hover text-center table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="color:#419BB2"> {{__('home.Invoice_no')}}</th>
                                            <th style="color:#419BB2">{{ $invoice->orderId }}</th>
                                            <th style="color:#419BB2">{{ __('home.paymentmethod') }}</th>
                                            <th style="color: #419BB2">
                                                @if ($invoice->Pay == 'Cash')
                                                {{ __('report.cash') }}
                                                @elseif($invoice->Pay == 'Shabka')
                                                {{ __('report.shabka') }}
                                                @elseif($invoice->Pay == 'Bank_transfer')
                                                {{ __('home.Bank_transfer') }}
                                                @else
                                                {{ __('report.credit') }}
                                                @endif
                                            </th>
                                            <th style="font-size: 13px !important ;color:#419BB2">{{ __('home.suppliername') }}</th>
                                            <th style="font-size: 13px !important ;color:#419BB2" colspan="3">{{ $invoice->supllier->name }}</th>
                                        </tr>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ __('report.date') }}</th>

                                            <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.quantity') }}</th>

                                            <th class="border-bottom-0">{{ __('home.price') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.addedValue') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.total') }}</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 0;

                                    ?>
                                    @foreach (App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('returns_purchase', '!=', 0)->get() as $product)
                                    <?php
                                    $i++;
                                    $totalprice += $product->returns_purchase * $product->purchasingـprice;

                                    $totaladdedvalue += $product->returns_purchase * $product->Added_Value;
                                    $date = explode(' ', $product->created_at);
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $date[0] }}</td>

                                            <td dir=ltr>{{ $product->productData->barcode }}</td>
                                            <td>{{ $product->productData->name }}</td>
                                            <td>{{ $product->returns_purchase }}</td>


                                            <td>{{ $product->purchasingـprice }}</td>
                                            <td>{{ $product->Added_Value }}</td>
                                            <td>{{ $product->returns_purchase * $product->Added_Value + $product->returns_purchase * $product->purchasingـprice }}
                                            </td>
                                        </tr>

                                    </tbody>
                                    @endforeach
                                </table>


                                <div class="my-5">
                                    <hr style="border-top: 4px solid rgba(0,0,0,.3)">
                                </div>


                                @endforeach


                                <div class="table-padding">
                                    <table class="table table-bordered table-hover text-center table-striped mt-5">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">{{ __('report.totalprice') }}</th>
                                                <th>{{ __('home.the amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>{{ __('report.totalpricewithoudtax') }}</td>
                                                <td>{{ $totalprice }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>{{ __('report.totaltax') }}</td>
                                                <td> {{ $totaladdedvalue }}</td>
                                            </tr>
                                            <?php
                                            $discount = 0;
                                            $x = App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('numberofpice', '!=', 0)->get();
                                            $count = count($x);
                                            ?>
                                            @if(!$count)
                                            <?php
                                            $discount = $invoice->discount;
                                            ?>
                                            <tr>

                                                <th scope="row">2</th>
                                                <td>{{ __('home.discount') }}</td>
                                                <td> {{ $invoice->discount }}</td>

                                            </tr>
                                            @endif
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>{{ __('report.totalallprice') }}</td>
                                                <td>{{ $totaladdedvalue + $totalprice-$discount }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>



                                <br>

                                <br>
                                @endif
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-danger print-style float-left mt-10 mr-10 p-1" id="print_Button" onclick="printDiv()">
                                        {{ __('home.print') }}
                                        <i class="mdi mdi-printer ml-1"></i>
                                    </button>
                                    <br>

                                </div>
                                <br>


                            </div>
                        </div>
                    </div>
                </div><!-- COL-END -->
            </div>
            <!-- row closed -->
        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->
    @endsection
    @section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>


    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>

    @endsection