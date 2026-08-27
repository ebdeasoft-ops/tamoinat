<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Arabic Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            font-family: 'Cairo', 'DejaVu Sans', sans-serif !important;
        }

                body {
            font-size: 12px;
            padding: 0;
            margin: 0;
            color: #1f2937;
            text-align: right;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 6px 8px;
            text-align: center;
        }

        .bordered,
        .bordered td,
        .bordered th {
            border: 1px solid #dfe4ec;
        }

        tr:nth-child(even) {
            background-color: #f7f9fc;
        }

        @page {
            size: a4;
            margin: 10px 16px;
            padding: 0;
        }

        .row {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        .top-accent-bar {
            height: 6px;
            background-color: #c8933a;
            border-radius: 0 0 3px 3px;
            margin-bottom: 10px;
        }

        .letterhead-table {
            margin-bottom: 6px;
        }

        .letterhead-table td {
            vertical-align: middle;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1b3358;
        }

        .company-line {
            font-size: 10.5px;
            color: #6b7280;
            display: block;
            margin-top: 2px;
        }

        .letterhead-divider {
            border: none;
            border-top: 2px solid #1b3358;
            margin: 8px 0 14px 0;
        }

        .quote-banner {
            background-color: #1b3358;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 0;
            text-align: center;
            margin: 4px 0 14px 0;
            border-radius: 6px;
            border-right: 6px solid #c8933a;
        }

        .quote-banner .sub {
            display: block;
            font-size: 10px;
            font-weight: normal;
            color: #f4e6cf;
            margin-top: 2px;
        }

        .meta-wrap {
            margin-bottom: 14px;
        }

        .meta-table {
            border-radius: 6px;
            overflow: hidden;
        }

        .meta-table th {
            background-color: #f7f9fc;
            font-size: 11px;
            color: #1b3358;
            width: 55%;
            font-weight: bold;
            border-color: #dfe4ec;
        }

        .meta-table td.value-cell {
            font-weight: bold;
            width: 45%;
            color: #111827;
            border-color: #dfe4ec;
        }

        .meta-label-ar {
            display: block;
            font-size: 9.5px;
            color: #6b7280;
            font-weight: normal;
            margin-top: 2px;
        }

        .items-table {
            margin-bottom: 4px;
        }

        .items-table thead td {
            background-color: #1b3358;
            color: #fff;
            font-weight: bold;
            font-size: 10.5px;
            border-color: #1b3358;
        }

        .items-table thead td .en {
            display: block;
        }

        .items-table thead td .ar {
            display: block;
            font-size: 9.5px;
            color: #f4e6cf;
            font-weight: normal;
        }

        .items-table tbody td {
            font-size: 11.5px;
            border-color: #dfe4ec;
        }

        .totals-wrap {
            width: 55%;
            margin-right: 0;
            margin-left: auto;
        }

        .totals-table {
            border-radius: 6px;
            overflow: hidden;
        }

        .totals-table td {
            border-color: #dfe4ec;
        }

        .totals-table td:nth-child(2) {
            background-color: #f7f9fc;
            font-weight: bold;
            width: 60%;
            color: #1b3358;
            text-align: right;
            padding-right: 14px;
        }

        .totals-table td:nth-child(1) {
            width: 40%;
            font-weight: bold;
        }

        .totals-table td:nth-child(2) .label-ar {
            display: block;
            font-size: 12px;
            font-weight: bold;
        }

        .totals-table td:nth-child(2) .label-en {
            display: block;
            font-size: 9px;
            font-weight: normal;
            color: #6b7280;
            direction: ltr;
        }

        .net-total-row td:nth-child(2) .label-en {
            color: #f4e6cf;
        }

        .net-total-row td {
            background-color: #1b3358 !important;
            color: #fff;
            font-size: 14px;
            padding: 10px 8px;
        }

        .net-total-row td:nth-child(2) {
            background-color: #1b3358 !important;
            color: #fff;
        }

        .net-total-row .amount-words {
            color: #f4e6cf !important;
            font-size: 10px;
            font-weight: normal;
        }

        .terms-box {
            border: 1px solid #dfe4ec;
            border-right: 4px solid #c8933a;
            background-color: #f7f9fc;
            border-radius: 6px;
            padding: 10px 14px;
            margin-top: 16px;
            font-size: 10.5px;
            color: #374151;
        }

        .terms-box h4 {
            margin: 0 0 6px 0;
            font-size: 12px;
            color: #1b3358;
        }

        .terms-box ul {
            margin: 0;
            padding-right: 16px;
        }

        .terms-box li {
            margin-bottom: 3px;
        }

        .signature-area {
            width: 100%;
            margin-top: 26px;
        }

        .signature-area td {
            width: 50%;
            text-align: center;
            padding-top: 30px;
            font-size: 11px;
            color: #6b7280;
        }

        .signature-line {
            border-top: 1px solid #9ca3af;
            width: 70%;
            margin: 0 auto 6px auto;
        }

        .bank-box {
            border: 2px solid #1b3358;
            border-radius: 6px;
            width: 65%;
            margin: 14px auto 0 auto;
            padding: 8px;
            font-size: 10px;
            text-align: center;
            color: #1b3358;
        }

        .footer-bar {
            position: fixed;
            bottom: 0px;
            width: 100%;
            text-align: center;
            border-top: 2px solid #1b3358;
            padding-top: 5px;
            font-size: 9.5px;
            color: #555;
        }
    </style>
</head>

<body>

    @php
        // Number-to-words helper (used for both the riyal and halala portions below).
        // Extracted once instead of being duplicated inline.
        function numberToWords($num)
        {
            $num = (int) str_replace([',', ''], '', trim((string) $num));
            if (!$num) {
                return '';
            }

            $words = [];
            $list1 = [
                '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
                'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen',
            ];
            $list2 = ['', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred'];
            $list3 = [
                '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion',
                'septillion', 'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion',
                'quattuordecillion', 'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion',
                'novemdecillion', 'vigintillion',
            ];

            $numLength = strlen($num);
            $levels = (int) (($numLength + 2) / 3);
            $maxLength = $levels * 3;
            $num = substr('00' . $num, -$maxLength);
            $numLevels = str_split($num, 3);

            for ($i = 0; $i < count($numLevels); $i++) {
                $levels--;
                $hundreds = (int) ($numLevels[$i] / 100);
                $hundreds = $hundreds ? ' ' . $list1[$hundreds] . ' hundred ' : '';
                $tens = (int) ($numLevels[$i] % 100);
                $singles = '';

                if ($tens < 20) {
                    $tens = $tens ? ' and ' . $list1[$tens] . ' ' : '';
                } else {
                    $tensDigit = (int) ($tens / 10);
                    $singlesDigit = (int) ($numLevels[$i] % 10);
                    $tens = ' and ' . $list2[$tensDigit] . ' ';
                    $singles = ' ' . $list1[$singlesDigit] . ' ';
                }

                $words[] = $hundreds . $tens . $singles . (($levels && (int) $numLevels[$i]) ? ' ' . $list3[$levels] . ' ' : '');
            }

            $words = implode(' ', $words);
            $words = preg_replace('/^\s\b(and)/', '', $words);

            return ucfirst(trim($words));
        }
    @endphp

    @php
        $logo = camplogo;
    @endphp

    <div class="top-accent-bar"></div>

    <!-- letterhead -->
    <table class="letterhead-table">
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
                    <img src="{{ public_path('assets/img/brand') . '/' . $logo }}" style="width: 130px; height: 90px;">
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

    <hr class="letterhead-divider">

    <div class="quote-banner">
        عرض تسعيرة للعميل - QUOTATION
        <span class="sub">Sales Price Offer for Customer</span>
    </div>

    @php
        $offer_price_to_customer = App\Models\offer_price_to_customer::find($itemsRequest[0]->order_id);
        $avt = App\Models\Avt::find(1);
    @endphp

    <table class="meta-wrap">
        <tr>
            <td style="width:50%; padding-left:5px;">
                <table class="bordered meta-table">
                    <tr>
                        <td class="value-cell">{{ $offer_price_to_customer->created_at }}</td>
                        <th>Quote DATE<span class="meta-label-ar">تاريخ التسعيرة</span></th>
                    </tr>
                    <tr>
                        <td class="value-cell">{{ $offer_price_to_customer->id }}</td>
                        <th>Quote NUMBER<span class="meta-label-ar">رقم التسعيرة</span></th>
                    </tr>
                </table>
            </td>
            <td style="width:50%; padding-right:5px;">
                <table class="bordered meta-table">
                    <tr>
                        <td class="value-cell">{{ $offer_price_to_customer->customer->name }}</td>
                        <th>CLIENT NAME<span class="meta-label-ar">اسم العميل</span></th>
                    </tr>
                    <tr>
                        <td class="value-cell">{{ $offer_price_to_customer->customer->tax_no }}</td>
                        <th>TAX NUMBER<span class="meta-label-ar">الرقم الضريبي</span></th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- items -->
    <table class="bordered items-table">
        <thead>
            <tr>
                <td><span class="en">Total AFTER DISCOUNT</span><span class="ar">الاجمالي بعد الخصم</span></td>
                <td><span class="en">DISCOUNT</span><span class="ar">الخصم</span></td>
                <td><span class="en">Total</span><span class="ar">الاجمالي</span></td>
                <td><span class="en">QUANTITY</span><span class="ar">الكمية</span></td>
                <td><span class="en">PRODUCT PRICE</span><span class="ar">سعر القطعة</span></td>
                <td><span class="en">ITEM NAME</span><span class="ar">اسم الصنف</span></td>
                <td><span class="en">Item NO</span><span class="ar">رقم منتج</span></td>
                <td><span class="en">NO</span><span class="ar">رقم</span></td>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
                $totalpricePurchases = 0;
                $totaldiscount = 0;
            @endphp

            @foreach ($itemsRequest as $product)
                @php
                    $i++;
                    $totalpricePurchases += $product->PriceWithoudTax * $product->quantity;
                    $totaldiscount += $product->discount;
                @endphp
                <tr>
                    <td>{{ round(($product->quantity * $product->PriceWithoudTax) - $product->discount, 2) }}</td>
                    <td>{{ $product->discount }}</td>
                    <td>{{ $product->quantity * $product->PriceWithoudTax }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->PriceWithoudTax }}</td>
                    <td>{{ $product->productData->product_name }}</td>
                    <td dir="ltr">{{ $product->productData->Product_Code }}</td>
                    <td>{{ $i }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <!-- totals -->
    <div class="row" dir="rtl">
        <div class="totals-wrap">
            <table class="bordered totals-table">
                <tbody>
                    <tr>
                        <td>{{ round($totalpricePurchases, 2) }}</td>
                        <td><span class="label-ar">الاجمالي</span><span class="label-en">SUB TOTAL</span></td>
                    </tr>
                    <tr>
                        <td>{{ round($totaldiscount, 2) }}</td>
                        <td><span class="label-ar">الخصم</span><span class="label-en">DISCOUNT</span></td>
                    </tr>
                    <tr>
                        <td>{{ round($totalpricePurchases, 2) - round($totaldiscount, 2) }}</td>
                        <td><span class="label-ar">الاجمالي بعد الخصم</span><span class="label-en">SUB TOTAL AFTER DISCOUNT</span></td>
                    </tr>
                    <tr>
                        <td>{{ round((round($totalpricePurchases, 2) - round($totaldiscount, 2)) * $avt->AVT, 2) }}</td>
                        <td><span class="label-ar">ضريبة القيمة المضافة ({{ $avt->AVT * 100 }}%)</span><span class="label-en">VALUE ADDED TAX</span></td>
                    </tr>

                    @php
                        $total = round((round($totalpricePurchases, 2) - round($totaldiscount, 2)) * $avt->AVT, 2)
                            + (round($totalpricePurchases, 2) - round($totaldiscount, 2));

                        [$whole, $decimal] = explode('.', number_format((float) $total, 2, '.', ''));

                        $decimalDigits = str_split($decimal);
                        $decimalValue = $decimalDigits[0] === '0' ? (int) $decimalDigits[1] : (int) $decimal;

                        $decimalWords = $decimalValue !== 0 ? numberToWords($decimalValue) : 'Zero';
                        $wholeWords = numberToWords((int) $whole);
                    @endphp

                    <tr class="net-total-row">
                        <td>
                            <center>
                                {{ number_format(round($total, 2), 2, '.', '') }}
                                <br>
                                <span class="amount-words">
                                    {{ $wholeWords }} Riyals and {{ $decimalWords }} Halala
                                </span>
                            </center>
                        </td>
                        <td><span class="label-ar">الاجمالي الكلي</span><span class="label-en">NET TOTAL</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- الشروط والأحكام -->
    <div class="terms-box">
        <h4>الشروط والأحكام | Terms &amp; Conditions</h4>
        <ul>
            <li>هذا العرض ساري لمدة 15 يومًا من تاريخ الإصدار ما لم يُذكر خلاف ذلك. <span dir="ltr">/ This quotation is valid for 15 days from the issue date.</span></li>
            <li>الأسعار المذكورة أعلاه شاملة ضريبة القيمة المضافة. <span dir="ltr">/ Prices above are inclusive of VAT.</span></li>
            <li>يُعد هذا المستند عرض تسعير وليس فاتورة ضريبية رسمية. <span dir="ltr">/ This document is a quotation, not a tax invoice.</span></li>
        </ul>
    </div>

    <!-- منطقة التوقيع -->
    <table class="signature-area">
        <tr>
            <td>
                <div class="signature-line"></div>
                توقيع واعتماد العميل<br>
                <span dir="ltr">Customer Approval Signature</span>
            </td>
            <td>
                <div class="signature-line"></div>
                توقيع مسؤول المبيعات<br>
                <span dir="ltr">Sales Representative Signature</span>
            </td>
        </tr>
    </table>

    <div class="bank-box">
        البنك العربي الوطني<br>
        Account Number : 0108095553810016<br>
        IBAN Number : SA9830400108095553810016
    </div>

    <div class="footer-bar">
        <center>{{ addressar }}</center>
        <center>{{ addressen }}</center>
    </div>

</body>

</html>