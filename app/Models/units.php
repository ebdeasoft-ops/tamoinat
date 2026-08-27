<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class units extends Model
{
    use HasFactory;
    protected $fillable = [
        'comp_id',
        'branchs_id',
        'added_by',
        'is_master',
        'name',
        'active',
        'created_at',
        'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
