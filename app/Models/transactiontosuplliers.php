<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactiontosuplliers extends Model
{
    use HasFactory;
    protected $fillable = [
        'suplier_id',
        'paidـamount',
        'user_id',
        'Pay_Method_Name',
        'branchs_id',
        'note',
        'currentblance',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supllier()
    {
        return $this->belongsTo(supllier::class, 'suplier_id');
    }
}
