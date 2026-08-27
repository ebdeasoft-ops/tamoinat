<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
        ,'phone',
        'email',
        'comp_name',
        'tax_no',
         'address',
         'notes',
         'Limit_credit',
         'Balance',
    ];

}
