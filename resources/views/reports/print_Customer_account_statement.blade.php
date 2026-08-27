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
                    <br>
                    <div class="col-lg-3" id="start_at">
                        <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                        <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                        ?>
                        <label style="font-size: 12px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label><br>

                        <br>
                    </div>
                    @if (isset($data))
                    <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-padding">

                            <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                <thead>

                                    <tr>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('report.from') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $start_at}}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('report.to') }}</th>
                                        <th style="font-size: 13px;color:#419BB2"> {{ $end_at }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('home.clietName') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $customerName}}</th>
                                    </tr>

                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">

                        <br>

                        <div>
                            <table class="table table-hover table-bordered table-striped text-center my-3" id="example1" data-page-length='50' style=" text-align: center;">
                                <thead>
                                    <tr>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.sallerName') }} </th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Debit balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 0;
                                    $totaldiscount = 0;
                                    $totalPriceDay = 0;

                                    $listId = [];
                                    $debitblance = 0;

                                    ?>

                                    @foreach ($data[0] as $product)

                                    @if($product->Price!=0)
                                    <?php
                                    $totaldiscount += $product->discount;

                                    $i = 0;

                                    $count++;
                                    $avt = App\Models\Avt::find(1);
                                    $saleavt = $avt->AVT;
                                    $totalPriceDay += round(($product->Price - $product->discount) + (($product->Price - $product->discount) * $saleavt), 2);

                                    ?>
                                    <?php $i++; ?>
                                    @foreach($data[1] as $invoice)
                                    @if( $product->created_at>=$invoice->created_at&&!in_array($invoice->id,$listId))
                                    <?php
                                    $listId[] = $invoice->id;
                                    $debitblance=$invoice->currentblance;
                                    ?>
                                    <tr>

                                        <th style="color:#419BB2">{{ __('home.decoumentNo') }}</th>
                                        <th class="border-bottom-0"> {{ __('home.clientname') }}</th>
                                        <th class="border-bottom-0"> {{ __('home.date') }}</th>
                                        <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.cashreceived') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.Remainingamount') }}</th>
                                        <th style="color:#419BB2">__</th>

                                    </tr>



                                    <tr>
                                        <td><span style="color:green;font-size:16px">{{ $invoice->id }}</span></td>

                                        <td>{{ $invoice->customer->name }}</td>
                                        <td>{{$invoice->created_at }}</td>
                                        <td>
                                            @if ($invoice->pay_method == 'Cash')
                                            <span class="text-success">{{ __('report.cash') }}</span>
                                            @elseif($invoice->pay_method =="Bank_transfer")
                                            <span class="text-success">{{ __('home.Bank_transfer') }}</span>

                                            @else

                                            <span class="text-warning">{{ __('report.shabka') }}</span>
                                            @endif
                                        </td>
                                        <td><span style="color:green;font-size:16px">{{ $invoice->recive_amount  }}</span></td>
                                        <td><span style="color:red;font-size:16px">{{ $invoice->currentblance }}</span></td>
                                        <td><span style="color:red;font-size:16px">{{ $invoice->currentblance }}</span></td>


                                    </tr>



                                    @endif
                                    @endforeach


                                    <tr id="<?php echo $product['id']; ?>">
                                        <td data-target="id">{{ $product->id }}</td>
                                        <td data-target="id">{{ $product->user->name }}</td>

                                        <td data-target="numberofpice">{{ $product->created_at }}</td>
                                        <td data-target="numberofpice">{{ $product->branch->name }}
                                        </td>
                                        <?php
                                        $pays = '';
                                        if ($product->Pay == 'Cash') {
                                            $pays = __('report.cash');
                                        } elseif ($product->Pay == 'Shabka') {
                                            $pays = __('report.shabka');
                                        } elseif ($product->Pay == "Credit") {
                                            $pays = __('report.credit');
                                        } elseif ($product->Pay == "Bank_transfer") {
                                            $pays = __('home.Bank_transfer');
                                        } else {
                                            $pays = __('home.Partition of the amount');
                                        }

                                        ?>
                                        <td data-target="numberofpice">{{ $pays }}</td>
                                        <td data-target="numberofpice">
                                            <?php
                                            $avt = App\Models\Avt::find(1);
                                            $saleavt = $avt->AVT;
                                            $debitblance+=round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2);
                                            ?>
                                            {{ round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2) }}
                                        </td>

                                        <td><span style="color:red;font-size:16px">{{ $debitblance }}</span></td>


                                    </tr>
                                    @endif
                                    @endforeach
                                    @foreach($data[1] as $invoice)
                                    @if( !in_array($invoice->id,$listId))
                                    <?php
                                    $listId[] = $invoice->id;
                                    $debitblance=$invoice->currentblance;
                                    ?>
                                    <tr>

                                        <th style="color:#419BB2">{{ __('home.decoumentNo') }}</th>
                                        <th class="border-bottom-0"> {{ __('home.clientname') }}</th>
                                        <th class="border-bottom-0"> {{ __('home.date') }}</th>
                                        <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.cashreceived') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.Remainingamount') }}</th>
                                        <th style="color:#419BB2">__</th>

                                    </tr>



                                    <tr>
                                        <td><span style="color:green;font-size:16px">{{ $invoice->id }}</span></td>

                                        <td>{{ $invoice->customer->name }}</td>
                                        <td>{{$invoice->created_at }}</td>
                                        <td>
                                            @if ($invoice->pay_method == 'Cash')
                                            <span class="text-success">{{ __('report.cash') }}</span>
                                            @elseif($invoice->pay_method =="Bank_transfer")
                                            <span class="text-success">{{ __('home.Bank_transfer') }}</span>

                                            @else

                                            <span class="text-warning">{{ __('report.shabka') }}</span>
                                            @endif
                                        </td>
                                        <td><span style="color:green;font-size:16px">{{ $invoice->recive_amount  }}</span></td>
                                        <td><span style="color:red;font-size:16px">{{ $invoice->currentblance }}</span></td>
                                        <td><span style="color:red;font-size:16px">{{ $invoice->currentblance }}</span></td>


                                    </tr>



                                    @endif
                                    @endforeach
                                    <tr>
                                    <th>-</th>
                                    <th>-</th>
                                    <th>-</th>
                                    <th>-</th>
                                            <th>{{ __('home.total') }}</th>
                                            <th>{{ $totalPriceDay}}</th>
                                            <td><span style="color:red;font-size:16px">{{ $debitblance }}</span></td>

                                        </tr>
                                </tbody>
                            </table>

                            <br>
                            
                            <br>

                            <?php
                            $customer = App\Models\customers::find($customerId);
                            ?>
                            @endif
                            <br>

                            <div class="table-padding">

                                <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                    <thead>

                                        <tr>
                                            <th>{{ __('home.theamountreciet') }}</th>
                                            <th style="color:red;font-size:16px">{{ $customer->Balance}}</th>
                                        </tr>

                                    </thead>
                                </table>
                            </div>
                            <br>
                        </div>
                        <hr class="mg-b-40">



                        <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                            </button>
                        </div>



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