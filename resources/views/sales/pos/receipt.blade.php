@php
    $isAr = app()->getLocale() == 'ar';
    $t = fn ($ar, $en) => $isAr ? $ar : $en;
    $m = fn ($n) => number_format((float) $n, 2);
    $cur = $t('ر.س', 'SAR');
    $payNames = [
        'Cash' => $t('نقداً (كاش)', 'Cash'),
        'Shabka' => $t('شبكة (مدى / بطاقة)', 'Card'),
        'Bank_transfer' => $t('تحويل بنكي', 'Bank transfer'),
        'Partition' => $t('متعدد', 'Split'),
        'Credit' => $t('آجل', 'Credit'),
    ];
    $pay = $payNames[$invoice->Pay] ?? ($invoice->Pay ?: '-');
    $companyName = $isAr ? ($setting->name_ar ?? '') : (($setting->name_en ?? '') ?: ($setting->name_ar ?? ''));
    $address = $isAr ? ($setting->address_ar ?? '') : (($setting->address_en ?? '') ?: ($setting->address_ar ?? ''));
    $created = \Carbon\Carbon::parse($invoice->created_at);
    $customerName = optional($invoice->customer)->name;
    $isCashCustomer = !$customerName || $invoice->customer_id == 1;
    try {
        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(130)->margin(0)->generate($qr);
    } catch (\Throwable $e) {
        $qrSvg = null;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $t('فاتورة', 'Invoice') }} #{{ $invoice->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: 80mm auto; margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f1f5f9; font-family: 'Cairo', Tahoma, Arial, sans-serif; color: #000; }
        .tools { display: flex; justify-content: center; gap: 10px; padding: 18px 10px; flex-wrap: wrap; }
        .tools button, .tools a { display: inline-flex; align-items: center; gap: 6px; height: 42px; padding: 0 18px; border-radius: 10px; border: 0; font-family: inherit; font-weight: 800; font-size: 14px; cursor: pointer; text-decoration: none; }
        .b-print { background: #2563eb; color: #fff; }
        .b-back { background: #059669; color: #fff; }
        .b-pdf { background: #fff; color: #0f172a; border: 1px solid #cbd5e1 !important; }
        .paper { width: 80mm; margin: 0 auto 30px; background: #fff; padding: 4mm 4mm 6mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); font-size: 12px; line-height: 1.5; }
        .c { text-align: center; }
        .logo { max-width: 38mm; max-height: 20mm; display: block; margin: 0 auto 4px; }
        .co { font-size: 16px; font-weight: 800; line-height: 1.35; margin: 2px 0; }
        .muted { font-size: 11px; color: #333; }
        .sep { border: 0; border-top: 1px dashed #000; margin: 6px 0; }
        .sep.solid { border-top-style: solid; border-top-width: 2px; }
        .title { font-size: 14px; font-weight: 800; margin: 4px 0; }
        .kv { display: flex; justify-content: space-between; gap: 8px; font-size: 11.5px; }
        .kv b { font-weight: 700; }
        table { width: 100%; border-collapse: collapse; font-size: 11.5px; }
        th { font-weight: 800; border-bottom: 1px solid #000; padding: 3px 1px; text-align: start; }
        td { padding: 3px 1px; vertical-align: top; border-bottom: 1px dotted #999; }
        th.n, td.n { text-align: center; white-space: nowrap; }
        td.nm { font-weight: 700; word-break: break-word; }
        .tot { display: flex; justify-content: space-between; font-size: 12px; padding: 1px 0; }
        .grand { display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; padding: 3px 0; }
        .qr { display: flex; justify-content: center; margin: 8px 0 2px; }
        .qr svg { width: 34mm; height: 34mm; }
        .thanks { font-weight: 700; font-size: 12px; }
        /* خط أتقل وأوضح على الطابعة الحرارية */
        .paper { font-weight: 700; color: #000; font-size: 12.5px; }
        .paper .muted { color: #000; font-weight: 600; font-size: 11.5px; }
        .paper td, .paper .kv, .paper .tot { font-weight: 700; }
        .paper td.nm, .paper .kv b, .paper .tot b, .paper th { font-weight: 800; }
        .paper .co { font-size: 17px; font-weight: 900; }
        .paper .grand { font-size: 18px; font-weight: 900; }
        .paper td { border-bottom-color: #555; }
        .paper .sep { border-top-width: 1.5px; }
        @media print {
            body { background: #fff; }
            .tools { display: none !important; }
            .paper { width: 100%; margin: 0; box-shadow: none; padding: 2mm 3mm 4mm; }
        }
    </style>
</head>
<body>
    <div class="tools">
        <button type="button" class="b-print" onclick="window.print()">🖨 {{ $t('طباعة الفاتورة', 'Print receipt') }}</button>
        <a class="b-pdf" href="{{ url('/generate_pdf/' . $invoice->id) }}">{{ $t('تحميل PDF (A4)', 'Download PDF (A4)') }}</a>
        <a class="b-back" href="{{ url('/pos') }}">🖥 {{ $t('العودة لنقطة البيع', 'Back to POS') }}</a>
    </div>

    <div class="paper">
        <div class="c">
            @if (!empty($setting->logo))
                <img class="logo" src="{{ asset('assets/img/brand/' . $setting->logo) }}" alt="" onerror="this.style.display='none'">
            @endif
            <div class="co">{{ $companyName }}</div>
            @if (!empty($setting->SR))<div class="muted">{{ $t('س.ت', 'C.R') }}: {{ $setting->SR }}</div>@endif
            @if (!empty($setting->Tax))<div class="muted">{{ $t('الرقم الضريبي', 'VAT No.') }}: {{ $setting->Tax }}</div>@endif
            @if ($address)<div class="muted">{{ $address }}</div>@endif
        </div>

        <hr class="sep">
        <div class="c title">{{ $isCashCustomer || empty(optional($invoice->customer)->tax_no) ? $t('فاتورة ضريبية مبسطة', 'Simplified Tax Invoice') : $t('فاتورة ضريبية', 'Tax Invoice') }}</div>
        <hr class="sep">

        <div class="kv"><span>{{ $t('رقم الفاتورة', 'Invoice No.') }}:</span><b>#{{ $invoice->id }}</b></div>
        <div class="kv"><span>{{ $t('التاريخ والوقت', 'Date & time') }}:</span><b dir="ltr">{{ $created->format('Y/m/d H:i') }}</b></div>
        <div class="kv"><span>{{ $t('الكاشير', 'Cashier') }}:</span><b>{{ optional($invoice->user)->name ?? '-' }}</b></div>
        @unless ($isCashCustomer)
            <div class="kv"><span>{{ $t('العميل', 'Customer') }}:</span><b>{{ $customerName }}</b></div>
            @if (!empty($invoice->customer->tax_no))
                <div class="kv"><span>{{ $t('ضريبي العميل', 'Customer VAT') }}:</span><b>{{ $invoice->customer->tax_no }}</b></div>
            @endif
        @endunless

        <table style="margin-top:6px">
            <thead><tr>
                <th>{{ $t('الصنف', 'Item') }}</th>
                <th class="n">{{ $t('الكمية', 'Qty') }}</th>
                <th class="n">{{ $t('السعر', 'Price') }}</th>
                <th class="n">{{ $t('الإجمالي', 'Total') }}</th>
            </tr></thead>
            <tbody>
            @foreach ($items as $it)
                @php
                    $q = (float) $it->quantity;
                    $lineTotal = config('pos.prices_include_vat', true)
                        ? (float) $it->Unit_Price * $q + (float) $it->Added_Value
                        : (float) $it->Unit_Price * $q;
                    $unitShown = $q != 0 ? $lineTotal / $q : 0;
                @endphp
                <tr>
                    <td class="nm">{{ $it->product_name ?: (optional(\App\Models\products::find($it->product_id))->product_name ?? '-') }}</td>
                    <td class="n">{{ rtrim(rtrim(number_format((float) $it->quantity, 3, '.', ''), '0'), '.') }}</td>
                    <td class="n">{{ $m($unitShown) }}</td>
                    <td class="n">{{ $m($lineTotal) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <hr class="sep">
        <div class="tot"><span>{{ $t('المجموع غير شامل الضريبة', 'Subtotal (excl. VAT)') }}:</span><b>{{ $m($subtotal) }} {{ $cur }}</b></div>
        @if ($discount > 0)
            <div class="tot"><span>{{ $t('الخصم', 'Discount') }}:</span><b>{{ $m($discount) }} {{ $cur }}</b></div>
        @endif
        <div class="tot"><span>{{ $t('ضريبة القيمة المضافة', 'VAT') }} ({{ rtrim(rtrim(number_format($vatRate * 100, 2), '0'), '.') }}%):</span><b>{{ $m($tax) }} {{ $cur }}</b></div>
        <hr class="sep">
        <div class="grand"><span>{{ $t('الإجمالي المستحق', 'Total due') }}:</span><span>{{ $m($total) }} {{ $cur }}</span></div>
        <hr class="sep">
        <div class="kv"><span>{{ $t('طريقة الدفع', 'Payment') }}:</span><b>{{ $pay }}</b></div>
        @if ($invoice->Pay == 'Partition')
            @if ($invoice->cashamount > 0)<div class="kv"><span>{{ $t('نقدي', 'Cash') }}</span><b>{{ $m($invoice->cashamount) }}</b></div>@endif
            @if ($invoice->bankamount > 0)<div class="kv"><span>{{ $t('شبكة', 'Card') }}</span><b>{{ $m($invoice->bankamount) }}</b></div>@endif
            @if ($invoice->Bank_transfer > 0)<div class="kv"><span>{{ $t('تحويل', 'Transfer') }}</span><b>{{ $m($invoice->Bank_transfer) }}</b></div>@endif
        @endif

        @if ($qrSvg)
            <div class="qr">{!! $qrSvg !!}</div>
            <div class="c muted">{{ $t('امسح الرمز للتحقق من الفاتورة', 'Scan to verify the invoice') }}</div>
        @endif

        <hr class="sep solid">
        <div class="c thanks">{{ $t('شكراً لزيارتكم', 'Thank you for your visit') }}</div>
    </div>

    <script>
        // طباعة تلقائية عند الفتح من شاشة البيع (?print=1)
        if (/[?&]print=1/.test(location.search)) {
            window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 250); });
        }
    </script>
</body>
</html>
