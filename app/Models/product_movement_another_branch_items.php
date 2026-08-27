<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\units;

class product_movement_another_branch_items extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id' ,
        'product_id' ,
        'quantity' ,
        'unitId',
        'cost_per_each_withoud_tax' ,
        'created_at' ,
        'updated_at' 
    ];

    public function product()
       {
           return $this->belongsTo(products::class,'product_id');
       }
       public function unit()
       {
           return $this->belongsTo(units::class,'unitId');
       }

}
