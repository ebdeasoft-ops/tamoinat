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

                    <br>
                    <br><!-- invoice-header -->



                    @if (isset($data['invoices']))
                    <div class="card-body">
                        <div class="px-2">
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
                            @foreach ($data['invoices'] as $invoice)
                            <?php
                            $totaladdedvalue += $invoice->Added_Value;
                            $totalprice += $invoice->Price;
                            if ($count == 0) {
                                $userId = $invoice->user_id;
                                $startat = $invoice->created_at;
                            }
                            $endat = $invoice->created_at;
                            $count++;

                            ?>

                            <table class="table table-bordered  table-striped text-center my-5">

                                <thead>
                                    <tr>
                                        <th>{{ __('report.invoiceNo') }}</th>
                                        <th>{{ $invoice->id }}</th>
                                        @if ($data['salesreport'] == 'yes')
                                        <th></th><span class="text-danger">{{ __('home.paymentmethod') }}</th>
                                            <th>
                                                @if ($invoice->Pay == 'Cash')
                                                <span class="text-success">{{ __('report.cash') }}</span>
                                                @elseif($invoice->Pay == 'Credit')
                                                <span class="text-danger">{{ __('report.credit') }}</span>
                                                @elseif($invoice->Pay == "Bank_transfer")
                                                <span class="text-warning">{{ __('home.Bank_transfer') }}</span>

                                                @elseif($invoice->Pay == "Partition")
                                                <span class="text-warning">{{ __('home.Partition of the amount') }}</span>

                                                @else

                                                <span class="text-warning">{{ __('report.shabka') }}</span>
                                                @endif


                                        </span></th>
                                        @endif
                                        <th>{{ __('home.total') }}</th>
                                        <th>{{ $invoice->Added_Value + $invoice->Price }}</th>
                                    </tr>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ __('report.date') }}</th>

                                        <th class="border-bottom-0" dir='ltr'>
                                            {{ __('home.productNo') }}
                                        </th>
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
                                @foreach (App\Models\sales::where('invoice_id', $invoice->id)->get() as $product)
                                <?php
                                $i++;
                                $date = explode(' ', $product->created_at);
                                ?>
                                <tbody>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $date[0] }}</td>

                                        <td dir='ltr'>{{ $product->productData->Product_Code }}
                                        </td>
                                        <td>{{ $product->productData->product_name }}</td>
                                        <td>{{ $product->quantity }}</td>


                                        <td>{{ $product->Unit_Price }}</td>
                                        <td>{{ $product->Added_Value }}</td>
                                        <td>{{ $product->quantity * $product->Added_Value + $product->quantity * $product->Unit_Price }}
                                        </td>
                                    </tr>

                                </tbody>
                                @endforeach
                            </table>

                            <div class="my-4">
                                <hr style="border-top: 4px solid rgba(0,0,0,.3)">
                            </div>



                            @endforeach



                            <div class="table-padding mt-5">
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>{{ __('report.totalprice') }}</th>
                                            <th>{{ ('home.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('report.totalpricewithoudtax') }}</td>
                                            <td>{{ $totalprice }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('report.totaltax') }}</td>
                                            <td>{{ $totaladdedvalue }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('report.totalallprice') }}</td>
                                            <td>{{ $totaladdedvalue + $totalprice }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>



                            <br>


                            <br>
                            @endif
                            <hr class="mg-b-40">



                            <div class="d-flex justify-content-center">
                                <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                                    {{ __('home.print') }}
                                    <i class="mdi mdi-printer ml-1"></i>
                                </button>
                            </div>
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