<?php

namespace App\Http\Controllers;

use App\Models\product_movement_another_branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use App\Models\product_movement_another_branch_items;
use App\Models\products;
use App\Models\units;

class ProductMovementAnotherBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('supProcesses.send_product_to_branch');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // return $request;
        $product = products::find($request->product_no);
        if ($request->order_id == 0) {
            $product_movement_another_branch = product_movement_another_branch::create(
                [
                    'branch_from' => Auth()->user()->branchs_id,
                    'branch_to' => $request->branch,
                    'user_from' => Auth()->user()->id,
                    'reciveInvoiceNumber' => 1,
                    'user_to' => $request->userfrom,
                    'Totalcost' => $request->thecostProduct * $request->quentity,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
            $product_movement_another_branch_items = product_movement_another_branch_items::create(
                [
                    'order_id' => $product_movement_another_branch->id,
                    'product_id' => $request->product_no,
                    'quantity' => $request->quentity,
                    'unitId' => $request->unit,
                    'cost_per_each_withoud_tax' => $request->thecostProduct,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
            $product = products::find($request->product_no);
            $unit = units::find($request->unit);
            if ($unit->is_master == 0) {
                $updatedproduct = products::where('id', $request->product_no)->update(
                    [
                        'All_QUENTITY' => ($product->QUENTITY_all_Retails - $request->quentity) / $product->retail_uom_quntToParent,
                        'QUENTITY' => (int)(($product->QUENTITY_all_Retails - $request->quentity) / $product->retail_uom_quntToParent),
                        'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $request->quentity)) % $product->retail_uom_quntToParent) * $product->retail_uom_quntToParent,
                        'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $request->quentity),
                    ]
                );
            } else {
                $updatedproduct = products::where('id', $request->product_no)->update(
                    [
                        'All_QUENTITY' => ($product->All_QUENTITY - $request->quentity),
                        'QUENTITY' => (int)($product->All_QUENTITY - $request->quentity),
                        'QUENTITY_Retail' => ($product->All_QUENTITY - $request->quentity) - ((int)($product->All_QUENTITY - $request->quentity)),
                        'QUENTITY_all_Retails' => ($product->All_QUENTITY - $request->quentity) * $product->retail_uom_quntToParent,
                    ]
                );
            }

            $product_movement_another_branch_items = product_movement_another_branch_items::where('order_id', $product_movement_another_branch->id)->get();
            $orderDate = [];
            $count = 0;
            foreach ($product_movement_another_branch_items as $product_movement_another_branch_item) {
                $count++;

                $product = products::find($product_movement_another_branch_item->product_id);

                $dataitems[] = [
                    "count" => $count,
                    'productname' => $product->name,
                    "product_code" => $product->barcode,
                    "unit" => $product_movement_another_branch_item->unit->name,
                    "details_items_no" => $product_movement_another_branch_item->id,
                    'cost' => $product_movement_another_branch_item->cost_per_each_withoud_tax,
                    'quantity' => $product_movement_another_branch_item->quantity,
                    'total' => $product_movement_another_branch_item->quantity * $product_movement_another_branch_item->cost_per_each_withoud_tax,

                ];
            }

            $data = [
                "orderData" => $product_movement_another_branch,
                "orderItems" => $dataitems
            ];
            return $data;
        } else {
            $product_movement_another_branch_data = product_movement_another_branch::find($request->order_id);
            $product_movement_another_branch = product_movement_another_branch::where('id', $request->order_id)->update(
                [

                    'Totalcost' => $product_movement_another_branch_data->Totalcost + ($request->thecostProduct * $request->quentity),
                    'created_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
            $product_movement_another_branch_items = product_movement_another_branch_items::create(
                [
                    'order_id' => $request->order_id,
                    'product_id' => $request->product_no,
                    'quantity' => $request->quentity,
                    'unitId' => $request->unit,

                    'cost_per_each_withoud_tax' => $request->thecostProduct,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
            $product = products::find($request->product_no);
            $unit = units::find($request->unit);
            if ($unit->is_master == 0) {
                $updatedproduct = products::where('id', $request->product_no)->update(
                    [
                        'All_QUENTITY' => ($product->QUENTITY_all_Retails - $request->quentity) / $product->retail_uom_quntToParent,
                        'QUENTITY' => (int)(($product->QUENTITY_all_Retails - $request->quentity) / $product->retail_uom_quntToParent),
                        'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $request->quentity)) % $product->retail_uom_quntToParent) * $product->retail_uom_quntToParent,
                        'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $request->quentity),
                    ]
                );
            } else {
                $updatedproduct = products::where('id', $request->product_no)->update(
                    [
                        'All_QUENTITY' => ($product->All_QUENTITY - $request->quentity),
                        'QUENTITY' => (int)($product->All_QUENTITY - $request->quentity),
                        'QUENTITY_Retail' => ($product->All_QUENTITY - $request->quentity) - ((int)($product->All_QUENTITY - $request->quentity)),
                        'QUENTITY_all_Retails' => ($product->All_QUENTITY - $request->quentity) * $product->retail_uom_quntToParent,

                    ]
                );
            }
            $product_movement_another_branch_items = product_movement_another_branch_items::where('order_id', $request->order_id)->get();

            $dataitems = [];
            $count = 0;
            foreach ($product_movement_another_branch_items as $product_movement_another_branch_item) {
                $count++;

                $product = products::find($product_movement_another_branch_item->product_id);

                $dataitems[] = [
                    "count" => $count,
                    'productname' => $product->name,
                    "product_code" => $product->barcode,
                    "unit" => $product_movement_another_branch_item->unit->name,
                    "details_items_no" => $product_movement_another_branch_item->id,
                    'cost' => $product_movement_another_branch_item->cost_per_each_withoud_tax,
                    'quantity' => $product_movement_another_branch_item->quantity,
                    'total' => $product_movement_another_branch_item->quantity * $product_movement_another_branch_item->cost_per_each_withoud_tax,

                ];
            }

            $data = [
                "orderData" => $product_movement_another_branch_data,
                "orderItems" => $dataitems
            ];
            return $data;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function findinvoiceMovmevt($id)
    {
        $result = product_movement_another_branch::find($id);
        $data = [
            'barnch_id' => $result->branchfrom->id,
            'barnch_name' => $result->branchfrom->name,
            'user_id' => $result->userfrom->id,
            'user_name' => $result->userfrom->name,

        ];
        $product_movement_another_branch_items = product_movement_another_branch_items::where('order_id', $id)->get();
        $dataitems = [];
        $count = 0;
        foreach ($product_movement_another_branch_items as $product_movement_another_branch_item) {
            $count++;

            $product = products::find($product_movement_another_branch_item->product_id);

            $dataitems[] = [
                "count" => $count,
                'productname' => $product->name,
                "product_code" => $product->barcode,
                "unit" => $product_movement_another_branch_item->unit->name,
                "details_items_no" => $product_movement_another_branch_item->id,
                'cost' => $product_movement_another_branch_item->cost_per_each_withoud_tax,
                'quantity' => $product_movement_another_branch_item->quantity,
                'total' => $product_movement_another_branch_item->quantity * $product_movement_another_branch_item->cost_per_each_withoud_tax,

            ];
        }
        $data = [
            'senderdata' => $data,
            'orderItems' => $dataitems
        ];
        return $data;
    }
    public function store(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->invoiceId == '-') {
            session()->flash('nodataprint', '');

            return view('supProcesses.recive_product_from_another_branch');
        }
        $notregisterproductCount = 0;
        //
        $product_movement_another_branch_items = product_movement_another_branch_items::where('order_id', $request->invoiceId)->get();
        foreach ($product_movement_another_branch_items as $item) {
            $productrecive = products::find($item->product_id);
            $updateProduct = products::where('branchs_id', Auth()->user()->branchs_id)->where('barcode', $productrecive->barcode)->first();
            if ($updateProduct != null) {
                $unit = units::find($item->unitId);
                if ($unit->is_master == 0) {
                    $findparent=  products::where('id', $updateProduct->parent_inv_itemcard_id)->first();
                    products::where('id', $updateProduct->parent_inv_itemcard_id)->update(
                        [
                            'All_QUENTITY' => ( $findparent->All_QUENTITY + $item->quantity),
                            'QUENTITY' => (int)( $findparent->All_QUENTITY + $item->quantity),
                            'QUENTITY_Retail' => ( $findparent->All_QUENTITY + $item->quantity) - ((int)( $findparent->All_QUENTITY + $item->quantity)),
                            'QUENTITY_all_Retails' => ( $findparent->All_QUENTITY + $item->quantity) *  $findparent->retail_uom_quntToParent,
                            "cost_price"=>$item->cost_per_each_withoud_tax ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax*  $findparent->retail_uom_quntToParent
        
                        ]
                    );
                    $updatedproduct = products::where('id', $updateProduct->id)->update(
                        [
                            'All_QUENTITY' => ($updateProduct->QUENTITY_all_Retails + $item->quantity),
                            "cost_price"=>$item->cost_per_each_withoud_tax* $updateProduct->retail_uom_quntToParent ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax
                        ]
                    );
                } else {
                    $updatedproduct = products::where('id', $updateProduct->id)->update(
                        [
                            'All_QUENTITY' => ($updateProduct->All_QUENTITY + $item->quantity),
                            'QUENTITY' => (int)($updateProduct->All_QUENTITY + $item->quantity),
                            'QUENTITY_Retail' => ($updateProduct->All_QUENTITY + $item->quantity) - ((int)($updateProduct->All_QUENTITY + $item->quantity)),
                            'QUENTITY_all_Retails' => ($updateProduct->All_QUENTITY + $item->quantity) * $updateProduct->retail_uom_quntToParent,
                            "cost_price"=>$item->cost_per_each_withoud_tax ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax* $updateProduct->retail_uom_quntToParent
        
                        ]
                    );
                    $updatedproduct = products::where('parent_inv_itemcard_id', $updateProduct->id)->update(
                        [
                            'All_QUENTITY' => ($updateProduct->All_QUENTITY + $item->quantity) * $updateProduct->retail_uom_quntToParent,
                            "cost_price"=>$item->cost_per_each_withoud_tax* $updateProduct->retail_uom_quntToParent ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax
                        ]
                    );
                }
            } else {
                $notregisterproductCount++;

             if($productrecive->parent_inv_itemcard_id!=0){
                $parentproduct = products::find($productrecive->parent_inv_itemcard_id);



                $newproductParent = products::create(
                    [
                        'item_code' => $parentproduct->item_code,
                        'barcode' =>  $parentproduct->barcode,
                        'name' =>  $parentproduct->name,
                        'item_type' => $parentproduct->item_type,
                        'inv_itemcard_categories_id' =>  $parentproduct->inv_itemcard_categories_id,
                        'parent_inv_itemcard_id' => 0,
                        'does_has_retailunit' => $parentproduct->does_has_retailunit,
                        'retail_uom_id' =>  $parentproduct->retail_uom_id,
                        'uom_id' =>  $parentproduct->uom_id,
                        'retail_uom_quntToParent' =>  $parentproduct->retail_uom_quntToParent,
                        'added_by' => Auth()->user()->id,
                        'updated_by' => Auth()->user()->id,
                        'active' =>  $parentproduct->active,
                        'date' => \Carbon\Carbon::now()->addHours(3),
                        'branchs_id' =>Auth()->user()->branchs_id,
                        'price' =>  $parentproduct->price,
                        'price_retail' => $parentproduct->price_retail,
                        'photo' =>  $parentproduct->photo,
                        'has_fixced_price' =>  $parentproduct->has_fixced_price,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                        'Product_Location' => 'Transfer',
                        'minmum_quantity_stock_alart' =>  $parentproduct->minmum_quantity_stock_alart,
                    ]
                );
                $newproducts = products::create(
                    [
                        'item_code' => $productrecive->item_code,
                        'barcode' => $productrecive->barcode,
                        'name' => $productrecive->name,
                        'item_type' => $productrecive->item_type,
                        'inv_itemcard_categories_id' => $productrecive->inv_itemcard_categories_id,
                        'parent_inv_itemcard_id' => $newproductParent->id,
                        'does_has_retailunit' => $productrecive->does_has_retailunit,
                        'retail_uom_id' => $productrecive->retail_uom_id,
                        'uom_id' => $productrecive->uom_id,
                        'retail_uom_quntToParent' => $productrecive->retail_uom_quntToParent,
                        'added_by' => Auth()->user()->id,
                        'updated_by' => Auth()->user()->id,
                        'active' => $productrecive->active,
                        'date' => \Carbon\Carbon::now()->addHours(3),
                        'branchs_id' =>Auth()->user()->branchs_id,
                        'price' => $productrecive->price,
                        'price_retail' => $productrecive->price_retail,
                        'photo' => $productrecive->photo,
                        // 'cost_price' => $productrecive->cost_price,
                        // 'cost_price_retail' => $productrecive->cost_price_retail,
                        'has_fixced_price' => $productrecive->has_fixced_price,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                        'Product_Location' => 'Transfer',
                        'minmum_quantity_stock_alart' => $productrecive->minmum_quantity_stock_alart,
                    ]
                );
             }else{
                
                $newproducts = products::create(
                    [
                        'item_code' => $productrecive->item_code,
                        'barcode' => $productrecive->barcode,
                        'name' => $productrecive->name,
                        'item_type' => $productrecive->item_type,
                        'inv_itemcard_categories_id' => $productrecive->inv_itemcard_categories_id,
                        'parent_inv_itemcard_id' => $productrecive->parent_inv_itemcard_id,
                        'does_has_retailunit' => $productrecive->does_has_retailunit,
                        'retail_uom_id' => $productrecive->retail_uom_id,
                        'uom_id' => $productrecive->uom_id,
                        'retail_uom_quntToParent' => $productrecive->retail_uom_quntToParent,
                        'added_by' => Auth()->user()->id,
                        'updated_by' => Auth()->user()->id,
                        'active' => $productrecive->active,
                        'date' => \Carbon\Carbon::now()->addHours(3),
                        'branchs_id' =>Auth()->user()->branchs_id,
                        'price' => $productrecive->price,
                        'price_retail' => $productrecive->price_retail,
                        // 'cost_price' => $productrecive->cost_price,
                        // 'cost_price_retail' => $productrecive->cost_price_retail,
                        'photo' => $productrecive->photo,
                        'has_fixced_price' => $productrecive->has_fixced_price,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                        'Product_Location' => 'Transfer',
                        'minmum_quantity_stock_alart' => $productrecive->minmum_quantity_stock_alart,
                    ]
                );
             }
        
               
              
            
                $unit = units::find($item->unitId);
                if ($unit->is_master == 0) {
                    $updatedproductdata = products::where('id', $newproductParent->id)->update(
                        [
                            'All_QUENTITY' => ( $item->quantity) / $productrecive->retail_uom_quntToParent,
                            'QUENTITY' => (int)(( $item->quantity) / $productrecive->retail_uom_quntToParent),
                            'QUENTITY_Retail' => ((($item->quantity)) % $productrecive->retail_uom_quntToParent) * $productrecive->retail_uom_quntToParent,
                            'QUENTITY_all_Retails' => ( $item->quantity),
                            "cost_price"=>$item->cost_per_each_withoud_tax* $productrecive->retail_uom_quntToParent ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax
                        ]
                    );
                    $updatedproduct = products::where('id', $newproducts->id)->update(
                        [
                            'All_QUENTITY' => ( $item->quantity),
                            'QUENTITY' => 0,
                            'QUENTITY_Retail' => 0,
                            'QUENTITY_all_Retails' => 0,
                            "cost_price"=>$item->cost_per_each_withoud_tax* $productrecive->retail_uom_quntToParent ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax
                        ]
                    );
                } else {
                    $updatedproduct = products::where('id', $newproducts->id)->update(
                        [
                            'All_QUENTITY' => ( $item->quantity),
                            'QUENTITY' => (int)( $item->quantity),
                            'QUENTITY_Retail' => ($item->quantity) - ((int)( $item->quantity)),
                            'QUENTITY_all_Retails' => ( $item->quantity) * $productrecive->retail_uom_quntToParent,
                            "cost_price"=>$item->cost_per_each_withoud_tax ,
                            "cost_price_retail"=>$item->cost_per_each_withoud_tax* $productrecive->retail_uom_quntToParent
        
                        ]
                    );
                }
            }
        }
      

        product_movement_another_branch::where('id', $request->invoiceId)->update(
            [

                'reciveInvoiceNumber' => 10,
                'created_at' => \Carbon\Carbon::now()->addHours(3),

            ]
        );
        if ($notregisterproductCount > 0) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم تزويد المخزون بمنتجات المستلمة بنجاح وتحديث سعر التكلفة. و تم انشاء " . $notregisterproductCount . " منتجات لم يتم عثور علي ارقمهم واضافة الكمية و سعر التكلفة بنجاح وشكرا" : "Inventory has been replenished with successfully received products and the cost price has been updated. And has been created" . $notregisterproductCount . "Products whose number was not found and the quantity and cost price were added successfully, thank you.";
            session()->flash('createnewproduct', $message);
        } else {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم تزويد المخزون بمنتجات المستلمة بنجاح وتحديث سعر التكلفة. " : "Inventory has been replenished with successfully received products and the cost price has been updated.";
            session()->flash('confirmed', $message);
        }
        $data = $request->invoiceId;
        return view('supProcesses.recive_product_from_another_branch', compact('data'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product_movement_another_branch  $product_movement_another_branch
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = 0;
        return view('supProcesses.recive_product_from_another_branch', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\product_movement_another_branch  $product_movement_another_branch
     * @return \Illuminate\Http\Response
     */
    public function print_Transfer_items(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        if ($request->sprint_invoice_number == null) {
            session()->flash('nodataprint', '');
            return view('supProcesses.send_product_to_branch');
        }
        $product_movement_another_branch_data = product_movement_another_branch::find($request->sprint_invoice_number);
        $items = product_movement_another_branch_items::where('order_id', $product_movement_another_branch_data->id)->get();
        $data = [
            "invoice" => $product_movement_another_branch_data,
            "itemsdetails" => $items
        ];
        return view('supProcesses.print_send_product', compact('data'));
    }
    public function print_Recive_items(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        if ($request->sprint_invoice_number == null) {
            session()->flash('nodataprint', '');
            return view('supProcesses.recive_product_from_another_branch');
        }
        $product_movement_another_branch_data = product_movement_another_branch::find($request->sprint_invoice_number);
        $items = product_movement_another_branch_items::where('order_id', $product_movement_another_branch_data->id)->get();
        $data = [
            "invoice" => $product_movement_another_branch_data,
            "itemsdetails" => $items
        ];
        return view('supProcesses.print_recive_product', compact('data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product_movement_another_branch  $product_movement_another_branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product_movement_another_branch $product_movement_another_branch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product_movement_another_branch  $product_movement_another_branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_movement_another_branch)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        $item = product_movement_another_branch_items::find($product_movement_another_branch);
        $order = product_movement_another_branch::find($item->order_id);
        $product = products::find($item->product_id);
        // products::find($item->product_id)->Update([

        //     'numberofpice' => $product->numberofpice + $item->quantity,
        // ]);
        $unit = units::find($item->unitId);
        if ($unit->is_master == 0) {
            $updatedproduct = products::where('id', $item->product_id)->update(
                [
                    'All_QUENTITY' => ($product->QUENTITY_all_Retails + $item->quantity) / $product->retail_uom_quntToParent,
                    'QUENTITY' => (int)(($product->QUENTITY_all_Retails + $item->quantity) / $product->retail_uom_quntToParent),
                    'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails + $item->quantity)) % $product->retail_uom_quntToParent) * $product->retail_uom_quntToParent,
                    'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails + $item->quantity),
                ]
            );
        } else {
            $updatedproduct = products::where('id', $item->product_id)->update(
                [
                    'All_QUENTITY' => ($product->All_QUENTITY + $item->quantity),
                    'QUENTITY' => (int)($product->All_QUENTITY + $item->quantity),
                    'QUENTITY_Retail' => ($product->All_QUENTITY + $item->quantity) - ((int)($product->All_QUENTITY + $item->quantity)),
                    'QUENTITY_all_Retails' => ($product->All_QUENTITY + $item->quantity) * $product->retail_uom_quntToParent,

                ]
            );
        }




        product_movement_another_branch::where('id', $item->order_id)->Update(
            [

                'Totalcost' => $order->Totalcost - ($item->cost_per_each_withoud_tax * $item->quantity),
                'created_at' => \Carbon\Carbon::now()->addHours(3),

            ]
        );
        $product_movement_another_branch_items = product_movement_another_branch_items::where('id', $product_movement_another_branch)->first();
        $product_movement_another_branch_items->delete();

        $product_movement_another_branch_items = product_movement_another_branch_items::where('order_id', $order->id)->get();
        $count = 0;
        $dataitems = [];
        foreach ($product_movement_another_branch_items as $product_movement_another_branch_item) {
            $count++;

            $product = products::find($product_movement_another_branch_item->product_id);

            $dataitems[] = [
                "count" => $count,
                'productname' => $product->product_name,
                "product_code" => $product->Product_Code,
                "details_items_no" => $product_movement_another_branch_item->id,
                "unit" => $product_movement_another_branch_item->unit->name,
                'cost' => $product_movement_another_branch_item->cost_per_each_withoud_tax,
                'quantity' => $product_movement_another_branch_item->quantity,
                'total' => $product_movement_another_branch_item->quantity * $product_movement_another_branch_item->cost_per_each_withoud_tax,

            ];
        }

        $data = [
            "orderData" => $order,
            "orderItems" => $dataitems
        ];
        return $data;
    }
}
