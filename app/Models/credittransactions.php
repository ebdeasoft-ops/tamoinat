<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class credittransactions extends Model
{
    use HasFactory;
    protected $fillable = [
    'customer_id',
    'user_id'
,'recive_amount',
'note' ,
'currentblance',
    'pay_method',
    'branchs_id',
    'Pay_Method_Name',
    'created_at',
     'updated_at',
];
    public function customer()
    {
        return $this->belongsTo(customers::class,'customer_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
