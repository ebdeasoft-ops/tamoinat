<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ترقيم موحد لكل القيود (يدوية + آلية من الفواتير والسندات ...).
 *
 * - journal_sequences: عداد أرقام القيود (يُقفل عند الحجز فلا تكرار ولا فجوات عند الفشل).
 * - daily_records: تعريف مصدر القيد (نوع المستند ورقمه) وهل هو آلي.
 * - فهرس على credittransactions.dely_record لسرعة دفتر اليومية وكشف الحساب.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('journal_sequences')) {
            Schema::create('journal_sequences', function (Blueprint $table) {
                $table->string('name', 50)->primary();
                $table->unsignedBigInteger('last_number')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('daily_records', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_records', 'source_type')) {
                $table->string('source_type', 50)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('daily_records', 'source_id')) {
                $table->string('source_id', 50)->nullable()->after('source_type');
            }
            if (!Schema::hasColumn('daily_records', 'is_auto')) {
                $table->boolean('is_auto')->default(false)->after('source_id');
            }
        });

        if (!$this->hasIndex('daily_records', 'daily_records_source_index')) {
            Schema::table('daily_records', function (Blueprint $table) {
                $table->index(['source_type', 'source_id'], 'daily_records_source_index');
            });
        }

        if (!$this->hasIndex('credittransactions', 'credittransactions_dely_record_index')) {
            Schema::table('credittransactions', function (Blueprint $table) {
                $table->index('dely_record', 'credittransactions_dely_record_index');
            });
        }

        // القيود الموجودة كلها يدوية
        DB::table('daily_records')->whereNull('source_type')->update(['source_type' => 'manual', 'is_auto' => 0]);

        // بداية العداد = أعلى رقم قيد مستخدم حالياً (في رؤوس القيود أو السطور)
        $highest = (int) max(
            (int) DB::table('daily_records')->max('id'),
            (int) DB::table('credittransactions')->max('dely_record')
        );

        // لو العداد موجود مسبقاً لا ننزل به أبداً (حتى لا يعاد استخدام رقم محذوف)
        $current = (int) DB::table('journal_sequences')->where('name', 'journal_entries')->value('last_number');

        DB::table('journal_sequences')->updateOrInsert(
            ['name' => 'journal_entries'],
            ['last_number' => max($highest, $current), 'created_at' => now(), 'updated_at' => now()]
        );
    }

    private function hasIndex(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }

    public function down(): void
    {
        Schema::table('credittransactions', function (Blueprint $table) {
            $table->dropIndex('credittransactions_dely_record_index');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropIndex('daily_records_source_index');
            $table->dropColumn(['source_type', 'source_id', 'is_auto']);
        });

        Schema::dropIfExists('journal_sequences');
    }
};
