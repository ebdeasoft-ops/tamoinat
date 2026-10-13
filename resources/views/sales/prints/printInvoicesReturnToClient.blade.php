@extends('layouts.master')
@section('css')
<style>
    /* ==========================================================================
       معاينة طباعة الفاتورة — شاشة أنيقة + طباعة نظيفة موفّرة للحبر
       ========================================================================== */
    :root {
        --inv-navy: #1b3358;
        --inv-navy-light: #23395D;
        --inv-border: #d9dee6;
        --inv-bg-soft: #f8fafc;
        --inv-radius: 12px;
        --inv-shadow: 0 8px 24px rgba(16, 24, 40, .08);
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
    }

    /* ---------- شكل الشاشة (لا يظهر عند الطباعة) ---------- */
    .card-invoice {
        border: 1px solid var(--inv-border) !important;
        border-radius: var(--inv-radius) !important;
        box-shadow: var(--inv-shadow) !important;
        overflow: hidden;
    }

    .invoice-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: linear-gradient(180deg, #fbfcfe 0%, #ffffff 100%);
        border-bottom: 2px solid var(--inv-navy);
        padding-bottom: 16px;
        margin-bottom: 10px;
    }

    .invoice-header .billed-from {
        text-align: center;
        min-width: 220px;
    }

    .invoice-header .billed-from span[style*="25px"] {
        color: var(--inv-navy) !important;
        font-weight: 800;
    }

    .invoice-header .logo-1 {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(16, 24, 40, .12);
    }

    .invoice-table thead th,
    table.table thead th {
        background: linear-gradient(180deg, #f4f6fb 0%, #eef1f8 100%) !important;
        color: var(--inv-navy) !important;
        font-weight: 700 !important;
        border-color: var(--inv-border) !important;
    }

    .invoice-table td,
    table.table td {
        border-color: var(--inv-border) !important;
        vertical-align: middle;
    }

    .table-padding table.table-invoice td {
        padding: 10px 16px;
        font-weight: 600;
    }

    .table-padding table.table-invoice tr:last-child {
        background: var(--inv-navy) !important;
        color: #fff !important;
        font-weight: 800;
    }

    .table-padding table.table-invoice tr:last-child td {
        color: #fff !important;
        border-color: var(--inv-navy) !important;
    }

    .invoice-header + .table-responsive + .table-responsive .card,
    div.card:has(> span) {
        background: var(--inv-bg-soft);
        border: 1px dashed var(--inv-border);
        border-radius: var(--inv-radius);
        padding: 14px 18px;
        text-align: center;
        font-size: 11pt;
        color: #475569;
    }

    #print_Button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--inv-navy) 0%, var(--inv-navy-light) 100%) !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 10px 22px !important;
        box-shadow: var(--inv-shadow);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    }

    #print_Button:hover {
        transform: translateY(-2px);
        filter: brightness(1.08);
    }

    /* ---------- شكل الطباعة (نظيف بدون ظلال/تدرجات لتوفير الحبر) ---------- */
    @media print {
        #print_Button {
            display: none;
        }

        body {
            border-style: solid;
        }

        .card-invoice {
            box-shadow: none !important;
            border: none !important;
        }

        .invoice-header {
            background: none !important;
        }

        .invoice-table thead th,
        table.table thead th {
            background: #f1f1f1 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table-padding table.table-invoice tr:last-child,
        .table-padding table.table-invoice tr:last-child td {
            background: #eee !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
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
                    <div class="table-responsive mg-t-40">
                        <table style="border:2px solid rgba(0,0,0,.3)" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
                                <tr>

                                    <th class="tx-center">{{__('home.clietName')}}</th>
                                    <th class="tx-center"> {{__('home.tax_number')}} </th>

                                    <th class="tx-center">{{__('home.paymentmethod')}} </th>
                                    <th class="tx-center">{{__('home.branch')}} </th>
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

                                    <td class="tx-center">{{ $data['invoiceData']->branch->name}}</td>
                                    <td class="tx-center">{{ $data['invoiceData']->created_at}}</td>
                                    <td class="tx-center">{{ $data['invoiceData']->id}}</td>

                                </tr>



                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mg-t-30 mb-5">
                        <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center mb-5">
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
                                    <td class="wd-10p">{{$i}}</td>
                                    <td class="tx-center">{{ $product->productData->product_name}}</td>
                                    <td class="tx-center">{{ $product->Unit_Price}}</td>
                                    <td class="tx-center">{{ $product->quantity}}</td>
                                    <td class="tx-center">{{ $product->Unit_Price*$product->quantity}}</td>
                                    <td class="tx-center">{{ $product->Discount_Value}}</td>
                                    <td class="tx-center">{{ ($product->Unit_Price*$product->quantity)-$product->Discount_Value}}</td>

                                </tr>
                                @endforeach



                            </tbody>
                        </table>
                        <div class="row">
                            <div class="table-responsive mg-t-20 mt-3 mr-2 d-flex justify-content-around table-padding">
                                <table style="border: 2px solid rgba(0,0,0,.3);" class="table table-invoice table-striped text-center">

                                    <tbody>
                                        <tr>

                                            <td class="tx-">{{__('home.total')}}</td>
                                            <td class="tx-">{{round($data['invoicetotal_price'],2)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-"> {{__('home.addedValue')}} </td>
                                            <td class="tx-">{{round($data['invoicetotal_addedvalue'],2)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-">{{ __('home.discount') }} </td>
                                            <td class="tx-">{{round($data['invoicetotal_discount'],2)}}</td>
                                        </tr>

                                        <tr>
                                            <td class="tx-">{{__('home.the amount')}}</td>
                                            <td class="tx-">{{round($data['invoicetotal_addedvalue']+$data['invoicetotal_price'],2)}}</td>
                                        </tr>
                                    </tbody>

                                </table>
                                <div class="card-body">
                                    <?php

                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSaleRate = $avtSaleRate->AVT;

                                    function ConvertToHEX($value)
                                    {
                                        return pack("H*", sprintf("%02X", $value));
                                    }
                                    $sellerName = sallerQrCode;
                                    $varNumber =TaxQrCode;
                                    $time = \Carbon\Carbon::now()->addHours(3);

                                    $total = ($data['invoicetotal_price'] + $data['invoicetotal_addedvalue']);
                                    $tax =  $data['invoicetotal_addedvalue'];
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
                            </div>
                            <div class="card">
                                &nbsp;&nbsp; <span>القطع الكهربائية لاترد ولا تستبدل </span>
                                &nbsp;&nbsp; <span>Electrical parts are not returned or exchanged</span>
                                <br>
                                <br>
                                <span>{{addressar}}</span>
                                <br>
                                <span>{{addressen}}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="mg-b-40">



                    <button class="btn btn-danger float-left mt-3 mr-2 print-style" id="print_Button" onclick="printDiv()"> <i class="mdi mdi-printer ml-1"></i>طباعة</button>
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