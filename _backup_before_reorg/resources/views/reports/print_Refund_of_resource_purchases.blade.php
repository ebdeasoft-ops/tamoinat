@extends('layouts.master')

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    @media print {
        #print_Button, .breadcrumb-header, .main-header, .sidebar, footer, .app-sidebar, .app-header { display: none !important; }
        body { background: #fff !important; }
        .invoice-wrapper { box-shadow: none !important; padding: 0 !important; }
        .invoice-block { page-break-inside: avoid; }
    }

    body { font-family: 'Cairo', sans-serif; background: #f4f6f9; }

    .invoice-wrapper {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,.06);
        padding: 40px;
    }

    .report-title-band {
        background: linear-gradient(135deg, #419BB2 0%, #2c7a8c 100%);
        border-radius: 12px;
        padding: 18px 24px;
        color: #fff;
        text-align: center;
        margin-bottom: 30px;
    }
    .report-title-band h2 {
        margin: 0;
        font-weight: 800;
        font-size: 24px;
        letter-spacing: .5px;
    }

    .company-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #419BB2;
        padding-bottom: 24px;
        margin-bottom: 24px;
    }
    .company-block { width: 33%; text-align: center; }
    .company-block .company-name { font-size: 22px; font-weight: 800; color: #2c3e50; display: block; margin-bottom: 6px; }
    .company-block p, .company-block span { font-size: 13px; color: #6c757d; margin: 2px 0; display: block; }
    .company-logo img { max-height: 80px; }

    .meta-strip {
        display: flex;
        justify-content: space-around;
        background: #eef6f8;
        border: 1px solid #d7ecf0;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 28px;
        font-size: 14px;
    }
    .meta-strip .meta-item strong { color: #419BB2; display: block; font-size: 12px; margin-bottom: 3px; }

    .invoice-block {
        border: 1px solid #e3e7ec;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 28px;
        background: #fdfdfe;
    }

    .invoice-block-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #419BB2;
        color: #fff;
        border-radius: 8px;
        padding: 10px 18px;
        margin-bottom: 16px;
        font-size: 14px;
        font-weight: 700;
    }
    .invoice-block-header span.badge-num { background: rgba(255,255,255,.2); border-radius: 6px; padding: 2px 10px; margin-inline-start: 6px; }

    table.items-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    table.items-table thead th {
        background: #f1f5f7;
        color: #2c3e50;
        font-size: 13px;
        font-weight: 700;
        padding: 10px;
        border: 1px solid #e3e7ec;
    }
    table.items-table tbody td {
        padding: 9px 10px;
        text-align: center;
        font-size: 13px;
        border: 1px solid #e3e7ec;
        color: #34495e;
    }
    table.items-table tbody tr:nth-child(even) { background: #fafbfc; }

    .invoice-totals { width: 60%; margin-inline-start: auto; }
    .invoice-totals table { width: 100%; border-collapse: collapse; }
    .invoice-totals td, .invoice-totals th {
        padding: 8px 12px; font-size: 13px; border: 1px solid #e3e7ec;
    }
    .invoice-totals th { background: #f8f9fa; color: #495057; text-align: start; font-weight: 600; }
    .invoice-totals tr.grand-row th, .invoice-totals tr.grand-row td {
        background: #eaf6f8; color: #1f5e6b; font-weight: 800; font-size: 15px;
    }

    .summary-box {
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        border-radius: 14px;
        padding: 30px;
        margin-top: 20px;
        color: #fff;
        text-align: center;
    }
    .summary-box h4 { font-weight: 800; margin-bottom: 20px; letter-spacing: .5px; }
    .summary-grid { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
    .summary-item { min-width: 180px; }
    .summary-item .label { font-size: 13px; opacity: .75; margin-bottom: 6px; }
    .summary-item .value { font-size: 22px; font-weight: 800; color: #6fd6e8; }
    .summary-item.final .value { color: #ffd166; font-size: 26px; }

    .empty-state { text-align: center; padding: 60px 20px; color: #adb5bd; }
    .empty-state i { font-size: 48px; display: block; margin-bottom: 10px; }

    #print_Button { border-radius: 30px; padding: 10px 28px; font-weight: 700; box-shadow: 0 4px 10px rgba(65,155,178,.3); }
</style>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12">
        <div class="invoice-wrapper" id="print">

            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary" id="print_Button" onclick="printDiv()">
                    طباعة التقرير <i class="mdi mdi-printer ms-1"></i>
                </button>
            </div>

            <!-- رأس الشركة -->
            <div class="company-header">
                <div class="company-block">
                    <span class="company-name">{{ Nameen ?? '' }}</span>
                    <p dir="ltr">{{ describtionen ?? '' }}</p>
                    <span dir="ltr">{{ STen ?? '' }}</span>
                    <p dir="ltr">{{ Taxen ?? '' }}</p>
                </div>

                <div class="company-logo">
                    @php $logo = camplogo ?? 'default.png'; @endphp
                    <a href="https://ebdeasoft.com/">
                        <img src="{{ asset('assets/img/brand').'/'.$logo }}" alt="logo">
                    </a>
                </div>

                <div class="company-block">
                    <span class="company-name">{{ Namear ?? '' }}</span>
                    <p>{{ describtionar ?? '' }}</p>
                    <p>{{ STar ?? '' }}</p>
                    <p>{{ Taxar ?? '' }}</p>
                </div>
            </div>

            <!-- عنوان التقرير -->
            <div class="report-title-band">
                <h2>تقرير مرتجع المشتريات</h2>
            </div>

            <!-- شريط الفترة -->
            <div class="meta-strip">
                <div class="meta-item">
                    <strong>تاريخ الإصدار</strong>
                    {{ date('Y-m-d H:i') }}
                </div>
                <div class="meta-item">
                    <strong>بداية الفترة</strong>
                    {{ $start_at ?? 'غير محدد' }}
                </div>
                <div class="meta-item">
                    <strong>نهاية الفترة</strong>
                    {{ $end_at ?? date('Y-m-d') }}
                </div>
            </div>

            @if (isset($Invoices) && count($Invoices) > 0)
                @php
                    $grandTotalBeforeTax = 0;
                    $grandTotalTax = 0;
                    $grandTotalFinal = 0;
                @endphp

                @foreach ($Invoices as $invoice)
                    @php
                        $totalprice = 0;
                        $totalTaxAmount = 0;
                        $discount = $invoice->discount ?? 0;
                        $products = App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('returns_purchase', '!=', 0)->get();
                    @endphp

                    <div class="invoice-block">
                        <div class="invoice-block-header">
                            <div>رقم الفاتورة <span class="badge-num">{{ $invoice->orderId }}</span></div>
                            <div>المورد <span class="badge-num">{{ optional($invoice->supllier)->name ?? '—' }}</span></div>
                        </div>

                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية المرتجعة</th>
                                    <th>سعر الشراء</th>
                                    <th>الضريبة</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    @php
                                        $lineBase = $product->returns_purchase * $product->purchasingـprice;
                                        $lineTax = $product->returns_purchase * $product->Added_Value;

                                        $totalprice += $lineBase;
                                        $totalTaxAmount += $lineTax;
                                    @endphp
                                    <tr>
                                        <td>{{ optional($product->productData)->product_name }}</td>
                                        <td>{{ $product->returns_purchase }}</td>
                                        <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                                        <td>{{ number_format($product->Added_Value, 2) }}</td>
                                        <td>{{ number_format($lineBase + $lineTax, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php
                            $totalTax = round($totalTaxAmount, 2);
                            $invoiceGrandTotal = ($totalprice - $discount) + $totalTax;

                            $grandTotalBeforeTax += ($totalprice - $discount);
                            $grandTotalTax += $totalTax;
                            $grandTotalFinal += $invoiceGrandTotal;
                        @endphp

                        <div class="invoice-totals">
                            <table>
                                <tr><th>الإجمالي قبل الضريبة</th><td>{{ number_format($totalprice - $discount, 2) }}</td></tr>
                                <tr><th>إجمالي الضريبة</th><td>{{ number_format($totalTax, 2) }}</td></tr>
                                <tr class="grand-row"><th>الإجمالي النهائي</th><td>{{ number_format($invoiceGrandTotal, 2) }}</td></tr>
                            </table>
                        </div>
                    </div>
                @endforeach

                <!-- الملخص الشامل -->
                <div class="summary-box">
                    <h4>ملخص المرتجعات الشامل</h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="label">الإجمالي قبل الضريبة</div>
                            <div class="value">{{ number_format($grandTotalBeforeTax, 2) }}</div>
                        </div>
                        <div class="summary-item">
                            <div class="label">إجمالي الضريبة</div>
                            <div class="value">{{ number_format($grandTotalTax, 2) }}</div>
                        </div>
                        <div class="summary-item final">
                            <div class="label">الإجمالي النهائي</div>
                            <div class="value">{{ number_format($grandTotalFinal, 2) }}</div>
                        </div>
                    </div>
                </div>

            @else
                <div class="empty-state">
                    <i class="mdi mdi-file-search-outline"></i>
                    لا توجد بيانات مرتجعات خلال الفترة المحددة
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
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