<?php

namespace App\Console\Commands;

use App\Services\JournalEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * ترقيم القيود القديمة (قبل التعديل) التي سُجلت بدون رقم قيد.
 *
 * التجميع:
 *  - سند قبض / صرف: حسب رقم السند.
 *  - فاتورة مبيعات / مشتريات / تسليم: حسب رقم الفاتورة (كل حركات الفاتورة قيد واحد).
 *  - المرتجعات: حسب رقم الفاتورة + وقت العملية (كل عملية إرجاع قيد مستقل).
 *  - غير ذلك: حسب المستخدم + الفرع + وقت التسجيل (نفس الثانية = نفس العملية).
 *
 * الأرقام تُعطى بالترتيب الزمني بعد آخر رقم مستخدم، من نفس عداد القيود.
 *
 *   php artisan journal:backfill            (عرض فقط بدون تعديل)
 *   php artisan journal:backfill --apply    (تنفيذ فعلي)
 */
class BackfillJournalNumbers extends Command
{
    protected $signature = 'journal:backfill {--apply : تنفيذ الترقيم فعلياً (بدونه يعرض النتيجة فقط)}';

    protected $description = 'إعطاء أرقام قيود للحركات المحاسبية القديمة التي ليس لها رقم قيد';

    public function handle(): int
    {
        $groups = [];

        DB::table('credittransactions')
            ->select('id', 'note', 'created_at', 'date_export', 'user_id', 'branchs_id', 'sent_abd_count', 'sent_serf_count', 'debtor', 'creditor')
            ->where(function ($q) {
                $q->whereNull('dely_record')->orWhere('dely_record', 0);
            })
            ->where(function ($q) {
                $q->whereNull('Opening_entry')->orWhere('Opening_entry', 0);
            })
            ->where(function ($q) {
                $q->whereNull('parent_Opening_entry')->orWhere('parent_Opening_entry', 0);
            })
            ->where(function ($q) {
                $q->whereNull('parent_dely_record')->orWhere('parent_dely_record', 0);
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->chunk(2000, function ($rows) use (&$groups) {
                foreach ($rows as $row) {
                    [$key, $type, $ref, $reuse] = $this->groupKey($row);

                    if (!isset($groups[$key])) {
                        $groups[$key] = [
                            'type'  => $type,
                            'ref'   => $ref,
                            'reuse' => $reuse,
                            'first' => (string) $row->created_at,
                            'date'  => $row->date_export ?: substr((string) $row->created_at, 0, 10),
                            'user'  => $row->user_id,
                            'note'  => $row->note,
                            'ids'   => [],
                            'debit' => 0,
                            'credit'=> 0,
                        ];
                    }
                    $groups[$key]['ids'][] = $row->id;
                    $groups[$key]['debit'] += (float) $row->debtor;
                    $groups[$key]['credit'] += (float) $row->creditor;
                }
            });

        $lines = array_sum(array_map(fn ($g) => count($g['ids']), $groups));
        $this->info('حركات بدون رقم قيد: ' . $lines . ' — ستُجمع في ' . count($groups) . ' قيد.');

        $byType = [];
        $unbalanced = 0;
        foreach ($groups as $g) {
            $byType[$g['type']] = ($byType[$g['type']] ?? 0) + 1;
            if (abs($g['debit'] - $g['credit']) > 0.01) {
                $unbalanced++;
            }
        }
        $this->table(['النوع', 'عدد القيود'], collect($byType)->map(fn ($c, $t) => [JournalEntry::LABELS[$t] ?? $t, $c])->values()->all());
        if ($unbalanced) {
            $this->warn("تنبيه: {$unbalanced} قيد غير متوازن في البيانات القديمة (يُرقم كما هو، راجعه يدوياً).");
        }

        if (!$this->option('apply')) {
            $this->line('عرض فقط. للتنفيذ الفعلي: php artisan journal:backfill --apply');
            return self::SUCCESS;
        }

        uasort($groups, fn ($a, $b) => strcmp($a['first'], $b['first']));

        $done = 0;
        foreach (array_chunk($groups, 500, true) as $chunk) {
            DB::transaction(function () use ($chunk, &$done) {
                $seq = DB::table('journal_sequences')->where('name', JournalEntry::SEQUENCE)->lockForUpdate()->first();
                $last = max((int) ($seq->last_number ?? 0), JournalEntry::highestUsedNumber());

                foreach ($chunk as $g) {
                    $number = null;

                    if ($g['reuse']) {
                        $number = DB::table('daily_records')
                            ->where('source_type', $g['type'])->where('source_id', $g['ref'])
                            ->orderByDesc('id')->value('id');
                    }

                    if (!$number) {
                        $number = ++$last;
                        DB::table('daily_records')->insert([
                            'id'            => $number,
                            'date'          => substr((string) $g['date'], 0, 10),
                            'general_notes' => mb_substr($g['type'] === 'auto'
                                ? trim('قيد آلي - ' . trim((string) $g['note'], " -|"))
                                : JournalEntry::describe($g['type'], $g['ref']), 0, 1000),
                            'total_debit'   => 0,
                            'total_credit'  => 0,
                            'user_id'       => $g['user'],
                            'source_type'   => $g['type'],
                            'source_id'     => $g['ref'],
                            'is_auto'       => 1,
                            'created_at'    => $g['first'] ?: now(),
                            'updated_at'    => now(),
                        ]);
                    }

                    foreach (array_chunk($g['ids'], 1000) as $ids) {
                        DB::table('credittransactions')->whereIn('id', $ids)->update(['dely_record' => $number]);
                    }
                    JournalEntry::refreshTotals($number);
                    $done++;
                }

                DB::table('journal_sequences')->updateOrInsert(
                    ['name' => JournalEntry::SEQUENCE],
                    ['last_number' => $last, 'updated_at' => now()]
                );
            });
            $this->line("تم {$done} / " . count($groups));
        }

        $this->info('تم ترقيم القيود القديمة بنجاح.');
        return self::SUCCESS;
    }

    private function groupKey(object $row): array
    {
        $second = substr((string) $row->created_at, 0, 19);

        if ((int) $row->sent_abd_count > 0) {
            return ['receipt_voucher:' . $row->sent_abd_count, 'receipt_voucher', (string) $row->sent_abd_count, true];
        }
        if ((int) $row->sent_serf_count > 0) {
            return ['payment_voucher:' . $row->sent_serf_count, 'payment_voucher', (string) $row->sent_serf_count, true];
        }

        if ($parsed = JournalEntry::parseNote($row->note)) {
            $reuse = in_array($parsed['type'], JournalEntry::REUSABLE, true);
            $key = $parsed['type'] . ':' . $parsed['ref'] . ($reuse ? '' : ':' . $second);
            return [$key, $parsed['type'], $parsed['ref'], $reuse];
        }

        return ['auto:' . $row->user_id . ':' . $row->branchs_id . ':' . $second, 'auto', null, false];
    }
}
