<?php

namespace App\Services;

use App\Models\DailyRecord;
use App\Models\credittransactions;
use Illuminate\Support\Facades\DB;

/**
 * ترقيم القيود المحاسبية الموحد.
 *
 * - كل عملية (فاتورة / مرتجع / مصروف / سند / إهلاك / قيد يدوي) لها قيد واحد برقم واحد،
 *   وكل سطور القيد في credittransactions تحمل نفس الرقم في العمود dely_record.
 * - رأس القيد هو سجل في daily_records ورقمه (id) هو رقم القيد نفسه.
 * - الأرقام تأتي من عداد واحد (journal_sequences) يُقفل بـ SELECT ... FOR UPDATE،
 *   فلا تتكرر، ولا يعاد استخدام رقم محذوف، ولو فشلت العملية (Rollback) يرجع العداد
 *   مع باقي العملية فلا تحدث فجوة في الترقيم.
 */
class JournalEntry
{
    public const SEQUENCE = 'journal_entries';

    /** أنواع المستندات وأسماؤها في بيان القيد */
    public const LABELS = [
        'manual'           => 'قيد يدوي',
        'sales_invoice'    => 'فاتورة مبيعات',
        'sales_return'     => 'مرتجع مبيعات',
        'purchase_invoice' => 'فاتورة مشتريات',
        'purchase_return'  => 'مرتجع مشتريات',
        'delivery_invoice' => 'فاتورة تسليم',
        'delivery_return'  => 'مرتجع تسليمات',
        'receipt_voucher'  => 'سند قبض',
        'payment_voucher'  => 'سند صرف',
        'expense'          => 'مصروف',
        'depreciation'     => 'إهلاك',
        'cash_to_bank'     => 'تحويل نقدية إلى البنك',
        'opening_stock'    => 'مخزون افتتاحي',
        'auto'             => 'عملية آلية',
    ];

    /**
     * الأنواع التي يعاد فيها استخدام رقم قيد المستند عند تعديله
     * (المستند له رقم ثابت، والتعديل يعيد بناء سطور نفس القيد).
     * المرتجعات ليست منها: كل عملية إرجاع قيد مستقل حتى لو على نفس الفاتورة.
     */
    public const REUSABLE = [
        'sales_invoice', 'purchase_invoice', 'delivery_invoice',
        'receipt_voucher', 'payment_voucher', 'expense', 'depreciation',
    ];

    /** المستند الجاري ترحيله في هذا الطلب */
    protected static ?array $context = null;

    /** القيود التي تم تخصيصها في هذا الطلب: مفتاح السياق => رقم القيد */
    protected static array $resolved = [];

    // ------------------------------------------------------------------
    //  واجهة الاستخدام من الكنترولرز
    // ------------------------------------------------------------------

    /**
     * تحديد المستند الذي ستنتمي له السطور التالية.
     * الرقم لا يُحجز هنا؛ يُحجز عند إنشاء أول سطر فعلياً (فلا يضيع رقم لو لم تُنشأ سطور).
     *
     * @param string      $type   أحد مفاتيح LABELS
     * @param mixed       $refId  رقم المستند (رقم الفاتورة / السند ...)
     * @param bool|null   $reuse  إعادة استخدام قيد المستند الموجود (افتراضياً حسب REUSABLE)
     */
    public static function begin(string $type, $refId = null, ?bool $reuse = null, ?string $description = null): void
    {
        $ref = ($refId === null || $refId === '') ? null : (string) $refId;
        $reuse = $reuse ?? in_array($type, self::REUSABLE, true);

        static::$context = [
            'type'        => $type,
            'ref'         => $ref,
            'reuse'       => $reuse && $ref !== null,
            'description' => $description ?? static::describe($type, $ref),
            // كل begin بدون إعادة استخدام = قيد جديد
            'key'         => 'ctx:' . $type . ':' . $ref . ($reuse ? '' : ':' . uniqid('', true)),
        ];
    }

    /** إنهاء سياق المستند الحالي */
    public static function end(): void
    {
        static::$context = null;
    }

    /** مسح كل الحالة (للأوامر الطويلة / الاختبارات) */
    public static function reset(): void
    {
        static::$context = null;
        static::$resolved = [];
    }

    /** رقم قيد السياق الحالي (يحجزه لو لم يُحجز بعد) — للإدخالات عبر Query Builder */
    public static function currentNumber(array $lineAttributes = []): int
    {
        $line = new credittransactions();
        $line->forceFill($lineAttributes);
        return static::numberFor($line);
    }

    /**
     * حجز رقم قيد يدوي جديد وإنشاء رأسه.
     */
    public static function createManual(array $attributes): DailyRecord
    {
        $number = static::allocate([
            'date'          => $attributes['date'] ?? now()->toDateString(),
            'general_notes' => $attributes['general_notes'] ?? null,
            'total_debit'   => $attributes['total_debit'] ?? 0,
            'total_credit'  => $attributes['total_credit'] ?? 0,
            'user_id'       => $attributes['user_id'] ?? auth()->id(),
            'source_type'   => 'manual',
            'source_id'     => null,
            'is_auto'       => 0,
        ]);

        return DailyRecord::find($number);
    }

    /** هل القيد آلي (ناتج عن مستند) ولا يُعدل من شاشة القيود؟ */
    public static function isAuto($number): bool
    {
        if (!$number) {
            return false;
        }
        return (bool) DailyRecord::whereKey($number)->value('is_auto');
    }

    /** رسالة منع التعديل لقيد آلي */
    public static function lockedMessage($number): string
    {
        $record = DailyRecord::find($number);
        $doc = $record ? ($record->general_notes ?: static::describe($record->source_type, $record->source_id)) : '';

        return "القيد رقم ({$number}) قيد آلي مرتبط بمستنده ({$doc}) ولا يمكن تعديله أو حذفه من شاشة القيود اليومية. "
             . "عدّل المستند نفسه وسيتحدث القيد تلقائياً.";
    }

    public static function describe(?string $type, $refId = null): string
    {
        $label = self::LABELS[$type] ?? ($type ?: 'عملية');
        return $refId !== null && $refId !== '' ? "قيد {$label} رقم {$refId}" : "قيد {$label}";
    }

    // ------------------------------------------------------------------
    //  يُستدعى من موديل credittransactions
    // ------------------------------------------------------------------

    /**
     * يُستدعى قبل إدخال أي سطر: يضع رقم القيد لو لم يكن موجوداً.
     */
    public static function stamp(credittransactions $line): void
    {
        if ((int) $line->dely_record > 0) {
            return; // قيد يدوي أو رقم محدد مسبقاً
        }
        if ((int) $line->Opening_entry > 0 || (int) $line->parent_Opening_entry > 0) {
            return; // القيود الافتتاحية لها ترقيمها الخاص
        }
        if ((int) $line->parent_dely_record > 0) {
            return; // سطر انعكاس للحساب الأب داخل قيد يدوي قديم
        }

        $line->dely_record = static::numberFor($line);
    }

    /** إعادة حساب إجماليات رأس القيد من سطوره */
    public static function refreshTotals($number): void
    {
        $number = (int) $number;
        if ($number <= 0) {
            return;
        }

        DB::table('daily_records')->where('id', $number)->update([
            'total_debit'  => DB::raw("(SELECT COALESCE(SUM(debtor),0) FROM credittransactions WHERE dely_record = {$number})"),
            'total_credit' => DB::raw("(SELECT COALESCE(SUM(creditor),0) FROM credittransactions WHERE dely_record = {$number})"),
        ]);
    }

    /** قراءة نوع ورقم المستند من بيان السطر (يُستخدم احتياطياً وفي ترحيل البيانات القديمة) */
    public static function parseNote(?string $note): ?array
    {
        if (!$note) {
            return null;
        }

        $patterns = [
            // الأكثر تحديداً أولاً
            'delivery_return'  => '/مرتجع\s*تسليمات\s*رقم\s*:?\s*(\d+)/u',
            'sales_return'     => '/مرتجع\s*مبيعات\s*رقم\s*:?\s*(\d+)/u',
            'purchase_return'  => '/مرتجع\s*مشتريات\s*فاتورة\s*رقم\s*:?\s*(\d+)/u',
            'delivery_invoice' => '/فاتورة\s*تسليم\s*منتج\s*رقم\s*:?\s*(\d+)/u',
            'purchase_invoice' => '/فاتورة\s*مشتريات[^0-9|]*?رقم\s*:?\s*(\d+)/u',
            'sales_invoice'    => '/فاتورة\s*مبيعات\s*رقم\s*:?\s*(\d+)/u',
            'receipt_voucher'  => '/سند\s*قبض[^0-9]*?(\d+)/u',
            'payment_voucher'  => '/(?:سند\s*صرف|صرف\s*سند)[^0-9]*?(\d+)/u',
        ];

        foreach ($patterns as $type => $regex) {
            if (preg_match($regex, $note, $m)) {
                return ['type' => $type, 'ref' => $m[1]];
            }
        }

        return null;
    }

    // ------------------------------------------------------------------
    //  التنفيذ الداخلي
    // ------------------------------------------------------------------

    protected static function numberFor(credittransactions $line): int
    {
        $meta = static::$context;

        if (!$meta) {
            // احتياطي 1: سند قبض/صرف معروف رقمه من أعمدته
            if ((int) $line->sent_abd_count > 0) {
                $meta = ['type' => 'receipt_voucher', 'ref' => (string) $line->sent_abd_count];
            } elseif ((int) $line->sent_serf_count > 0) {
                $meta = ['type' => 'payment_voucher', 'ref' => (string) $line->sent_serf_count];
            } else {
                // احتياطي 2: من بيان السطر
                $meta = static::parseNote($line->note);
            }

            if ($meta) {
                $meta['reuse'] = in_array($meta['type'], self::REUSABLE, true);
                $meta['description'] = static::describe($meta['type'], $meta['ref']);
                $meta['key'] = 'note:' . $meta['type'] . ':' . $meta['ref'];
            } else {
                // احتياطي 3: كل عملية (طلب) غير معروفة = قيد واحد
                $meta = [
                    'type'        => 'auto',
                    'ref'         => null,
                    'reuse'       => false,
                    'description' => trim('قيد آلي - ' . trim((string) $line->note, " -|")),
                    'key'         => 'request',
                ];
            }
        }

        $key = $meta['key'];

        if (isset(static::$resolved[$key]) && DailyRecord::whereKey(static::$resolved[$key])->exists()) {
            return static::$resolved[$key];
        }

        $number = null;

        if ($meta['reuse']) {
            $number = DailyRecord::where('source_type', $meta['type'])
                ->where('source_id', $meta['ref'])
                ->orderByDesc('id')
                ->value('id');
        }

        if (!$number) {
            $number = static::allocate([
                'date'          => static::lineDate($line),
                'general_notes' => mb_substr($meta['description'], 0, 1000),
                'total_debit'   => 0,
                'total_credit'  => 0,
                'user_id'       => auth()->id() ?? $line->user_id,
                'source_type'   => $meta['type'],
                'source_id'     => $meta['ref'],
                'is_auto'       => 1,
            ]);
        }

        return static::$resolved[$key] = (int) $number;
    }

    /**
     * حجز الرقم التالي وإنشاء رأس القيد في نفس الـ Transaction.
     */
    protected static function allocate(array $header): int
    {
        return DB::transaction(function () use ($header) {
            $seq = DB::table('journal_sequences')->where('name', self::SEQUENCE)->lockForUpdate()->first();

            if (!$seq) {
                DB::table('journal_sequences')->insertOrIgnore([
                    'name'        => self::SEQUENCE,
                    'last_number' => static::highestUsedNumber(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $seq = DB::table('journal_sequences')->where('name', self::SEQUENCE)->lockForUpdate()->first();
            }

            $next = (int) $seq->last_number + 1;

            // حماية إضافية: لا نعطي رقماً مستخدماً بالفعل في أي مكان
            while (
                DB::table('daily_records')->where('id', $next)->exists()
                || DB::table('credittransactions')->where('dely_record', $next)->exists()
            ) {
                $next++;
            }

            DB::table('journal_sequences')->where('name', self::SEQUENCE)->update([
                'last_number' => $next,
                'updated_at'  => now(),
            ]);

            $record = new DailyRecord();
            $record->forceFill(array_merge($header, ['id' => $next]));
            $record->save();

            return $next;
        });
    }

    public static function highestUsedNumber(): int
    {
        return (int) max(
            (int) DB::table('daily_records')->max('id'),
            (int) DB::table('credittransactions')->max('dely_record')
        );
    }

    protected static function lineDate(credittransactions $line): string
    {
        if (!empty($line->date_export)) {
            return substr((string) $line->date_export, 0, 10);
        }
        if (!empty($line->created_at)) {
            return substr((string) $line->created_at, 0, 10);
        }
        return now()->toDateString();
    }
}
