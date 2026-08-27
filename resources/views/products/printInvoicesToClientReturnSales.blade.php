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

    /* ==========================================================================
       إشعار دائن / مرتجع مبيعات — تصميم احترافي (شاشة أنيقة + طباعة نظيفة)
       ========================================================================== */
    :root {
        --nc-navy: #1b3358;
        --nc-navy-light: #23395D;
        --nc-border: #d9dee6;
        --nc-bg-soft: #f8fafc;
        --nc-radius: 12px;
        --nc-shadow: 0 8px 24px rgba(16, 24, 40, .08);
    }

    .card-invoice {
        border: 1px solid var(--nc-border) !important;
        border-radius: var(--nc-radius) !important;
        box-shadow: var(--nc-shadow) !important;
    }

    .invoice-header {
        border-bottom: 2px solid var(--nc-navy);
        padding-bottom: 12px;
    }

    .invoice-header .billed-from span {
        color: var(--nc-navy) !important;
        font-weight: 800 !important;
    }

    .invoice-header .logo-1 {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(16, 24, 40, .12);
    }

    .nc-doc-type {
        text-align: center;
        margin: 6px 0 18px 0;
    }

    .nc-doc-type-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--nc-navy) 0%, var(--nc-navy-light) 100%);
        color: #fff;
        font-weight: 700;
        padding: 6px 22px;
        border-radius: 999px;
        font-size: 13px;
        letter-spacing: .3px;
        box-shadow: var(--nc-shadow);
    }

    .nc-doc-type-badge + .nc-doc-type-badge {
        margin-top: 6px;
    }

    .invoice-table thead th,
    .invoice-table tbody th,
    table.table thead th,
    table.table tbody th {
        border-color: var(--nc-border) !important;
    }

    .invoice-table thead tr:first-child th,
    table.table.invoice-table > thead > tr > th.wd-center,
    table.table.invoice-table > thead > tr > th.tx-center {
        background: linear-gradient(180deg, #f4f6fb 0%, #eef1f8 100%) !important;
        color: var(--nc-navy) !important;
        font-weight: 700 !important;
    }

    /* جدولا العميل وطريقة الدفع */
    .row > table.invoice-table th:first-child {
        background: var(--nc-bg-soft) !important;
        color: var(--nc-navy) !important;
        font-weight: 700 !important;
        text-align: right;
    }

    .row > table.invoice-table th:last-child {
        font-weight: 600 !important;
        color: #1f2937 !important;
    }

    /* جدول الإجماليات */
    .table-padding table.table-invoice tr:last-child th,
    .table-padding table.table-invoice tr:last-child td {
        background: var(--nc-navy) !important;
        color: #fff !important;
        border-color: var(--nc-navy) !important;
        font-weight: 800 !important;
    }

    #print_Button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--nc-navy) 0%, var(--nc-navy-light) 100%) !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 10px 22px !important;
        box-shadow: var(--nc-shadow);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    }

    #print_Button:hover {
        transform: translateY(-2px);
        filter: brightness(1.08);
    }

    /* ---------- طباعة أبيض/أسود بسيطة: بدون أي إطارات أو صناديق إضافية،
       فقط ألوان النص أسود وخط عريض للعناوين والإجمالي ---------- */
    @media print {
        * {
            color: #000 !important;
            text-shadow: none !important;
        }

        .card-invoice {
            box-shadow: none !important;
        }

        .invoice-header .billed-from span {
            color: #000 !important;
            font-weight: 800 !important;
        }

        .invoice-header .logo-1 {
            box-shadow: none !important;
        }

        .nc-doc-type-badge {
            background: none !important;
            color: #000 !important;
            box-shadow: none !important;
            border: none !important;
            font-weight: 800 !important;
        }

        .invoice-table thead tr:first-child th,
        table.table.invoice-table > thead > tr > th.wd-center,
        table.table.invoice-table > thead > tr > th.tx-center,
        .row > table.invoice-table th:first-child {
            background: none !important;
            font-weight: 800 !important;
        }

        .table-padding table.table-invoice tr:last-child th,
        .table-padding table.table-invoice tr:last-child td {
            background: none !important;
            font-weight: 800 !important;
        }

        #print_Button {
            display: none !important;
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
                                                        <button class="btn btn-danger float-left mt-3 mr-2 print-style" id="print_Button" onclick="printDiv()"> <i class="mdi mdi-printer ml-1"></i>طباعة</button>



                                           <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">




  <div class="billed-from" style="width:33%;text-align: center;" >
                            <br>
                             <span style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p dir=ltr style="font-size:11px"> {{describtionen}} </p>
                            <span dir=ltr>{{STen}} </span>
                            <p dir=ltr> {{Taxen}} </p>

                        </div>


                        <div class="row">
                        <?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 130px; height: 100px;"></a>

                        </div>



                             <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                           <span style="font-size:16px">{{Namear}}</span>
                            <br>
                            <p> {{describtionar}}</p>
                            <p>{{STar}}</p>
                            <p>{{Taxar}}</p>

                        </div><!-- billed-from -->

                    </div><!-- invoice-header -->
                    <br>
                    <br>
                <div class="nc-doc-type">
                    <span class="nc-doc-type-badge">Sales returns &nbsp;&nbsp; مرتجع المبيعات</span>
                    <br>
                    <span class="nc-doc-type-badge">Notice creditor &nbsp;&nbsp; اشعار دائن</span>
                </div>

                    <br>
                 <div class='row' style="justify-content: space-between;">
                        <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
                                <tr>

<th class="tx-center">CLIENT NAME -اسم العميل  </th>
 <th class="tx-10">{{$data['invoiceData']->customer->name}}</th>



                                </tr>

                                 <tr>
                                    <th class="tx-15">TAX NUMBER-
                                    الرقم الضريبي </th>

                                    <th class="tx-15">{{$data['invoiceData']->customer->tax_no}}</th>
                                                                    </tr>
                                <tr>

                                      <th class="tx-15"> CLIENT ADDRESS-عنوان العميل </th>

                                    <th class="tx-15">{{$data['invoiceData']->customer->address}}</th>

                                </tr>
                            </thead>

                        </table>

                        <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
                                <tr>


  <th class="tx-center">PAYMENT METHOD-طريقة الدفع </th

  >
      @foreach ($data['salesData'] as $product)
                                    <?php
                                    $created_at=$product->created_at;
                                  ?>
@endforeach

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

                                    <th class="tx-center">{{$pay}}</th>





                                </tr>


                                  <tr>
                                        <th class="tx-center"> INVOICE NUMBER-رقم الفاتورة</th>
                                    <th class="tx-center">{{ $data['invoiceData']->id}}</th>
                                </tr>
                                            <tr>
                                  <th class="tx-16"> NOTICE DATE تاريخ الاشعار </th>
                                    <th class="tx-16">{{ $created_at}}</th>
                                </tr>

                                    <tr>
                                    <th class="tx-16"> NOTICE  NUMBER رقم الاشعار</th>
                                    <th class="tx-16">{{$data['invoiceData']->NOTICE_Number}}</th>
                                </tr>
                            </thead>

                        </table>


</div>

                    <div class="table mg-t-30 my-5">
                        <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center my-5">
                            <thead>
                                <tr>
                                    <th class="wd-center">PRODUCT NO<br>رقم المنتج</th>
                                    <th class="tx-center">ITEM NAME<br>اسم الصنف </th>
                                    <th class="tx-center">PRODUCT PRICE<br>سعر القطعة </th>
                                    <th class="tx-center"> QUANTITY <br>الكمية </th>
                                    <th class="tx-center">TOTAL AMOUNT<br>الاجمالي</th>
                                    <th class="tx-center"> DISCOUNT<br>الخصم </th>
                                    <th class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</th>



                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $totalprice = 0;
                                $totalAddedValue = 0;
                                $avtSaleRate = App\Models\Avt::find(1);
                                $avtSale = $avtSaleRate->AVT;
                                $discount_total=0;
                                ?>

                                @foreach ($data['salesData'] as $product)
                                <?php $i++;
                                $discount_total+=( $product->discountvalue + $product->discountoninvoice);
                                $totalprice += ($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue - $product->discountoninvoice;
                                ?>


                                <tr>
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

                        <th class="tx-18">الاجمالي - SUB TOTAL </th>
                        <th class="tx-18">{{round($discount_total,2)+round($totalprice,2)}}</th>
                     </tr>
                     <tr>
                        <th class="tx-18">الخصم - DISCOUNT </th>
                        <th class="tx-18">{{round($discount_total,2)}}</th>
                     </tr>
                              <tr>

                                            <th class="tx-17">SUB TOTAL AFTER DISCOUNT - الاجمالي بعد الخصم</th>
                                        <td class="tx-">{{ $totalprice}}</td>
                                    </tr>

                                                      <?php
                                $avt = App\Models\Avt::find(1);

                        ?>
                                    <tr>
                                        <td class="tx-">ضريبة لمضافة({{$avt->AVT*100}}%)<br>  VALUE ADDED TAX   ({{$avt->AVT*100}}%)</td>
                                        <td class="tx-">{{round( $totalprice*$avtSale,2)}}</td>
                                    </tr>


                                    <tr>
                                        <td class="tx-17">NET TOTAL-الاجمالي </td>
                                        <td>{{round(($totalprice*$avtSale),2)+ $totalprice}}</td>
                                    </tr>
                                </body>

                            </table>
                        </div>
                              <div >
                                    <?php

                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSaleRate = $avtSaleRate->AVT;

                                    function ConvertToHEX($value)
                                    {
                                        return pack("H*", sprintf("%02X", $value));
                                    }
                                    $sellerName = sallerQrCode;
                                    $varNumber = TaxQrCode;
                                    // $time = \Carbon\Carbon::now()->addHours(3);
                                    $issue_time=substr($created_at, 11);
                                    $issue_date=substr($created_at, 0, 10);
                                     $time = (string)$issue_date . 'T' . (string)$issue_time;

                                    $total = number_format((round($totalprice*$avtSale,2)+ $totalprice),2,'.','');
                                    $tax = number_format(round( $totalprice*$avtSale,2),2,'.','');
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
                                    $empty='';
                                    $Hexempty = ConvertToHEX(6) . ConvertToHEX(strlen($empty));
                                    $empty6 = $Hexempty . $empty;
                                     $Hexempty = ConvertToHEX(7) . ConvertToHEX(strlen($empty));
                                    $empty7 = $Hexempty . $empty;
                                    $Hexempty = ConvertToHEX(8) . ConvertToHEX(strlen($empty));
                                    $empty8 = $Hexempty . $empty;
                                    $Hexempty = ConvertToHEX(9) . ConvertToHEX(strlen($empty));
                                    $empty9 = $Hexempty . $empty;

                                    $tobase   = $seller . $vat . $time . $total . $VATN. $empty6. $empty7. $empty8. $empty9;
                                    $dataforQRcode =  base64_encode($tobase);
                                    ?>
                                    {!! QrCode::size(140)->generate( $dataforQRcode) !!}
                                </div>
                        <hr class="mg-b-40">

     <div style="  position: fixed;
       text-align: center;
       bottom: 0px;
       width: 100%;">

@if(Auth()->user()->branchs_id==1)
<center> <span>
   -
</span>
</center>
<center> <span>
    {{addressar}}
</span>
</center>


<center> <span>    {{addressen}}
   </span>
</center>

@endif
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