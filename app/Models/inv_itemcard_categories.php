<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_itemcard_categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'comp_id',
        'branchs_id',
        'added_by',
        'updated_by',
        'name',
        'active',
        'created_at',
        'updated_at'
    ];
    public function Add_user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
    public function update_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
