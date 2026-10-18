<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * وردية الكاشير: فتح بمبلغ افتتاحي، وإغلاق بجرد النقدية الفعلية.
 * المبيعات تُحسب من فواتير الكاشير (user_id + الفرع) خلال فترة الوردية — بدون أي تعديل على جدول الفواتير.
 */
class CashierShift extends Model
{
    protected $table = 'cashier_shifts';

    protected $fillable = [
        'user_id', 'branchs_id', 'opened_at', 'closed_at', 'status',
        'opening_cash', 'cash_sales', 'card_sales', 'transfer_sales', 'credit_sales', 'total_sales',
        'tax_total', 'discount_total', 'invoices_count',
        'returns_total', 'cash_returns', 'card_returns', 'returns_count',
        'expected_cash', 'counted_cash', 'difference', 'open_note', 'close_note',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /** تعريف الجدول (يُستخدم في الـ migration، وكذلك لإنشائه تلقائياً لو لم تُشغَّل migrate) */
    public static function createTable(): void
    {
        if (Schema::hasTable('cashier_shifts')) {
            return;
        }
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('branchs_id')->nullable()->index();
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->string('status', 10)->default('open')->index();
            $table->decimal('opening_cash', 14, 2)->default(0);
            $table->decimal('cash_sales', 14, 2)->default(0);
            $table->decimal('card_sales', 14, 2)->default(0);
            $table->decimal('transfer_sales', 14, 2)->default(0);
            $table->decimal('credit_sales', 14, 2)->default(0);
            $table->decimal('total_sales', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->unsignedInteger('invoices_count')->default(0);
            $table->decimal('returns_total', 14, 2)->default(0);
            $table->decimal('cash_returns', 14, 2)->default(0);
            $table->decimal('card_returns', 14, 2)->default(0);
            $table->unsignedInteger('returns_count')->default(0);
            $table->decimal('expected_cash', 14, 2)->default(0);
            $table->decimal('counted_cash', 14, 2)->nullable();
            $table->decimal('difference', 14, 2)->nullable();
            $table->string('open_note', 500)->nullable();
            $table->string('close_note', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }

    public function getNumberAttribute(): string
    {
        return 'SH-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public static function currentFor($userId): ?self
    {
        return static::where('user_id', $userId)->where('status', 'open')->latest('id')->first();
    }

    /** يحسب مبيعات ومرتجعات الكاشير من بداية الوردية حتى $until (أو الآن) */
    public function computeTotals(?Carbon $until = null): array
    {
        $from = $this->opened_at;
        $to = $until ?: ($this->closed_at ?: Carbon::now());

        $inv = invoices::where('user_id', $this->user_id)
            ->when($this->branchs_id, fn ($q) => $q->where('branchs_id', $this->branchs_id))
            ->where('save', 1)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(*) AS cnt,
                COALESCE(SUM(cashamount),0) AS cash, COALESCE(SUM(bankamount),0) AS card,
                COALESCE(SUM(Bank_transfer),0) AS transfer, COALESCE(SUM(creaditamount),0) AS credit,
                COALESCE(SUM(Added_Value),0) AS tax, COALESCE(SUM(discount),0) AS discount')
            ->first();

        $avt = Avt::find(1);
        $vat = $avt ? (float) $avt->AVT : 0.15;

        $ret = DB::table('return_sales')
            ->where('user_id', $this->user_id)
            ->when($this->branchs_id, fn ($q) => $q->where('branch_id', $this->branchs_id))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(DISTINCT invoice_id) AS cnt,
                COALESCE(SUM(return_Unit_Price * return_quantity),0) AS net,
                COALESCE(SUM(returnshabkavalue),0) AS card')
            ->first();

        $returnsTotal = round((float) $ret->net * (1 + $vat), 2);
        $cardReturns = round(min((float) $ret->card, $returnsTotal), 2);
        $cashReturns = round(max($returnsTotal - $cardReturns, 0), 2);

        $cash = round((float) $inv->cash, 2);
        $card = round((float) $inv->card, 2);
        $transfer = round((float) $inv->transfer, 2);
        $credit = round((float) $inv->credit, 2);

        return [
            'invoices_count' => (int) $inv->cnt,
            'cash_sales'     => $cash,
            'card_sales'     => $card,
            'transfer_sales' => $transfer,
            'credit_sales'   => $credit,
            'total_sales'    => round($cash + $card + $transfer + $credit, 2),
            'tax_total'      => round((float) $inv->tax, 2),
            'discount_total' => round((float) $inv->discount, 2),
            'returns_count'  => (int) $ret->cnt,
            'returns_total'  => $returnsTotal,
            'cash_returns'   => $cashReturns,
            'card_returns'   => $cardReturns,
            'expected_cash'  => round((float) $this->opening_cash + $cash - $cashReturns, 2),
        ];
    }
}
