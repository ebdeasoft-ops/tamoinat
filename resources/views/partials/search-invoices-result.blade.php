@forelse ($invoices as $invoice)
    <tr>
        <td class="text-start">{{ $invoice->display_number ?? $invoice->invoice_number ?? $invoice->id }}</td>
        <td class="text-start">{{ optional($invoice->customer)->name ?? '—' }}</td>
        <td>{{ optional($invoice->user)->name ?? '—' }}</td>
        <td>{{ number_format($invoice->cashamount + $invoice->bankamount + $invoice->creaditamount + $invoice->Bank_transfer, 2) }}</td>
        <td>{{ $invoice->Pay ?? '—' }}</td>
        <td class="text-end">{{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d H:i') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-4">لا توجد فواتير مطابقة</td>
    </tr>
@endforelse