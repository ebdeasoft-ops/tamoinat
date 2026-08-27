<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnLog extends Model
{
    protected $table = 'purchase_return_logs';

    protected $fillable = [
        'orderId',
        'order_details_id',
        'product_id',
        'product_name',
        'purchasing_price',
        'Added_Value',
        'return_quentity',
        'total_returned_value',
        'return_type',
        'branchs_id',
        'returned_by',
        'return_reason',
    ];

    public function orderDetail()
    {
        return $this->belongsTo(orderDetails::class, 'order_details_id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }

    public function returnedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'returned_by');
    }
}