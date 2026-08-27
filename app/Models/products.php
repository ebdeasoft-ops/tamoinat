<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\units;
class products extends Model
{
    use HasFactory;
    protected $fillable=[ 
        'item_type','name','inv_itemcard_categories_id','parent_inv_itemcard_id','does_has_retailunit','retail_uom_id','uom_id','retail_uom_quntToParent','created_at','updated_at','added_by','updated_by','com_code','active','date','item_code','barcode',
        'price','nos_gomla_price','gomla_price','price_retail','nos_gomla_price_retail','gomla_price_retail',
        'cost_price','cost_price_retail','branchs_id','has_fixced_price','QUENTITY','QUENTITY_Retail','prodection_date','QUENTITY_all_Retails','photo','retail_uom_id','All_QUENTITY','expaire_date','prodyction_date','price_with_tax'
        ];
    public function branch()
    {
        return $this->belongsTo(branchs::class,'branchs_id');
    }
    public function retail_Uom()
    {
        return $this->belongsTo(units::class,'retail_uom_id');
    }
    public function Parent_uom()
    {
        return $this->belongsTo(units::class,'uom_id');
    }
    public function parentProduct()
    {
        return $this->belongsTo(products::class,'parent_inv_itemcard_id');
    }
}
