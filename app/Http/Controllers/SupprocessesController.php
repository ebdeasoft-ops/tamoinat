<?php

namespace App\Http\Controllers;

use App\Models\ProductsDamage;
use App\Models\products;
use App\Models\supllier;
use App\Models\customers;
use App\Models\units;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use Illuminate\Support\Facades\DB;


class SupprocessesController extends Controller
{
    //
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.addProduct');
    }

    public function Goupdatecustomer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.updatecustomer');
    }
    public function Goupdatesupplier()
    {

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.updatesupplier');
    }


    public function expenses_reason()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.enpenses_reason');
    }


    public function create_expenses_reason(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'breanchName' => 'required|unique:expenses_reasons,expenses_reason',
        ], [

            'section_name.required' => 'يرجي ادخال غرض الصرف',
            'breanchName.unique' => 'عفوا هذا الغرض تم تسجلة سابقا',


        ]);
        //
        //  return $request;
        $createBranch = DB::table('expenses_reasons')->insert([
            'expenses_reason' => $request->breanchName,
            'expensesAvt' => $request->AVT,
            'created_at' => \Carbon\Carbon::now()->addHours(3)
        ]);
        if ($createBranch != null) {
            session()->flash('create_reason_expense', 'تم انشاء غرض الصرف   بنجاج');
            return view('supProcesses.enpenses_reason');
        } else {
            session()->flash('notcreate', 'حدث مشكلة اثناء انشاء الفرع');
            return view('supProcesses.enpenses_reason');
        }
    }

    public function create_addnewProduct(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $this->validate($request, [
            'product_name_ar' => 'required',
            'Section' => 'required',
            'product_location' => 'required',
            'product_code' => 'required',
            'minmum_quantity_stock_alart' => 'required',
        ]);
        //return $request;

        $newcustomer = products::create(
            [
                'product_name' => $request->product_name_ar,
                'name_en' => $request->product_name_ar,
                'branchs_id' => $request->Section,
                'user_id' => Auth()->User()->id,
                'Product_Location' => $request->product_location,
                'Product_Code' => $request->product_code,
                'Status' => 1,
                'notes' => $request->product_notes,
                'unit' => $request->unit,
                'minmum_quantity_stock_alart' => $request->minmum_quantity_stock_alart,
            ]
        );
        if ($newcustomer != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم اضافة المنتج بنجاح' : 'Product added successfully';

            session()->flash('addProduct', $message);
        }
        return view('supProcesses.addProduct');
    }




    public function addnewProductajax(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $this->validate($request, [
            'product_name_ar' => 'required',
            'Section' => 'required',
            'product_location' => 'required',
            'product_code' => 'required',
            'minmum_quantity_stock_alart' => 'required',
        ]);
        //return $request;

        $newcustomer = products::create(
            [
                'product_name' => $request->product_name_ar,
                'name_en' => $request->product_name_ar,
                'branchs_id' => $request->Section,
                'user_id' => Auth()->User()->id,
                'Product_Location' => $request->product_location,
                'Product_Code' => $request->product_code,
                'Status' => 1,
                'notes' => $request->product_notes,
                'unit' => $request->unit,
                'minmum_quantity_stock_alart' => $request->minmum_quantity_stock_alart,
            ]
        );
        if ($newcustomer != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم اضافة المنتج بنجاح' : 'Product added successfully';

            return [$message];
        } else {
            return 0;
        }
    }




    public function product_movement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.product_movement');
    }
    public function product_damage()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.product damage');
    }



    public function addnewcustomer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.addnewcustomer');
    }
    public function addnewsupplier()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.addnewsupplier');
    }


    public function stockAdjastment()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        return view('supProcesses.stockAdjastment');
    }

    public function stock_update(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

            //   return   LaravelLocalization::getCurrentLocale();
        ;
        $productId = $request->productNo;
        $updatedproduct =    products::where('id', $productId)->update(
            [
                'product_name' => $request->productnameshow,
                'numberofpice' => $request->newquentity,
            ]
        );
        if ($updatedproduct != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل كمية المنتج بنجاح' : "Product quantity has been modified successfully.";
            session()->flash('productupdated', $message);
        }
        return view('supProcesses.stockAdjastment');
    }




    public function update_product_movement(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $productId = $request->product_no;
        $updatedproduct =    products::where('id', $productId)->update(
            [
                'Product_Location' => $request->new_location,
                'product_name' => $request->productnameshow,
                'Product_Code' => $request->productcode,
                'sale_price' => $request->product_price,
                'purchasingـprice' => $request->purachesepice,


            ]
        );
        if ($updatedproduct != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل موقع المنتج بنجاح' : "Product location has been modified successfully.";
            session()->flash('productupdatedlocation', $message);
        }
        return view('supProcesses.product_movement');
    }
    public function product_damage_add(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $unit = units::find($request->unit);
        $productId = $request->productNo;
        $product = products::find($productId);
        if ($unit->is_master == 0) {
            $updatedproduct = products::where('id', $productId)->update(
                [
                    'All_QUENTITY' => ($product->QUENTITY_all_Retails - $request->newquentity) / $product->retail_uom_quntToParent,
                    'QUENTITY' => (int)(($product->QUENTITY_all_Retails - $request->newquentity) / $product->retail_uom_quntToParent),
                    'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $request->newquentity) ) % $product->retail_uom_quntToParent) * $product->retail_uom_quntToParent,
                    'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $request->newquentity),
                ]
            );
            products::where('parent_inv_itemcard_id', $productId)->update(
                [
                    'All_QUENTITY' =>($product->QUENTITY_all_Retails - $request->newquentity),
                ]);
        } else {
            $updatedproduct = products::where('id', $productId)->update(
                [
                    'All_QUENTITY' => ($product->All_QUENTITY - $request->newquentity),
                    'QUENTITY' => (int)($product->All_QUENTITY - $request->newquentity),
                    'QUENTITY_Retail' => ($product->All_QUENTITY - $request->newquentity) -((int)($product->All_QUENTITY - $request->newquentity)),
                    'QUENTITY_all_Retails' => ($product->All_QUENTITY - $request->newquentity)* $product->retail_uom_quntToParent,
                ]
            );
            products::where('parent_inv_itemcard_id', $productId)->update(
                [
                    'All_QUENTITY' => ($product->All_QUENTITY - $request->newquentity)* $product->retail_uom_quntToParent,
                ]);
        }

        ProductsDamage::create([
            'damage_quantity' => $request->newquentity,
            'product_id' => $productId,
            'product_name' => $product->name,
            'branchs_id' => $product->branchs_id,
            'user_id' => Auth()->user()->id,
            'created_at' => \Carbon\Carbon::now()->addHours(3),
        ]);

        if ($updatedproduct != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم اتلاف  المنتج بنجاح' : "Product damage successfully.";
            session()->flash('damageproduct', $message);
        }
        return view('supProcesses.product damage');
    }






    public function getcustomerdata($id)
    {
        $updatecustomerdata = customers::find($id);
        return $updatecustomerdata;
    }
    public function getsupplierdata($id)
    {
        $updatecustomerdata = supllier::find($id);
        return $updatecustomerdata;
    }

    public function updatesupplier(Request $request)
    {


        //  return $request;
        $newcustomer = supllier::find($request->clientnamesearch)->update(
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'comp_name' => $request->name,
                'email' => $request->email ?? "supplier@gamil.com",
                'location' => $request->loction,
                'notes' => $request->notes ?? "لا توجد",
                'TaxـNumber' => $request->TaxـNumber ?? '0'
            ]
        );
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم  تعديل البيانات  بنجاح' : 'Data modified successfully';

        session()->flash('updateseccess', $message);
        return view('supProcesses.updatesupplier');
    }

    public function updatecustomer(Request $request)
    {


        //  return $request;
        $newcustomer = customers::find($request->clientnamesearch)->update(
            [
                'name' => $request->nameclient,
                'comp_name' => $request->nameclient,
                'address' => "Client Address",
                'tax_no' => $request->TaxـNumber ?? 0,
                'phone' =>  $request->phone ?? '05----------',
                'email' => $request->email ?? 'Email@gmail.com',
                'notes' => $request->product_notes ?? "لا توجد ملاحظات ",
                'Limit_credit' => $request->credit_limit,
                'grace_period_in_days' => $request->grace_period_in_days
            ]
        );
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم  تعديل البيانات  بنجاح' : 'Data modified successfully';

        session()->flash('updateseccess', $message);
        return view('supProcesses.updatecustomer');
    }


    public function createnewcustomerajax(Request $request)
    {


        //  return $request;
        $newcustomer = customers::create(
            [
                'name' => $request->name,
                'comp_name' => $request->name,
                'address' => "Client Address",
                'tax_no' => $request->tax_no ?? 0,
                "Balance" => $request->Balance ?? 0,
                'phone' =>  $request->phone ?? '05----------',
                'email' => $request->email ?? 'Email@gmail.com',
                'notes' => $request->notes ?? "لا توجد ملاحظات ",
                'Limit_credit' => $request->Limit_credit,
                'grace_period_in_days' => $request->grace_period_in_days
            ]
        );

        return  $newcustomer;
    }





    public function create_addnewcustomer(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'TaxـNumber' =>  'required | numeric',
            "balance" => 'required | numeric',
            'timeout_periodـinـdays' => 'required | numeric',
            'credit_limit' => 'required| numeric'
        ]);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        // return $request;


        $newcustomer = customers::create(
            [
                'name' => $request->name,
                'comp_name' => $request->name,
                'address' => "Client Address",
                'tax_no' => $request->TaxـNumber ?? 0,
                "Balance" => $request->balance ?? 0,
                'phone' =>  $request->phone ?? '05----------',
                'email' => $request->email ?? 'Email@gmail.com',
                'notes' => $request->product_notes ?? "لا توجد ملاحظات ",
                'Limit_credit' => $request->credit_limit,
                'grace_period_in_days' => $request->grace_period_in_days
            ]
        );
        // return $request;
        if ($newcustomer != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم اضافة العميل بنجاح' : 'Client added successfully';

            session()->flash('newcustomer', $message);
        }
        return view('supProcesses.addnewcustomer');
    }

    public function create_addnewsupplier(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'phone' => 'required',
            'loction' => 'required',
            'TaxـNumber' => 'required|numeric',
        ]);
        // return $request;

        $supllier = supllier::create(
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'comp_name' => $request->name,
                'email' => $request->email,
                'location' => $request->loction,
                'notes' => $request->notes ?? "لا توجد",
                'TaxـNumber' => $request->TaxـNumber,
                'branchs_id' => Auth()->User()->branchs_id
            ]
        );
        if ($supllier != null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم اضافة الموارد بنجاح' : 'Supplier added successfully';

            session()->flash('addnewsupplier', $message);
        }
        //return $request;
        return view('supProcesses.addnewsupplier');
    }

    public function create_addnewsupplierajax(Request $request)
    {


        $supllier = supllier::create(
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'comp_name' => $request->name,
                'email' => $request->email ?? "supplier@gamil.com",
                'location' => $request->loction,
                'notes' => $request->notes ?? "لا توجد",
                'TaxـNumber' => $request->TaxـNumber,
                                'branchs_id' => Auth()->User()->branchs_id

            ]
        );

        return  $supllier;
    }
}
