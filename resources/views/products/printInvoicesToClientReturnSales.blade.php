<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <script src="https://kit.fontawesome.com/164ea36700.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />

    <title> طباعة فاتورة مبيعات </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }





        @media print {



            td {
                font-size: 9px !important;
                text-align: center;
            }

            table {
                margin: 0 auto;
            }

            .mainheadtable {
                width: 90%;
                margin-right: 1px;
                float: right;
                border: 1 solid black
            }

            .tdhead {
                padding: 1px;
                text-align: right;
                font-weight: bold;
            }



            #print_Button {
                display: none;
            }

        }
    </style>

    <!-- row -->

<body style="max-width: 90mm; height:auto; color:black;font-family: 'Poppins', sans-serif; ">

    <div class="row row-sm">
        <div>
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">

                            <div class="col">
                             




                            </div><!-- invoice-header -->
                        </div>
                        <div style=" text-align: center;">
                            <br>
                            <span style="font-size:15px; text-align: center;">{{Nameen}}</span>
                            <br>
                            <span style="font-size:15px; text-align: center;">{{Namear}}</span>
                            <br>
                            <span dir="rtl" style="font-size:12px; text-align: right;">{{STar}}</span>
                            <br>
                            <span dir="rtl" style="font-size:12px; text-align: right;">{{Taxar}}</span>

                        </div>
                        <br>

                        <div style="text-align: right;">
                            <div style="text-align: center;">

                                <span dir="rtl" style="font-size:12px; text-align: right;">فاتورة استرجاع</span>
                            </div>
                            <br>
                            <span style="font-size:12px" class="tx-center">{{__('home.paymentmethod')}} : </span>
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
                            <span style="font-size:12px" class="tx-center">{{$pay}} </span>


                            <br>


                            <span style="font-size:12px" class="tx-center">{{__('home.branch')}} : </span>
                            <span style="font-size:12px" class="tx-center">{{ $data['invoiceData']->branch->name}}</span>

                            <br>


                            <span style="font-size:12px"> {{__('home.date')}} : </span>
                            <span style="font-size:12px">{{ $data['invoiceData']->created_at}}</span>

                            <br>



                            <span style="font-size:12px"> {{__('home.Invoice_no')}} : </span>
                            <span style="font-size:12px">{{ $data['invoiceData']->id}}</span>
                            <br>
                            <br>
                            <span style="font-size:12px">-------------------------------------------</span>

                            <br>
                            <span style="font-size:12px"> اسم العميل : {{ $data['invoiceData']->customer->name=='Cash customer'?'عميل نقدي':$data['invoiceData']->customer->name}}</span>
                            <br>
                            <span style="font-size:12px">العنوان : {{ $data['invoiceData']->customer->address=='Client Address' ||$data['invoiceData']->customer->address=='-'?'غير مسجل ':$data['invoiceData']->customer->address}} </span>
                            <br>
                            <span style="font-size:12px">الرقم الضريبي : {{ $data['invoiceData']->customer->tax_no}} </span>
                            <br>
                            <span style="font-size:12px">-------------------------------------------</span>


                        </div>

                        <br>
                        <br>

                        <div>
                            <table style="width:100%;  
" dir='rtl'>
                                <col style="width:35%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:35%">
                                <thead>
                                    <tr>
                                        <th style="font-size:9px"> {{__('home.product')}} </th>
                                        <th style="font-size:9px"> {{__('home.price')}} </th>
                                        <th style="font-size:9px"> {{__('home.quantity')}} </th>
                                        <th style="font-size:9px"> {{__('home.totalwithTax')}}</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSaleRate = $avtSaleRate->AVT;
                                    $totalprice=0;
                                    ?>


                                    @foreach ($data['salesData'] as $product)
                                    <?php $i++;
                                $totalprice += ($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue - $product->discountoninvoice;
                                ?>


                                    <tr>
                                        <td class="tx-center">{{ $product->productData->name}}</td>
                                        <td class="tx-center">{{ $product->return_Unit_Price}}</td>
                                        <td class="tx-center">{{ $product->return_quantity}}</td>
                                        <td class="tx-center">{{ ($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue}}</td>

                                    </tr>


                                    @endforeach





                                </tbody>
                            </table>
                            <br>
                            <br>
                            <div class="row">
                                <div>
                                    <table style="width:100%;  " dir='rtl'>
                                        <col style="width:70%">
                                        <col style="width:30%">

                                        <tbody>
                                            <tr>
                                                <td style="font-size:14px;text-align: center;">{{__('home.total')}} </td>
                                                <td style="font-size:15px;text-align: center;">{{ $totalprice}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:15px;text-align: center;"> {{__('home.addedValue')}} </td>
                                                <td style="font-size:15px;text-align: center;">{{round( $totalprice*$avtSaleRate,1)}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:15px;text-align: center;">{{__('home.the amount')}} </td>
                                                <td style="font-size:15px;text-align: center;">{{round(($totalprice*$avtSaleRate)+ $totalprice,1)}}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                    <br>
                                    <br>
                                    <span style="font-size:12px;;text-align: center;">** شكرا لزيارتكم ** </span>

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

    <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 1000);
    </script>

</body>

</html>
<!--Internal  Chart.bundle js -->