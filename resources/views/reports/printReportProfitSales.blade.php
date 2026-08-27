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


                    <div class="card-body">
                        <div class="">
                            @if (isset($Invoices))
                            <div class="col-lg-3" id="start_at">
                                <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                <?php
                                $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                ?>
                                <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                            </div>
                            <div style="border-radius: 10px" class="card pb-0 px-3">
                                <br>

                                <?php
                                $count = 0;
                                $startat = '';
                                $endat = '';
                                $totalprofit = 0;
                                $benfitshabka = 0;
                                $benfitcradit = 0;
                                $benfitcash = 0;
                                $benfitBank_transfer = 0;

                                ?>
                                @foreach ($Invoices as $invoice)
                                <?php

                                if ($count == 0) {
                                    $startat = $invoice->created_at;
                                }
                                $endat = $invoice->created_at;
                                $count++;
                                ?>

                                <br>





                                <table class="table table-responsive table-striped table-bordered text-center">
                                    <thead>

                                        <tr>
                                            <th>
                                                {{ __('report.invoiceNo') }}
                                            </th>

                                            <th>{{ $invoice->id }}</th>

                                        </tr>

                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ __('report.date') }}</th>

                                            <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.saleprice') }}</th>

                                            <th class="border-bottom-0">{{ __('home.saleperpice') }}</th>
                                            <th class="border-bottom-0">{{ __('home.discount') }}</th>

                                            <th class="border-bottom-0"> {{ __('report.profit') }}</th>
                                            <th class="border-bottom-0"> <span class="text-warning  float-left mt-3 mr-2" id="print_Button">{{ __('home.total') }}</span>
                                            </th>

                                        </tr>

                                    </thead>
                                    <?php
                                    $i = 0;
                                    $profit = 0;

                                    ?>
                                    @foreach (App\Models\sales::where('invoice_id', $invoice->id)->where('quantity', '!=', 0)->get() as $product)
                                    <?php
                                    $i++;
                                    $totalprofit += (($product->quantity * $product->Unit_Price) - $product->Discount_Value) - ($product->quantity * $product->productData->purchasingـprice);
                                    $profit += (($product->quantity * $product->Unit_Price) - $product->Discount_Value) - ($product->quantity * $product->productData->purchasingـprice);
                                    $date = explode(' ', $product->created_at);
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $date[0] }}</td>

                                            <td dir='ltr'>{{ $product->productData->Product_Code }}</td>
                                            <td>{{ $product->productData->product_name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->productData->purchasingـprice }}</td>
                                            <td>{{ $product->Unit_Price }}</td>
                                            <td>{{ $product->Discount_Value }}</td>
                                            <td>{{ (($product->quantity * $product->Unit_Price)-$product->Discount_Value) - ($product->quantity * $product->productData->purchasingـprice) }}
                                            </td>
                                            <td>-</td>
                                        </tr>

                                        @endforeach
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>

                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>

                                            <td>-</td>
                                            <td>{{$invoice->discount}}</td>
                                            <td>-</td>
                                            <td>{{ $profit-($invoice->discount-$invoice->discountOnProduct) }}

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>


                                <?php
                                $titalbenfitmix = 0;
                                $ratecash = 0;
                                $rateshabka = 0;
                                $ratecredit = 0;
                                $rateBank_transfer=0;

                                if ($product->Pay == 'Cash') {

                                    foreach (App\models\sales::where('invoice_id', $invoice->id)->where('save', 1)->get() as $salesbenfit) {

                                        $benfitcash += (($salesbenfit->Unit_Price * $salesbenfit->quantity)) - ($salesbenfit->productData->purchasingـprice * $salesbenfit->quantity);
                                    }
                                    $benfitcash -= ($invoice->discount);
                                } elseif ($product->Pay == 'Credit') {
                                    foreach (App\models\sales::where('invoice_id', $invoice->id)->where('save', 1)->get() as $salesbenfit) {

                                        $benfitcradit += (($salesbenfit->Unit_Price * $salesbenfit->quantity)) - ($salesbenfit->productData->purchasingـprice * $salesbenfit->quantity);
                                    }
                                    $benfitcradit -= $invoice->discount;
                                } elseif ($product->Pay == 'Bank_transfer') {
                                    foreach (App\models\sales::where('invoice_id', $product->id)->where('save', 1)->get() as $salesbenfit) {

                                        $Bank_transfer += (($salesbenfit->Unit_Price * $salesbenfit->quantity)) - ($salesbenfit->productData->purchasingـprice * $salesbenfit->quantity);
                                    }
                                    $benfitcradit -= $product->discount;
                                } elseif ($invoice->Pay == 'Shabka') {
                                    foreach (App\models\sales::where('invoice_id', $invoice->id)->where('save', 1)->get() as $salesbenfit) {

                                        $benfitshabka += (($salesbenfit->Unit_Price * $salesbenfit->quantity)) - ($salesbenfit->productData->purchasingـprice * $salesbenfit->quantity);
                                    }
                                    $benfitshabka -= $invoice->discount;
                                } else {
                                    foreach (App\models\sales::where('invoice_id', $invoice->id)->where('save', 1)->get() as $salesbenfit) {

                                        $titalbenfitmix += (($salesbenfit->Unit_Price * $salesbenfit->quantity)) - ($salesbenfit->productData->purchasingـprice * $salesbenfit->quantity);
                                    }
                                    $titalbenfitmix -= $invoice->discount;
                                    // return $titalbenfitmix;
                                    $ratecash = ($invoice->cashamount / ($invoice->cashamount + $invoice->bankamount + $invoice->creaditamount));
                                    $rateshabka = ($invoice->bankamount / ($invoice->cashamount + $invoice->bankamount + $invoice->creaditamount));
                                    $ratebank = ($invoice->Bank_transfer / ($invoice->cashamount + $invoice->bankamount + $invoice->creaditamount + $invoice->Bank_transfer));
                                    $ratecredit = ($invoice->creaditamount / ($invoice->cashamount + $invoice->bankamount + $invoice->creaditamount));
                                    $benfitBank_transfer +=  $titalbenfitmix * $ratebank;
                                    $benfitshabka += $titalbenfitmix * $rateshabka;
                                    $benfitcradit += $titalbenfitmix * $ratecredit;
                                    $benfitcash += $titalbenfitmix * $ratecash;
                                }                          ?>
                                @endforeach


                                <br>
                                <br>

                                <div class="table-padding">
                                <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.benfitcash') }}
                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round( $benfitcash,2) }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ __('home.benfitshabka') }}</th>
                                            <th>{{ round( $benfitshabka,2) }}</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.benfitcradit') }}</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round($benfitcradit,2)   }}</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.Bank_transfer') }}</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round($benfitBank_transfer,2)   }}</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.total') }}</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round($benfitcradit+round( $benfitcash ,2) +$benfitshabka +$benfitBank_transfer,2)  }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>






                                @endif

                            </div>
                            <hr class="mg-b-40">



                            <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                            </button>

                        </div>
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