<?php

namespace App\Support;

use App\Models\Avt;
use Illuminate\Support\Facades\DB;

/**
 * نسبة الضريبة الفعلية لكل فاتورة مبيعات وقت بيعها.
 *
 * التقارير كانت بتحسب الضريبة بالنسبة الحالية من الإعدادات (Avt)، فلما النسبة تتغير (مثلاً 15% ← 0%)
 * كل الفواتير القديمة كانت بتتحسب غلط. هنا بناخد النسبة المحفوظة مع أصناف الفاتورة نفسها:
 *   1) sales.tax_rate لو موجودة
 *   2) وإلا نستنتجها من الضريبة المحفوظة على الأصناف (Added_Value ÷ السعر)
 *   3) وإلا النسبة الحالية من الإعدادات
 */
class SaleTax
{
    private static ?float $current = null;
    private static array $byInvoice = [];

    /** نسبة الضريبة الحالية من الإعدادات */
    public static function current(): float
    {
        if (static::$current === null) {
            $avt = Avt::find(1);
            static::$current = $avt ? (float) $avt->AVT : 0.15;
        }
        return static::$current;
    }

    /** يحوّل 15 أو "0.15" إلى 0.15 */
    public static function normalize($rate): ?float
    {
        if ($rate === null || $rate === '' || !is_numeric($rate)) {
            return null;
        }
        $rate = (float) $rate;
        return $rate > 1 ? $rate / 100 : $rate;
    }

    /** تحميل نسب مجموعة فواتير مرة واحدة (لتقارير فيها فواتير كتير) */
    public static function preloadInvoices($ids): void
    {
        if (is_object($ids) && method_exists($ids, 'pluck')) {
            $ids = $ids->pluck('id');
        }
        $ids = collect($ids)->filter()->map(fn ($id) => (int) $id)->unique()
            ->reject(fn ($id) => array_key_exists($id, static::$byInvoice))->values();

        foreach ($ids->chunk(1000) as $chunk) {
            $rows = DB::table('sales')
                ->whereIn('invoice_id', $chunk->all())
                ->groupBy('invoice_id')
                ->selectRaw('invoice_id, MAX(tax_rate) AS rate, SUM(Added_Value) AS tax, SUM(Unit_Price * quantity) AS net')
                ->get()
                ->keyBy('invoice_id');

            foreach ($chunk as $id) {
                static::$byInvoice[$id] = static::resolve($rows[$id] ?? null);
            }
        }
    }

    /** نسبة الضريبة لفاتورة مبيعات */
    public static function forInvoice($invoiceId): float
    {
        $invoiceId = (int) $invoiceId;
        if (!$invoiceId) {
            return static::current();
        }
        if (!array_key_exists($invoiceId, static::$byInvoice)) {
            static::preloadInvoices([$invoiceId]);
        }
        return static::$byInvoice[$invoiceId];
    }

    /** نسبة الضريبة لسطر صنف (sales) أو مرتجع (return_sales) */
    public static function forLine($row): float
    {
        $rate = static::normalize($row->tax_rate ?? null);
        if ($rate !== null) {
            return $rate;
        }
        return static::forInvoice($row->invoice_id ?? 0);
    }

    private static function resolve($row): float
    {
        if ($row) {
            $rate = static::normalize($row->rate);
            if ($rate !== null) {
                return $rate;
            }
            if ((float) $row->net > 0 && (float) $row->tax > 0) {
                return round((float) $row->tax / (float) $row->net, 4);
            }
        }
        return static::current();
    }
}
