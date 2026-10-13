<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\employee;

class Increaseـor_deduction_employee extends Model
{
    use HasFactory;
    protected $table = 'increase_or_deduction_employees';
    protected $fillable = [
        'employee_id',
        'increase',
         'deduction',
       'created_at',
];


    public function employee()
    {
        return $this->belongsTo(employee::class,'employee_id');
    }
}
