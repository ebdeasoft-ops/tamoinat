<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            font-family: 'Cairo', 'DejaVu Sans', sans-serif !important;
        }

        body {
            font-size: 11px;
            padding: 2px;
            margin: 8px;
            color: #1f2937;
            text-align: right;
            line-height: 1.3;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 3px 5px;
            text-align: center;
        }

        .border,
        .border td,
        .border th {
            border: 1px solid #000;
        }

        tr:nth-child(even) {
            background-color: #eef6f8;
        }

        @page {
            size: a4;
            margin: 4px;
            padding: 0;
        }

        .double {
            border: 2px solid #dfe4ec;
            border-radius: 4px;
            width: 90%;
            font-size: 9px !important;
        }

        .row {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #1b3358;
        }

        .company-line {
            font-size: 9.5px;
            color: #6b7280;
            display: block;
        }

        .invoice-banner {
            background-color: #1b3358;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            padding: 6px 0;
            text-align: center;
            border-radius: 4px;
            width: 90% !important;
            border-right: 4px solid #c8933a;
        }

        .invoice-banner .label-en {
            font-size: 10px;
            font-weight: normal;
            color: #f4e6cf;
        }

        .invoice-banner .label-en:before {
            content: " - ";
            color: #f4e6cf;
        }

        .meta-table td,
        .customer-table td {
            border: 1px solid #d7dee8;
            font-size: 10.5px;
        }

        .meta-table tr:nth-child(odd) td,
        .customer-table tr:nth-child(odd) td {
            background-color: #f0f3f9;
        }

        .label-ar {
            font-weight: bold;
        }

        .label-en {
            font-size: 9px;
            font-weight: normal;
            color: #6b7280;
            direction: ltr;
        }

        .label-en:before {
            content: " - ";
            color: #9ca3af;
        }

        .items-table thead td {
            background-color: #1b3358;
            color: #fff;
            font-weight: bold;
            font-size: 9.5px;
        }

        .items-table thead td .label-en {
            display: block;
            color: #cdd8e8;
            font-size: 8.5px;
        }

        .items-table thead td .label-en:before {
            content: "";
        }

        .items-table thead td .label-ar {
            display: block;
        }

        .totals-table td:nth-child(2) {
            background-color: #f0f3f9;
            font-weight: bold;
        }

        .net-total-row td {
            background-color: #1b3358 !important;
            color: #fff;
            font-size: 12px;
            padding: 6px;
        }

        .net-total-row .label-en {
            color: #f4e6cf;
        }

        .return-row td {
            color: #c0392b;
        }

        .footer-bar {
            position: fixed;
            bottom: 0px;
            width: 100%;
            text-align: center;
            border-top: 1px solid #dfe4ec;
            padding-top: 4px;
            font-size: 8.5px;
            color: #8b93a1;
        }
    </style>
</head>

<body>

    @php
        // Guard against a fatal "cannot redeclare function" error if this
        // template is ever rendered more than once in the same request.
        if (!function_exists('ConvertToHEX')) {
            function ConvertToHEX($value)
            {
                return pack('H*', sprintf('%02X', $value));
            }
        }

        $logo = camplogo;
    @endphp

    <!-- letterhead -->
    <table>
        <tr>
            <td style="width:35%">
                <center>
                    <span class="company-name">{{ Nameen }}</span><br>
                    <span class="company-line">{{ describtionen }}</span>
                    <span class="company-line">{{ STen }}</span>
                    <span class="company-line">{{ Taxen }}</span>
                </center>
            </td>
            <td style="width:30%">
                <center>
                    <img src="{{ public_path('assets/img/brand') . '/' . $logo }}" style="width: 120px; height: 80px;">
                </center>
            </td>
            <td style="width:35%">
                <center>
                    <span class="company-name">{{ Namear }}</span><br>
                    <span class="company-line">{{ describtionar }}</span>
                    <span class="company-line">{{ STar }}</span>
                    <span class="company-line">{{ Taxar }}</span>
                </center>
            </td>
        </tr>
    </table>

    <center>
        @if ($data['invoiceData']->customer->id == 1)
            <p class="invoice-banner">فاتورة ضريبية مبسطة<span class="label-en">Simplified Tax Invoice</span></p>
        @else
            <p class="invoice-banner">فاتورة ضريبية<span class="label-en">Tax Invoice</span></p>
        @endif
    </center>

    @php
        $pay = '';
        if ($data['invoiceData']->Pay == 'Cash') {
            $pay = __('report.cash');
        } elseif ($data['invoiceData']->Pay == 'Shabka') {
            $pay = __('report.shabka');
        } elseif ($data['invoiceData']->Pay == 'Credit') {
            $pay = __('report.credit');
        } elseif ($data['invoiceData']->Pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('home.Partition of the amount');
        }
    @endphp

    <table dir="rtl" class="meta-table double" style="width:100%">
        <tbody>
            <tr>
                <td>{{ $data['invoiceData']->id }}</td>
                <td><span class="label-ar">رقم الفاتورة</span><span class="label-en">INVOICE NUMBER</span></td>
                <td>{{ $data['invoiceData']->branch->name }}</td>
                <td><span class="label-ar">اسم الفرع</span><span class="label-en">BRANCH NAME</span></td>
            </tr>
            <tr>
                <td>{{ $pay }}</td>
                <td><span class="label-ar">طريقة الدفع</span><span class="label-en">PAYMENT METHOD</span></td>
                <td>{{ $data['invoiceData']->created_at }}</td>
                <td><span class="label-ar">تاريخ الفاتورة</span><span class="label-en">INVOICE DATE</span></td>
            </tr>
            <tr>
                <td>{{ $data['invoiceData']->p_o ?: '-' }}</td>
                <td><span class="label-ar">امر الشراء</span><span class="label-en">PURCHASE ORDER</span></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- customer / seller details -->
    <table dir="rtl" class="customer-table double" style="width:100%">
        <tbody>
            <tr>
                <td>{{ Namear }}</td>
                <td><span class="label-ar">اسم البائع</span><span class="label-en">SELLER NAME</span></td>
                <td>{{ $data['invoiceData']->customer->name }}</td>
                <td><span class="label-ar">اسم العميل</span><span class="label-en">CLIENT NAME</span></td>
            </tr>

            <tr>
                <td>{{ Taxen }}</td>
                <td><span class="label-ar">الرقم الضريبي</span><span class="label-en">TAX NUMBER</span></td>
                <td>{{ $data['invoiceData']->customer->tax_no == 0 ? '-' : $data['invoiceData']->customer->tax_no }}</td>
                <td><span class="label-ar">الرقم الضريبي</span><span class="label-en">TAX NUMBER</span></td>
            </tr>

            @if ($data['invoiceData']->customer->address == '-')
                <tr>
                    <td>{{ defined('city') ? city : '-' }}</td>
                    <td><span class="label-ar">المدينة</span><span class="label-en">CITY</span></td>
                    <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->phone }}</td>
                    <td><span class="label-ar">رقم الجوال</span><span class="label-en">PHONE</span></td>
                </tr>
            @else
                <tr>
                    <td>{{ defined('city') ? city : '-' }}</td>
                    <td><span class="label-ar">المدينة</span><span class="label-en">CITY</span></td>
                    <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->address }}</td>
                    <td><span class="label-ar">العنوان</span><span class="label-en">ADDRESS</span></td>
                </tr>
            @endif

            <tr>
                <td>{{ defined('region') ? region : '-' }}</td>
                <td><span class="label-ar">المنطقة</span><span class="label-en">REGION</span></td>
                <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->sub_city }}</td>
                <td><span class="label-ar">المنطقة</span><span class="label-en">REGION</span></td>
            </tr>
            <tr>
                <td>{{ defined('street_name') ? street_name : '-' }}</td>
                <td><span class="label-ar">اسم الشارع</span><span class="label-en">STREET NAME</span></td>
                <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->street_name }}</td>
                <td><span class="label-ar">اسم الشارع</span><span class="label-en">STREET NAME</span></td>
            </tr>
            <tr>
                <td>{{ defined('postal_number') ? postal_number : '-' }}</td>
                <td><span class="label-ar">الرمز البريدي</span><span class="label-en">POSTAL NUMBER</span></td>
                <td>{{ $data['invoiceData']->customer->postcode }}</td>
                <td><span class="label-ar">الرمز البريدي</span><span class="label-en">POSTAL NUMBER</span></td>
            </tr>
            <tr>
                <td>{{ defined('building_number') ? building_number : '-' }}</td>
                <td><span class="label-ar">رقم المبنى</span><span class="label-en">BUILDING NUMBER</span></td>
                <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->building_number }}</td>
                <td><span class="label-ar">رقم المبنى</span><span class="label-en">BUILDING NUMBER</span></td>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- items -->
    <div dir="ltr">
        <table class="border items-table">
            <thead>
                <tr>
                    <td><span class="label-en">Total AFTER DISCOUNT</span><span class="label-ar">الاجمالي بعد الخصم</span></td>
                    <td><span class="label-en">DISCOUNT</span><span class="label-ar">الخصم</span></td>
                    <td><span class="label-en">Total</span><span class="label-ar">الاجمالي</span></td>
                    <td><span class="label-en">PRODUCT PRICE</span><span class="label-ar">سعر القطعة</span></td>
                    <td><span class="label-en">QUANTITY</span><span class="label-ar">الكمية</span></td>
                    <td><span class="label-en">ITEM NAME</span><span class="label-ar">اسم الصنف</span></td>
                    <td><span class="label-en">Item NO</span><span class="label-ar">رقم منتج</span></td>
                    <td><span class="label-en">NO</span><span class="label-ar">رقم</span></td>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                    $discountreturn = 0;
                @endphp

                @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                    @if ($product->quantity != 0)
                        @php $i++; @endphp
                        <tr>
                            <td>{{ number_format(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2, '.', '') }}</td>
                            <td>{{ number_format($product->Discount_Value, 2, '.', '') }}</td>
                            <td>{{ number_format($product->Unit_Price * $product->quantity, 2, '.', '') }}</td>
                            <td>{{ number_format($product->Unit_Price, 2, '.', '') }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->productData->product_name }}</td>
                            <td dir="rtl">{{ $product->productData->Product_Code }}</td>
                            <td>{{ $i }}</td>
                        </tr>
                    @endif
                @endforeach

                @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                    @php
                        $i++;
                        $discountreturn += $product->discountvalue + $product->discountoninvoice;
                    @endphp
                    @if ($product->return_quantity != 0)
                        <tr class="return-row">
                            <td>{{ number_format((float) (($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue), 2, '.', '') }}</td>
                            <td>{{ number_format((float) $product->discountvalue, 2, '.', '') }}</td>
                            <td>{{ number_format((float) $product->return_Unit_Price * $product->return_quantity, 2, '.', '') }}</td>
                            <td>{{ number_format($product->return_Unit_Price, 2, '.', '') }}</td>
                            <td>{{ $product->return_quantity }}</td>
                            <td>{{ $product->productData->product_name }}</td>
                            <td dir="rtl">{{ $product->productData->Product_Code }}</td>
                            <td>{{ $i }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <br>

    <div class="row" dir="ltr">
        @php
            // ZATCA simplified tax invoice QR payload — logic preserved exactly as original.
            $invoice = App\Models\invoices::find($data['invoiceData']->id);
            $price = $invoice->cashamount + $invoice->Bank_transfer + $invoice->bankamount + $invoice->creaditamount;
            $avt = App\Models\Avt::find(1);

            $price_befor_tax = ($price * 100) / (100 + $avt->AVT * 100);
            $invoicetotal_addedvalue = $price_befor_tax * $avt->AVT;
            $invoicetotal_price = $price_befor_tax;
            $invoicetotal_discount = $invoice->discount + $discountreturn;

            $sellerName = sallerQrCode;
            $varNumber = TaxQrCode;
            $time = $invoice->created_at;
            $issue_time = substr($time, 11);
            $issue_date = substr($time, 0, 10);
            $time = (string) $issue_date . 'T' . (string) $issue_time;

            $total = number_format(round($invoicetotal_addedvalue + $invoicetotal_price, 2), 2, '.', '');
            $tax = number_format(round($invoicetotal_addedvalue, 2), 2, '.', '');

            $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
            $seller = $HexSeller . $sellerName;
            $HexVAT = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
            $vat = $HexVAT . $varNumber;
            $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($time));
            $time = $HexTime . $time;
            $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($total));
            $total = $HexTotal . $total;
            $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax));
            $VATN = $HexVATN . $tax;

            $empty = '';
            $Hexempty = ConvertToHEX(6) . ConvertToHEX(strlen($empty));
            $empty6 = $Hexempty . $empty;
            $Hexempty = ConvertToHEX(7) . ConvertToHEX(strlen($empty));
            $empty7 = $Hexempty . $empty;
            $Hexempty = ConvertToHEX(8) . ConvertToHEX(strlen($empty));
            $empty8 = $Hexempty . $empty;
            $Hexempty = ConvertToHEX(9) . ConvertToHEX(strlen($empty));
            $empty9 = $Hexempty . $empty;

            $tobase = $seller . $vat . $time . $total . $VATN . $empty6 . $empty7 . $empty8 . $empty9;
            $dataforQRcode = base64_encode($tobase);
        @endphp

        <center>
            <p class="double" dir="rtl" style="direction: rtl; text-align: center;">
                يمكن ارجاع القطع المباعة او استبدالها خلال 3 ايام من تاريخ الشراء وتكون بحالتها المباعة <br>
                والقطع الكهربائية لا ترد ولا تستبدل والطلبات الخاصة لا يمكن ارجاعها او استبدالها بعد تاكيد الطلب
            </p>
        </center>

        <div class="row" dir="rtl">
            <table style="width:100%">
                <tr>
                    <td style="width:40%">
                        <table class="border totals-table">
                            <tbody>
                                <tr>
                                    <td>{{ number_format((float) (round($invoicetotal_price, 2) + round($invoicetotal_discount, 2)), 2, '.', '') }}</td>
                                    <td><span class="label-ar">الاجمالي</span><span class="label-en">SUB TOTAL</span></td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(round($invoicetotal_discount, 2), 2, '.', '') }}</td>
                                    <td><span class="label-ar">الخصم</span><span class="label-en">DISCOUNT</span></td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(round($invoicetotal_price, 2), 2, '.', '') }}</td>
                                    <td><span class="label-ar">الاجمالي بعد الخصم</span><span class="label-en">SUB TOTAL AFTER DISCOUNT</span></td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(round($invoicetotal_addedvalue, 2), 2, '.', '') }}</td>
                                    <td><span class="label-ar">ضريبة القيمة المضافة ({{ $avt->AVT * 100 }}%)</span><span class="label-en">VALUE ADDED TAX ({{ $avt->AVT * 100 }}%)</span></td>
                                </tr>
                                <tr class="net-total-row">
                                    <td>{{ number_format(round($invoicetotal_addedvalue + $invoicetotal_price, 2), 2, '.', '') }}</td>
                                    <td><span class="label-ar">الاجمالي الكلي</span><span class="label-en">NET TOTAL</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td style="width:35%">
                        @if (Auth()->user()->branchs_id == 1)
                            <center>
                                <p class="double">
                                    {{ bankname }}<br>
                                    Account Number : {{ bank_acount_number }}<br>
                                    IBAN Number : {{ bank_acount_iban }}
                                </p>
                            </center>
                        @endif
                    </td>

                    <td style="width:25%">
                        <center>
                            <img src="data:image/png;base64,{!! base64_encode(QrCode::size(110)->generate($dataforQRcode)) !!}">
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <br>

    <span class="tx-16">{{ __('home.notesClient') }} : {{ $data['invoiceData']->note }}</span>

    <div class="footer-bar">
        @if (Auth()->user()->branchs_id == 1)
            <center><span dir="rtl">{{ addressar }}</span></center>
            <center><span>{{ addressen }}</span></center>
        @endif
    </div>

</body>

</html>