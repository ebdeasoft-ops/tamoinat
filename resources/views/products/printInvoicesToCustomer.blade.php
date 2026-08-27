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
        /* border-style: solid; */

    }

    .page-before {
        page-break-before: always;
    }

    .page {
        page-break-after: always;
    }
</style>
@endsection
@section('title')
معاينه طباعة الفاتورة
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
                    <div class="row mg-t-12">

                        <!-- invoice-header -->
                        <div class="row mg-t-12">


                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <span>{{__('home.notesClient')}} : {{$data['invoiceData']->note}}</span>

                    <div class="table-responsive mg-t-40">
                        <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center">
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

                    <div class="table-responsive mg-t-30">
                        <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="wd-center">#</th>
                                    <th class="tx-center"> {{__('home.product')}} </th>
                                    <th class="tx-center"> {{__('home.productprice')}} </th>
                                    <th class="tx-center"> {{__('home.quantity')}} </th>
                                    <th class="tx-center"> {{ __('home.price')}}</th>
                                    <th class="tx-center"> {{__('home.discount')}} </th>
                                    <th class="tx-center"> {{__('home.total')}}</th>



                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                ?>

                                @foreach ($data['salesData'] as $product)
                                <?php $i++ ?>

                                <tr>
                                    <td class="wd-20p">{{$i}}</td>
                                    <td class="tx-center">{{ $product->productData->name}}</td>
                                    <td class="tx-center">{{ $product->Unit_Price}}</td>
                                    <td class="tx-center">{{ $product->quantity}}</td>
                                    <td class="tx-center">{{ $product->Unit_Price*$product->quantity}}</td>
                                    <td class="tx-center">{{ $product->Discount_Value}}</td>
                                    <td class="tx-center">{{ ($product->Unit_Price*$product->quantity)-$product->Discount_Value}}</td>

                                </tr>
                                @endforeach



                            </tbody>
                        </table>
                        <div class="table-responsive mg-t-20  float-left mt-3 mr-2 table-padding d-flex justify-content-around">
                            <table class="table invoice-table table-striped text-center">

                                <body>
                                    <tr>

                                        <td class="tx-">{{__('home.total')}}</td>
                                        <td class="tx-">{{round($data['invoicetotal_price'],1)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-"> {{__('home.addedValue')}} </td>
                                        <td class="tx-">{{round($data['invoicetotal_addedvalue'],1)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-">{{ __('home.discount') }} </td>
                                        <td class="tx-">{{round($data['invoicetotal_discount'],1)}}</td>
                                    </tr>

                                    <tr>
                                        <td class="tx-">{{__('home.the amount')}}</td>
                                        <td class="tx-">{{round($data['invoicetotal_addedvalue']+$data['invoicetotal_price'],1)}}</td>
                                    </tr>
                                </body>

                            </table>

                            <div class="card-body">
                                <?php
                                $avtSaleRate = App\Models\Avt::find(1);
                                $avtSaleRate = $avtSaleRate->AVT;

                                function ConvertToHEX($value)
                                {
                                    return pack("H*", sprintf("%02X", $value));
                                }

                                $sellerName =  __('home.cam_name_owner');
                                $varNumber = __('home.TaxNumberpure');
                                $time = \Carbon\Carbon::now()->addHours(3);

                                $total = (round($data['invoicetotal_addedvalue'] + $data['invoicetotal_price'], 2));
                                $tax = round($data['invoicetotal_addedvalue'], 2);
                                $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
                                $seller  =  $HexSeller . $sellerName;
                                $HexVAT  = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
                                $vat  = $HexVAT . $varNumber;
                                $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($time));
                                $time  = $HexTime . $time;
                                $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($total));
                                $total  = $HexTotal . $total;
                                $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax));
                                $VATN  = $HexVATN . $tax;

                                $tobase   = $seller . $vat . $time . $total . $VATN;
                                $dataforQRcode =  base64_encode($tobase);
                                ?>
                                {!! QrCode::size(130)->generate( $dataforQRcode) !!}
                            </div>
                            <br>
                            <br> <br>
                            <br> <br>
                            <br>
                           
                        </div>
                        <div class="col">
                        <br>
                                <br> <br>
                                <br>
                                <span style="font-size:20px">القطع الكهربائية لاترد ولا تستبدل </span>
                                <span style="font-size:20px">Electrical parts are not returned or exchanged</span>
                                <br>
                                <br>
                                <span>&nbsp;&nbsp;&nbsp;{{addressar}}</span>
                                <br>
                                <span>&nbsp;&nbsp;&nbsp;{{addressen}}</span>

                            </div>
                    </div>
                    <hr class="mg-b-40">
                    <div>
                        <div class="page-before"></div>

                        <div class=" table-responsive mg-t-30">
                            <br>

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
    <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/Volvo.png') }}" class="logo-1" alt="logo"></a>
    <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logoprintpage.png') }}" class="logo-1" alt="logo"></a>
    &nbsp;
    &nbsp;
    &nbsp;
    &nbsp;
    &nbsp;
    &nbsp;

    <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/benz.png') }}" class="logo-1" alt="logo"></a>

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
                            <div class="table-responsive mg-t-40">
                                <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center">
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
                            <br>
                            <br> <br>
                            <br> <br>
                            <br>
                            <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="wd-center">#</th>
                                        <th class="wd-center">{{__('home.productNo')}} </th>
                                        <th class="wd-center">{{__('home.branch')}} </th>
                                        <th class="wd-center">{{__('home.productlocation')}} </th>
                                        <th class="tx-center"> {{__('home.product')}} </th>
                                        <th class="tx-center"> {{__('home.quantity')}} </th>




                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    ?>

                                    @foreach ($data['salesData'] as $product)
                                    @if($product->quantity!=0)

                                    @if(Auth()->user()->branchs_id!=$product->productData->branchs_id)
                                    <?php $i++ ?>
                                    <tr>
                                        <td class="wd-20p">{{$i}}</td>
                                        <td class="wd-center" dir="ltr">{{$product->productData->name}}</td>
                                        <td class="wd-center">{{$product->productData->branch->name}}</td>
                                        <td class="wd-center">{{$product->productData->Product_Location}}</td>
                                        <td class="tx-center">{{ $product->productData->product_name}}</td>
                                        <td class="tx-center">{{ $product->quantity}}</td>

                                    </tr>
                                    @endif
                                    @endif
                                    @endforeach



                                </tbody>
                            </table>


                        </div>
                    </div>

                    <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                        {{__('home.print')}}
                        <i class="mdi mdi-printer ml-1"></i>
                    </button>
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
    $(document).ready(function() {

var printContents = document.getElementById('print').innerHTML;
var originalContents = document.body.innerHTML;
document.body.innerHTML = printContents;
setTimeout(() => {
    window.print();
}, 500);

setTimeout(() => {
    window.close();
}, 12000);

})
</script>

@endsection