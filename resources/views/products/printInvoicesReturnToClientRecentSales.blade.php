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
                  font-size: 13px !important;
                text-align: center;
                                font-weight: bold;
        }





        @media print {

            .text{
                font-size: 15px !important;
                text-align: center;
                font-weight: bold;
            }

                font-size: 13px !important;
                text-align: center;
                                font-weight: bold;

            td {
                font-size: 13px !important;
                text-align: center;
                                font-weight: bold;

            }

            table {
                margin: 0 auto;
                      font-size: 13px !important;
                text-align: center;
                                font-weight: bold;
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

<body style="width:95%; padding: 1px;
">

    <div class="row row-sm">
        <div>
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                        
                        </div>
                        <div class="text" style=" text-align: center;">
                                     <?php
                                $logo = camplogo;
                                ?>
                                <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>
                                <br>
                            <span class="text" >{{Nameen}}</span>
                            <br>
                            <span class="text" >{{Namear}}</span>
                            <br>
                            <span dir="rtl" class="text">{{STar}}</span>
                            <br>
                            <span dir="rtl"class="text">{{Taxar}}</span>

                        </div>
                       

                        <div style="text-align: right;">
                        <div style="text-align: center;">

                            <span dir="rtl" class="text">{{__("home.VATinvoice")}}</span>
                        </div>
                         
                            <span class="text">{{__('home.paymentmethod')}} : </span>
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
                            <span class="text">{{$pay}} </span>


                            <br>


                            <span class="text">{{__('home.branch')}} : </span>
                            <span class="text">{{ $data['invoiceData']->branch->name}}</span>

                            <br>


                            <span class="text"> {{__('home.date')}} : </span>
                            <span class="text">{{ $data['invoiceData']->created_at}}</span>

                            <br>



                            <span class="text"> {{__('home.Invoice_no')}} : </span>
                            <span class="text">{{ $data['invoiceData']->id}}</span>
                            <br>
                        
                         
                            <span class="text"> اسم العميل : {{ $data['invoiceData']->customer->name=='Cash customer'?'عميل نقدي':$data['invoiceData']->customer->name}}</span>
                            <br>
          
                        

                        </div>

                        

                        <div>
                            <table style="width:100%;  
" dir='rtl'>
                                <col style="width:35%">
                                <col style="width:25%">
                                <col style="width:15%">
                                <col style="width:25%">
                                <thead>
                                    <tr>
                                        <th style="font-size:9px"> {{__('home.product')}} </th>
                                        <th style="font-size:9px"> {{__('home.saleperpice')}} </th>
                                        <th style="font-size:9px"> {{__('home.quantity')}} </th>
                                        <th style="font-size:9px"> {{__('home.totalwithTax')}}</th>




                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSaleRate = $avtSaleRate->AVT;

                                    ?>

                                    @foreach ($data['salesData'] as $product)
                                    <?php $i++ ?>
                                    @if($product->quantity!=0)
                                    <tr>

                                        <td style="font-size:12px;text-align: right;">{{ $product->productData->name}}</td>
                                        <td style="font-size:13px;text-align: center;">{{ $product->price_with_tax}}</td>
                                        <td style="font-size:13px;text-align: center;">{{ $product->quantity}}</td>
                                        <td style="font-size:13px;text-align: center;">{{round( ($product->price_with_tax*$product->quantity), 2)}}</td>

                                    </tr>
                                    @endif
                                    @endforeach



                                </tbody>
                            </table>
                            <br>
                            <div class="row">
                                <div>
                                    <table style="width:100%;  " dir='rtl'>
                                        <col style="width:70%">
                                        <col style="width:30%">

                                        <tbody>
                                            <tr>

                                                <td style="font-size:14px;text-align: center;">{{__('home.total')}} </td>
                                                <td style="font-size:15px;text-align: center;">{{round($data['invoicetotal_price'],2)}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:15px;text-align: center;"> {{__('home.addedValue')}} </td>
                                                <td style="font-size:15px;text-align: center;">{{round($data['invoicetotal_addedvalue'],2)}}</td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:15px;text-align: center;">{{ __('home.discount') }} </td>
                                                <td style="font-size:15px;text-align: center;">{{round($data['invoicetotal_discount'],2)}}</td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:15px;text-align: center;">{{__('home.the amount')}} </td>
                                                <td style="font-size:15px;text-align: center;">{{round($data['invoicetotal_addedvalue']+$data['invoicetotal_price'],2)}} 
                                                     <br>
                                        <p style="color:red;font-size:10px">     <span style="color:red;font-size:10px">{{$data['totatextlriyales'] }}</span>
                                        <span style="color:red;font-size:10px">{{$data['totatextlrihalala']}} </span></p> 

                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>
                                    <br>
                                    <span style="font-size:12px ;text-align: center;">-------------------------------------------</span>
                                    <br>
                                    <div style="text-align: center;">

                                    <span style="font-size:12px;;text-align: center;">رمز الاستجابة QR CODE</span>
                                    </div>
                                   
                                    <div class="card-body " style="text-align: center;
  margin: auto;
  width: 40%;
  padding: 10px;">
                                        <?php


                                        function ConvertToHEX($value)
                                        {
                                            return pack("H*", sprintf("%02X", $value));
                                        }
                                        $sellerName = sallerQrCode;
                                        $varNumber = TaxQrCode;
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
                                        {!! QrCode::size(90)->generate( $dataforQRcode) !!}
                                    </div>
                                </div>
                                <div style="text-align: center;">
                                <span class="text"> البضاعة المباعة  تسترجع باصل الفاتورة </span>
                                <br>
                                <span class="text"> Sold goods are returned with the original invoice. </span>
                                <br>
                                <span class="text">** شكرا لزيارتكم ** </span>




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

    <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 1000);
    </script>

</body>

</html>
<!--Internal  Chart.bundle js -->