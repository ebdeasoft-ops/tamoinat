<?php

namespace App\Models;

use App\Services\JournalEntry;
use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
    // تحديد اسم الجدول إذا كان مختلفاً عن الجمع التلقائي
    protected $table = 'daily_records';

    // رقم القيد (id) يُحجز من عداد القيود الموحد وليس auto increment
    // راجع App\Services\JournalEntry
    public $incrementing = false;

    // الحقول المسموح بتعبئتها (Mass Assignment)
    protected $fillable = [
        'date',
        'general_notes',
        'total_debit',
        'total_credit',
        'user_id',
        'source_type',
        'source_id',
        'is_auto',
    ];

    protected $casts = [
        'is_auto' => 'boolean',
    ];

    /**
     * سطور القيد (كل السطور تحمل رقم القيد في dely_record)
     */
    public function transactions()
    {
        return $this->hasMany(credittransactions::class, 'dely_record');
    }

    /**
     * علاقة القيد بالمستخدم الذي أنشأه
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** القيود التي لها سطور فعلية فقط (يستبعد رؤوس مستندات محذوفة) */
    public function scopeHasLines($query)
    {
        return $query->whereExists(function ($q) {
            $q->selectRaw('1')
                ->from('credittransactions')
                ->whereColumn('credittransactions.dely_record', 'daily_records.id');
        });
    }

    /** اسم نوع المستند */
    public function getSourceLabelAttribute(): string
    {
        return JournalEntry::LABELS[$this->source_type ?: 'manual'] ?? (string) $this->source_type;
    }
}
