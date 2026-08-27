<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemcardRequestUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
   
    public function rules()
    {
        return [
         'name'=>'required',
         'inv_itemcard_categories_id'=>'required',
        //  'price'=>'required',
        
        //  'cost_price'=>'required',
        //  'price_retail'=>'required_if:does_has_retailunit,1',
        
        //  'cost_price_retail'=>'required_if:does_has_retailunit,1',
         'has_fixced_price'=>'required',
         'active'=>'required',

        ];
    }

    public function messages()
    {
        return [
        'name.required'=>__('home.requird'),
        'inv_itemcard_categories_id.required'=>__('home.requird'),
        'price.required'=>__('home.requird'),
        'nos_gomla_price.required'=>__('home.requird'),
        'gomla_price.required'=>__('home.requird'),
        'cost_price.required'=>__('home.requird'),
        'price_retail.required_if'=>__('home.requird'),
        'has_fixced_price.required'=>__('home.requird'),
        'active.required'=>__('home.requird'),

        ];
    }
    
}