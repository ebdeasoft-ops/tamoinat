<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\supllier;

use App\Models\resource_purchases;
use App\Models\customers;
use App\Models\User;
use App\Models\Avt;
use App\Models\order_price_from_supplier;
use App\Models\order_price_from_supplier_items;
use Illuminate\Http\Request;
use App\Models\orderDetails;
use App\Models\orderTosupllier;
use Carbon\Carbon;
use App\Models\offer_price_to_customer_items;
use App\Models\offer_price_to_customer;
use App\Models\invoices;
use App\Models\units;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class ProductsController extends Controller
{
    public function getByCodenew( $barcode)
    {
        $data = products::where('branchs_id',  Auth()->User()->branchs_id)->where('barcode', $barcode)->first();
        if ($data == NULL) {
            return 0;
        }
       
        return json_decode($data->toJson(), true);
    }
    public function ChooseProductpaginatenewSale_new($branchs_id, $currentrow)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = products::where('branchs_id', $branchs_id)->paginate(20);
        return view('ajax_choose_product_sale_new', compact('data'))->with('currentrow',$currentrow);
        
    }


    public function searchChooseProductpaginatenewSale_new($searchtext, $branchs_id, $currentrow)
    {                   

        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];
        if ($branchs_id == '-') {
            $data = products::where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->paginate(20);

            return view('ajax_choose_product_sale_new', compact('data'))->with('currentrow', $currentrow);
        }
        $data = products::where('branchs_id', $branchs_id)->where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchs_id)->paginate(20);
        
        return view('ajax_choose_product_sale_new', compact('data'))->with('currentrow', $currentrow);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $allcustomers = customers::get();
        $allproduct = products::where('branchs_id', Auth()->User()->branchs_id)->get();
        $data = [
            "allcustomers" => $allcustomers,
            "allproduct" => $allproduct
        ];
        //return $data;
        return view('products.transactions', compact('data'));
    }




    public function showAllProducts()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('showAllProducts');
    }
    public function ShowAllNotifications()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        return view('Notifications_Products');
    }


    public function profile()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        return view('profile.show');
    }

    public function previousPurchasesInvoices()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        $data = resource_purchases::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->paginate(20);
        return view('previousPurchasInvoicesNew', compact(('data')));
    }





    public function previousSalesInvoices()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 0)->paginate(20);
        return view('previousSalesInvoices', compact(('data')));
    }
    public function getAllinvicesajax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 0)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }
    public function getAllinvicesapurchasesjax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = resource_purchases::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices_purchases', compact('data'));
    }
    public function searchAllInvoicespaginatenew($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 0)->where('created_at', 'LIKE', '%' . $date . '%')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }
    public function searchAllInvoicespaginatenewpurchase($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = resource_purchases::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('created_at', 'LIKE', $date . '%')->paginate(20);
        return view('ajax_Recent_Invoices_purchases', compact('data'));
    }
    public function searchaboutinvoiceByIdfunction($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 0)->where('id', $date)->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }

    public function searchaboutinvoiceByIdfunctionpurchases($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = resource_purchases::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('orderId', $date)->paginate(20);
        return view('ajax_Recent_Invoices_purchases', compact('data'));
    }
    public function searchaboutReciptByIdfunction($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 1)->where('id', $date)->paginate(20);
        return view('ajax_Recent_Recipts', compact('data'));
    }
    public function getAllRecieptsjax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Recipts', compact('data'));
    }
    public function searchAllRecieptspaginatenew($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 1)->where('created_at', 'LIKE', '%' . $date . '%')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }

    public function previousRecieptInvoices()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        return view('previousRecieptInvoices');
    }


    /**
     * Show the form for creating a new resource.
     *
     * 
     * 
     * @return \Illuminate\Http\Response
     */

    public function changePaymethodIPurchases($orderId, $paymentMethod)
    {

        $resource_purchases = resource_purchases::where('orderId', $orderId)->first();
        $order_tosuplliers = orderTosupllier::find($orderId);
        // if ($resource_purchases->Pay_Method_Name == "Credit") {
        //     $supllier = supllier::find($order_tosuplliers->suplier_id);
        //     supllier::where('id', $order_tosuplliers->suplier_id)->update(
        //         [
        //             'In_debt' => $supllier->In_debt - $resource_purchases->In_debt,
        //         ]
        //     );
        // }


        resource_purchases::where('orderId', $orderId)->update(
            [
                'Pay_Method_Name' => $paymentMethod,

            ]
        );
        orderTosupllier::where('id', $orderId)->update(
            [
                'Limit_credit' => $paymentMethod,

            ]
        );


        // if ($paymentMethod == "Credit") {
        //     $supllier = supllier::find($order_tosuplliers->suplier_id);
        //     supllier::where('id', $order_tosuplliers->suplier_id)->update(
        //         [
        //             'In_debt' => $supllier->In_debt + $resource_purchases->In_debt,
        //         ]
        //     );
        // }
        return 'Done';
    }


    public function AddproductPriceToCustomer(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avt = Avt::find(1);

        //  return $request;
        $customertid = $request->clientnamesearch;
        $productid = $request->productNo;
        if ($request->orderNo == null) {
            $create_order = offer_price_to_customer::create(
                [
                    'customer_id' => $customertid,
                    'branchs_id' => Auth()->User()->branchs_id,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
            $create_order_price_from_supplier_items = offer_price_to_customer_items::create(
                [
                    'product_id' => $productid,
                    'quantity' => $request->quentity,
                    "PriceWithoudTax" => $request->saleprice,
                    "discount" => $request->discount,
                    'order_id' => $create_order->id,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),


                ]
            );

            $itemsRequest = offer_price_to_customer_items::where('order_id', $create_order->id)->get();
            $ListProducts = [];
            $count = 0;
            foreach ($itemsRequest as $item) {
                $count++;
                $product = products::find($item->product_id);
                $ListProducts[] = [
                    "count" => $count,
                    "productCode" => $product->barcode,
                    "productName" => $product->name,
                    "sale_price" => $item->PriceWithoudTax,
                    "discount" => $item->discount,
                    "order_id" => $item->order_id,
                    "quantity" => $item->quantity,
                    "added_value" => $avt->AVT * $item->PriceWithoudTax

                ];
            }
            return $ListProducts;
            return view('products.OfferPricesTocustomer', compact('itemsRequest'))->with('supplierdata', $request);
        } else {
            //  return 'agin';

            $create_order_price_from_supplier_items = offer_price_to_customer_items::create(
                [
                    'product_id' => $productid,
                    'quantity' => $request->quentity,
                    "PriceWithoudTax" => $request->saleprice,
                    "discount" => $request->discount,
                    'order_id' => $request->orderNo,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );


            $itemsRequest = offer_price_to_customer_items::where('order_id', $request->orderNo)->get();
            $ListProducts = [];
            $count = 0;
            foreach ($itemsRequest as $item) {
                $count++;
                $product = products::find($item->product_id);
                $ListProducts[] = [
                    "count" => $count,
                    "productCode" => $product->barcode,
                    "productName" => $product->name,
                    "sale_price" => $item->PriceWithoudTax,
                    "discount" => $item->discount,
                    "order_id" => $item->order_id,
                    "quantity" => $item->quantity,
                    "added_value" => $avt->AVT * $item->PriceWithoudTax
                ];
            }
            return $ListProducts;
            return view('products.OfferPricesTocustomer', compact('itemsRequest'))->with('supplierdata', $request);
        }
    }

    public function showAllproductpaginatepurchase($branchId)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        $products = products::where('branchs_id', $branchId)->paginate(50);
        $resultProducts = [];


        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->barcode,
                'name' => $product->name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name,
            ];
        }
        $products['otherdata'] = $resultProducts;
        return $products;
    }

    public function searchAllproductpaginatepurchase($branchId, $searchtext)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $products = products::where('name', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchId)->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchId)->paginate(100);
        $resultProducts = [];


        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->barcode,
                'name' => $product->name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name,
            ];
        }
        $products['otherdata'] = $resultProducts;
        return $products;
    }

    public function order_price_from_suppliers(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $request;
        $suppliertid = $request->supplierId;
        $productid = $request->productNo;
        if ($request->orderNo == null) {
            $create_order = order_price_from_supplier::create(
                [
                    'suplier_id' => $suppliertid,
                    'branchs_id' => Auth()->User()->branchs_id,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
            $create_order_price_from_supplier_items = order_price_from_supplier_items::create(
                [
                    'product_id' => $productid,
                    'quantity' => $request->quentity,
                    'order_id' => $create_order->id,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),


                ]
            );
            $itemsRequest = order_price_from_supplier_items::where('order_id', $create_order->id)->get();
            $ListProducts = [];
            $count = 0;
            foreach ($itemsRequest as $item) {
                $count++;
                $product = products::find($item->product_id);
                $ListProducts[] = [
                    "count" => $count,
                    "productCode" => $product->barcode,
                    "productName" => $product->name,
                    "productQuantity" => $item->quantity,
                    "order_id" => $item->order_id
                ];
            }
            return $ListProducts;
            //  return view('products.Requestpricesofproductsfromsupplier',compact('itemsRequest'))->with('supplierdata',$request);

        } else {
            $create_order_price_from_supplier_items = order_price_from_supplier_items::create(
                [
                    'product_id' => $productid,
                    'quantity' => $request->quentity,
                    'order_id' => $request->orderNo,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );


            $itemsRequest = order_price_from_supplier_items::where('order_id', $request->orderNo)->get();
            $ListProducts = [];
            $count = 0;
            foreach ($itemsRequest as $item) {
                $count++;
                $product = products::find($item->product_id);
                $ListProducts[] = [
                    "count" => $count,
                    "productCode" => $product->barcode,
                    "productName" => $product->name,
                    "productQuantity" => $item->quantity,
                    "order_id" => $item->order_id

                ];
            }
            return $ListProducts;
            // return view('products.Requestpricesofproductsfromsupplier',compact('itemsRequest'))->with('supplierdata',$request);

        }
    }


    public function print_order_perice_to_customer($product_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        $itemsRequest = offer_price_to_customer_items::where('order_id', $product_id)->get();

        return view('products.print_order_perice_to_customer', compact('itemsRequest'));
    }
    public function printOrderPriceFromSupplier($product_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $itemsRequest = order_price_from_supplier_items::where('order_id', $product_id)->get();

        return view('products.print_order_perice_from_supplier', compact('itemsRequest'));
    }

    public function print_order_perice_to_customerByPost(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        $itemsRequest = offer_price_to_customer_items::where('order_id', $request->OrderNoprint)->get();

        return view('products.print_order_perice_to_customer', compact('itemsRequest'));
    }
    public function printOrderPriceFromSupplierBypost(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $itemsRequest = order_price_from_supplier_items::where('order_id', $request->OrderNoprint)->get();

        return view('products.print_order_perice_from_supplier', compact('itemsRequest'));
    }

    public function purchases()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(50);

        //return $data;
        return view('products.purchases', compact('products'));
    }


    public function Purchase_returns()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = [];
        return view('products.purchase_return', compact('data'));

        $orderTosupllier = orderTosupllier::get();
        //purchase_return
    }







    public function Purchase_returns_Data(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderOwner = orderTosupllier::find($request->clientName);
        $resource_purchases = resource_purchases::where('orderId', $request->clientName)->where('save', 1)->first();
        $orderdetails = orderDetails::where('order_owner', $request->clientName)->get();
        if ($resource_purchases == null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? '  لم يتم العثور علي فاتورة بهذة الرقم' : 'No invoice with this number was found';

            session()->flash('notfountreturnpuracheseproduct', $message);
            $data = [];
            return view('products.purchase_return', compact('data'));
        }
        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,

            'product' => $orderdetails
        ];
        //return $data;
        return view('products.purchase_return', compact('data'));
    }


    public function printReturnpurchases($id)
    {
        //return $id;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $orderOwner = orderTosupllier::find($id);
        $resource_purchases = resource_purchases::where('orderId', $id)->first();

        $orderdetails = orderDetails::where('order_owner', $id)->where('returns_purchase', "!=", 0)->get();
        if ($orderOwner == null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? '  لم يتم العثور علي فاتورة بهذة الرقم' : 'No invoice with this number was found';

            session()->flash('notfountreturnpuracheseproduct', $message);
            $data = [];
            return view('products.purchase_return', compact('data'));
        }
        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'product' => $orderdetails,
            'resource_purchases' => $resource_purchases
        ];
        // return $data;
        return view('products.print_purchase_return', compact('data'));
    }

    public function create(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        $t = time();

        $clientphone = $request["phonenumber"];
        ;
        $clientname = $request["clientName"];
        $clientaddress = $request["address"];
        $clientnote = $request["notes"];
        $product = products::get();
        $data = [
            "date" => date("Y-m-d", $t),
            'product' => $product,
            'clientnote' => $clientnote,
            'clientphone' => $clientphone,
            'clientname' => $clientname,
            'clientaddress' => $clientaddress,
            'print' => 'print quentity'

        ];
        return view('products.print_products', compact('data'));
    }



    public function getProductsPriceFromSupplier()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);

        //return $data;
        return view('products.Requestpricesofproductsfromsupplier', compact('products'))->with('order_id', "-");
    }

    public function showProductsPrice(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        //
        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);

        return view('products.OfferPricesTocustomer', compact('products'))->with('order_id', '-');
    }

    public function print_all_products_price(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $t = time();


        $product = products::get();
        $data = [
            "date" => date("Y-m-d", $t),
            'product' => $product,
        ];
        return view('products.print_all_products_price', compact('data'));
    }
    public function printProductPriceToCustomer($request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $t = time();

        $clientphone = $request["phonenumber"];
        ;
        $clientname = $request["clientName"];
        $clientaddress = $request["address"];
        $clientnote = $request["notes"];
        $product = products::get();
        $data = [
            "date" => date("Y-m-d", $t),
            'product' => $product,
            'clientnote' => $clientnote,
            'clientphone' => $clientphone,
            'clientname' => $clientname,
            'clientaddress' => $clientaddress,
            'print' => 'printprice'
        ];
        return view('products.print_products', compact('data'));
    }

    public function printProductPrice(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $t = time();

        $clientphone = $request["phonenumber"];
        ;
        $clientname = $request["clientName"];
        $clientaddress = $request["address"];
        $clientnote = $request["notes"];
        $product = products::get();
        $data = [
            "date" => date("Y-m-d", $t),
            'product' => $product,
            'clientnote' => $clientnote,
            'clientphone' => $clientphone,
            'clientname' => $clientname,
            'clientaddress' => $clientaddress,
            'print' => 'printprice'
        ];
        return view('products.print_products', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($products)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $product = products::find($products);
        return $product;
    }


    public function Addproducttopurchases(Request $request)
    {
        $avtPurcheseRate = Avt::find(2);
        $orderdatareturn = [];
        $clientNo = $request->clientnamesearch;

        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->orderNo == null) {






            $createorder = orderTosupllier::create(
                [
                    'user_id' => Auth()->user()->id,
                    'suplier_id' => $clientNo,
                    'Limit_credit' => $request->pay,
                    'purchaseـamount' => $request->quentity * $request->quentityprice,
                    'added_value' => $request->quentity * $request->quentityprice * $avtPurcheseRate->AVT,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );


            $orderdatareturn = resource_purchases::create(
                [
                    "Other expenses" => $request->Otherexpenses,
                    "shipping fee" => $request->shippingfee,
                    "purchase_invoice_no" => 0,
                    "Purchase_invoice_number" => $request->Purchase_invoice_number_supplier,
                    'orderId' => $createorder->id,
                    'suplier_id' => $clientNo,
                    'In_debt' => ($request->quentity * $request->quentityprice) + ($request->quentityprice * $avtPurcheseRate->AVT * $request->quentity),
                    'Pay_Method_Name' => $request->pay,
                    'notes' => $request->notes,
                    'branchs_id' => $request->branchs_id,
                    'created_at' => $request->data,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );

            // if ($request->pay == 'Credit') {
            //     $supllierIn_debt =  supllier::find($clientNo);
            //     supllier::where('id', $clientNo)->update(
            //         [
            //             'In_debt' => ($request->quentity * $request->quentityprice) + ($request->quentityprice * $avtPurcheseRate->AVT * $request->quentity) + $supllierIn_debt->In_debt,
            //         ]
            //     );
            // }
        } else {



            $getrecientorder = orderTosupllier::where('id', $request->orderNo)->first();

            $createorder = orderTosupllier::where('id', $request->orderNo)->update(
                [

                    'purchaseـamount' => $getrecientorder->purchaseـamount + ($request->quentity * $request->quentityprice),
                    'added_value' => $getrecientorder->added_value + ($request->quentity * $request->quentityprice * $avtPurcheseRate->AVT)

                ]
            );
            $resourceـpurchases = resource_purchases::where('orderId', $request->orderNo)->first();
            $orderdatareturn = resource_purchases::where('orderId', $request->orderNo)->first();
            resource_purchases::where('orderId', $request->orderNo)->update(
                [
                    'In_debt' => $resourceـpurchases->In_debt + ($request->quentity * $request->quentityprice) + ($request->quentityprice * $avtPurcheseRate->AVT * $request->quentity)
                ]
            );


            // if ($request->pay == 'Credit') {
            //     $supllierIn_debt =  supllier::find($clientNo);
            //     supllier::where('id', $clientNo)->update(
            //         [
            //             'In_debt' => ($request->quentity * $request->quentityprice) + ($request->quentityprice * $avtPurcheseRate->AVT * $request->quentity) + $supllierIn_debt->In_debt,
            //         ]
            //     );
            // }
        }
        $productno = 0;
        if ($request->productNo == null) {
            $productno = $request->productname;
        }


        $getProduct = products::where('id', $productno)->first();
        $productno = $getProduct->id;
        $Added_value = $request->quentityprice * $avtPurcheseRate->AVT;
        $product_Price = $request->quentityprice;

        $orderdetails = orderDetails::create(
            [
                'product_id' => $productno,
                'order_owner' => $createorder->id ?? $request->orderNo,
                'product_name' => $request->productnameshow,
                'purchasingـprice' => $product_Price,
                'Added_Value' => $Added_value,
                'sale_price' => $request->sale_price,
                'unit' => $request->unit,
                'prodection_date' => $request->proDate,
                'expaire_date' => $request->expDate,
                'numberofpice' => $request->quentity,
                'created_at' => date("Y-m-d"),
                'updated_at' => date("Y-m-d"),
            ]
        );
        // $updateProduct = products::find($productno);
        // products::where('id', $productno)->Update([
        //     'purchasingـprice' => $request->quentityprice,
        //     'sale_price' => $request->sale_price,
        //     'numberofpice' => $updateProduct->numberofpice + $request->quentity,
        // ]);
        $unit = units::find($request->unit);

        $recentsupllier = supllier::find($clientNo);
        $orderId = $orderdetails->order_owner;
        $orderdetails = orderDetails::where('order_owner', $orderdetails->order_owner)->get();
        // return $request;
        $allProdctsD = [];
        $resourceـpurchases = resource_purchases::where('orderId', $orderId)->first();

        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->numberofpice,
                'unit' => $unit->name,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id
            ];
        }
        $data = [
            "Purchase_invoice_number_supplier" => $orderdatareturn['Purchase_invoice_number'],
            "Other expenses" => $orderdatareturn['Other expenses'],
            "shipping fee" => $orderdatareturn['shipping fee'],
            'orderNo' => $orderId,
            "purchase_invoice_no" => $orderdatareturn['purchase_invoice_no'],
            'pay' => $request->pay,
            'In_debt' => $resourceـpurchases->In_debt,
            'discount' => $resourceـpurchases->discount,
            'recentsupllier' => $recentsupllier,
            "product" => $allProdctsD
        ];
        return $data;
        return view('products.purchases', compact('data'));
    }

    public function get_all_products_in_orderto_supplier($id)
    {
        return orderDetails::where('order_owner', $id)->get();
    }


    public function AddproducttoSupllier(Request $request)
    {
        //return  $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->orderNo == null) {
            $clientNo = 0;
            $clientNo = $request->clientnamesearch;

            $createorder = orderTosupllier::create(
                [
                    'user_id' => Auth()->user()->id,
                    'suplier_id' => $clientNo,

                ]
            );
        }


        $productno = 0;
        if ($request->productNo == "ادخل  رقم المنتج") {
            $productno = $request->productname;
        } else {
            $productno = $request->productNo;
        }
        $avtPurcheseRate = Avt::find(2);
        $Added_value = $request->quentityprice * $avtPurcheseRate->AVT;
        $product_Price = $request->quentityprice;
        $product = products::find($productno);
        $orderdetails = orderDetails::create(
            [
                'product_id' => $productno,
                'product_name' => $product->name,
                'order_owner' => $createorder->id ?? $request->orderNo,
                'name' => $request->productnameshow,
                'purchasingـprice' => $product_Price,
                'unit' => $product->item_type,
                'Added_Value' => $Added_value,
                'numberofpice' => $request->quentity,
                'created_at' => date("Y-m-d"),
                'updated_at' => date("Y-m-d"),
            ]
        );
        //  return $orderdetails;

        $orderdetails = orderDetails::where('order_owner', $orderdetails->order_owner)->get();
        $listOfProduct = [];
        $count = 0;
        $totalAdded_value = 0;
        $totalPrice = 0;

        foreach ($orderdetails as $orderitem) {
            $totalAdded_value += $orderitem->numberofpice * $orderitem->Added_Value;
            $totalPrice += $orderitem->numberofpice * $orderitem->purchasingـprice;
            $count++;
            $listOfProduct[] = [
                "count" => $count,
                "productCode" => $orderitem->productData->barcode,
                "product_name" => $orderitem->productData->name,
                "product_id" => $orderitem->product_id,
                "quantity" => $orderitem->numberofpice,
                "purchasingـprice" => $orderitem->purchasingـprice,
                "Added_Value" => $orderitem->Added_Value,
                "total" => ($orderitem->numberofpice * $orderitem->Added_Value) + ($orderitem->numberofpice * $orderitem->purchasingـprice),
                "orderNo" => $orderitem->order_owner,
                "totalAdded_Value" => $totalAdded_value,
                "totalPrice" => $totalPrice
            ];
        }

        return $listOfProduct;
        return view('products.Purchase_order_of_resources', compact('data'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function goToSale()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(50);
        // return $products;
        return view('products.sales', compact('products'));
    }
    public function goToSaleByPage()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(50);
        return $products;
    }

    public function searchaboutproduct($searchtext)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $products = products::where('name', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', Auth()->User()->branchs_id)->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', Auth()->User()->branchs_id)->paginate(50);

        return $products;
    }


    public function showAllproductpaginate()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        $products = products::paginate(50);
        $resultProducts = [];


        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->barcode,
                'name' => $product->name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name,
            ];
        }
        $products['otherdata'] = $resultProducts;
        return $products;
    }

    public function searchAllproductpaginate($searchtext)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $products = products::where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->paginate(100);
        $resultProducts = [];


        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->barcode,
                'name' => $product->name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name,
            ];
        }
        $products['otherdata'] = $resultProducts;
        return $products;
    }
    public function Allproductpaginatenew()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $data = products::paginate(20);



        return view('ajax_search', compact('data'));
    }
    public function makeTotalDiscontpurchases($invoiceId, $discountValue)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = resource_purchases::where('orderId', $invoiceId)->first();

        resource_purchases::where('orderId', $invoiceId)->update(
            [
                'discount' => $data->discount + $discountValue ?? 0,
                'In_debt' => $data->In_debt
            ]
        );
        $avtPurcheseRate = Avt::find(2);
        $orderDetails = orderDetails::where('order_owner', $invoiceId)->get();
        $totalpurcgaseswithoudTax = 0;
        foreach ($orderDetails as $item) {
            $totalpurcgaseswithoudTax += $item->purchasingـprice * $item->numberofpice;
        }
        $data = resource_purchases::where('orderId', $invoiceId)->first();
        $dataN = [
            'discount' => $discountValue ?? 0,
            'totalpurcgaseswithoudTax' => $totalpurcgaseswithoudTax,
            'Addedvalue' => $totalpurcgaseswithoudTax * $avtPurcheseRate->AVT,
            'In_debt' => $data->In_debt
        ];
        return $dataN;
    }
    public function cancelInvoiceDiscontpurcgases($invoiceId)
    {
        $data = resource_purchases::where('orderId', $invoiceId)->first();

        resource_purchases::where('orderId', $invoiceId)->update(
            [
                'discount' => 0,
                'In_debt' => $data->In_debt

            ]
        );
        $data = resource_purchases::where('orderId', $invoiceId)->first();
        $avtPurcheseRate = Avt::find(2);

        $orderDetails = orderDetails::where('order_owner', $invoiceId)->get();
        $totalpurcgaseswithoudTax = 0;
        foreach ($orderDetails as $item) {
            $totalpurcgaseswithoudTax += $item->purchasingـprice * $item->numberofpice;
        }
        $data = resource_purchases::where('orderId', $invoiceId)->first();
        $dataN = [
            'discount' => 0,
            'totalpurcgaseswithoudTax' => $totalpurcgaseswithoudTax,
            'Addedvalue' => $totalpurcgaseswithoudTax * $avtPurcheseRate->AVT,
            'In_debt' => $data->In_debt
        ];
        return $dataN;
    }

    public function searchAllproductpaginatenew($searchtext)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $data = products::where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->paginate(20);



        return view('ajax_search', compact('data'));
    }
    public function searchChooseProductpaginatenew($searchtext, $branchs_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $data = products::where('branchs_id', $branchs_id)->where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchs_id)->paginate(20);
        return view('ajax_choose_product', compact('data'));
    }
    public function ChooseProductpaginatenew($branchs_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = products::where('branchs_id', $branchs_id)->paginate(20);
        // return $data;

        return view('ajax_choose_product', compact('data'));
    }


    public function searchChooseProductpaginatenewSale($searchtext, $branchs_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];
        if ($branchs_id == '-') {
            $data = products::where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->paginate(20);
            return view('ajax_choose_product_sale', compact('data'));
        }
        $data = products::where('branchs_id', $branchs_id)->where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchs_id)->paginate(20);
        return view('ajax_choose_product_sale', compact('data'));
    }
    public function ChooseProductpaginatenewSale($branchs_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($branchs_id == '-') {
            $data = products::paginate(20);
            return view('ajax_choose_product_sale', compact('data'));
        }
        $data = products::where('branchs_id', $branchs_id)->paginate(20);
        // return $data;

        return view('ajax_choose_product_sale', compact('data'));
    }



    public function searchaboutproductwithBranchId($searchtext, $branchId)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $products = products::where('name', 'LIKE', '%' . $searchtext . '%')->orwhere('barcode', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchId)->paginate(50);

        return $products;
    }
    public function goToReceipt()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        $products = products::paginate(50);
        // return $products;
        return view('products.Receipt', compact('products'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function savepurchase($request, $payment, $supplier)
    {
        resource_purchases::where('orderId', $request)->update(
            [
                'save' => 1,
                'suplier_id' => $supplier,
                'Pay_Method_Name' => $payment,
                'created_at' => \Carbon\Carbon::now()->addHours(3),

            ]
        );
        $orderOwner = orderTosupllier::find($request)->update(
            [

                'Limit_credit' => $payment
            ]
        );
        ;

        orderDetails::where('order_owner', $request)->where('numberofpice', '!=', 0)->update(
            ['save' => 1,]
        );
        $orderOwner = orderTosupllier::find($request)->update(
            ['suplier_id' => $supplier,]
        );
        foreach (orderDetails::where('order_owner', $request)->where('numberofpice', '!=', 0)->get() as $item) {

            $product = products::find($item->product_id);
            // products::where('id', $item->product_id)->Update([
            //     'purchasingـprice' => $item->purchasingـprice,
            //     // 'sale_price' => $item->sale_price,
            //     'numberofpice' => $updateProduct->numberofpice + $item->numberofpice,
            // ]);
            $unit = units::find($item->unit);

            // if ($unit->is_master == 0) {
            //     $updatedproduct = products::where('id', $item->product_id)->update(
            //         [
            //             'All_QUENTITY' => ($product->QUENTITY_all_Retails +$item->numberofpice	) / $product->retail_uom_quntToParent,
            //             'QUENTITY' => (int)(($product->QUENTITY_all_Retails +$item->numberofpice) / $product->retail_uom_quntToParent),
            //             'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails + $item->numberofpice) ) % $product->retail_uom_quntToParent) * $product->retail_uom_quntToParent,
            //             'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails + $item->numberofpice),
            //             'cost_price_retail'=>$item->purchasingـprice,
            //             "cost_price"=>$item->purchasingـprice*$product->retail_uom_quntToParent,
            //             "price"=> $item->sale_price *$product->retail_uom_quntToParent,
            //             "price_retail"=>  $item->sale_price ,
            //             'prodection_date'=>$item->prodection_date,
            //             'expaire_date'=>$item->expaire_date,
            //         ]
            //     );
            //     products::where('parent_inv_itemcard_id', $item->product_id)->update(
            //     [
            //         "cost_price"=>$item->purchasingـprice,
            //         "price"=> $item->sale_price,
            //         'All_QUENTITY' => ($product->QUENTITY_all_Retails + $item->numberofpice),
            //         'QUENTITY' => 0,
            //         'QUENTITY_Retail' => 0,
            //         'QUENTITY_all_Retails' => 0,

            //     ]
            //     );
            // } 
            // else {

            $updatedproduct = products::where('id', $item->product_id)->update(
                [
                    'All_QUENTITY' => ($product->All_QUENTITY + $item->numberofpice),
                    'QUENTITY' => (int) ($product->All_QUENTITY + $item->numberofpice),
                    'QUENTITY_Retail' => ($product->All_QUENTITY + $item->numberofpice) - ((int) ($product->All_QUENTITY + $item->numberofpice)),
                    'QUENTITY_all_Retails' => ($product->All_QUENTITY + $item->numberofpice) * $product->retail_uom_quntToParent,
                    'cost_price_retail' => $product->retail_uom_quntToParent == 0 ? 0 : $item->purchasingـprice / $product->retail_uom_quntToParent,
                    "cost_price" => $item->purchasingـprice,
                    "price" => $item->sale_price,
                    "price_retail" => $product->retail_uom_quntToParent == 0 ? 0 : $item->sale_price / $product->retail_uom_quntToParent,
                    'prodection_date' => $item->prodection_date,
                    'expaire_date' => $item->expaire_date,
                ]
            );
            $child = products::where('parent_inv_itemcard_id', $item->product_id)->first();
            if ($child != null) {
                products::where('parent_inv_itemcard_id', $item->product_id)->update(
                    [
                        "cost_price" => $item->purchasingـprice / $product->retail_uom_quntToParent,
                        "price" => $item->sale_price / $product->retail_uom_quntToParent,
                        'All_QUENTITY' => ($product->All_QUENTITY) + $item->numberofpice * $product->retail_uom_quntToParent,

                    ]
                );
            }










        }

        $resource_purchases = resource_purchases::where('orderId', $request)->first();
        if ($payment == "Credit") {
            $supplier = supllier::find($resource_purchases->suplier_id);
            //return $supplier->In_debt + $resource_purchases->In_debt;
            supllier::where('id', $resource_purchases->suplier_id)->update(
                [
                    'In_debt' => $supplier->In_debt + $resource_purchases->In_debt - $resource_purchases->discount
                ]
            );
        }

        return 1;
    }
    public function update(Request $request)
    {

        //

        //   return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::where('product_id', $request->id)
            ->where('order_owner', $request->ordernumber)->first();
        //   return $orderDetails;
        $resource_purchases = resource_purchases::where('orderId', $request->ordernumber)->first();
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $request->ordernumber)->update(
                [
                    'recoveredـpieces' => $resource_purchases->recoveredـpieces + $request->return_quentity,
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
            $supplier = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update(
                [
                    'In_debt' => $supplier->In_debt - (($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity))
                ]
            );
        } else {
            resource_purchases::where('orderId', $request->ordernumber)->update(
                [
                    'recoveredـpieces' => $resource_purchases->recoveredـpieces + $request->return_quentity,
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),


                ]
            );
        }




        $productData = products::find($request->id);

        $unit = units::find($orderDetails->unit);
        $productId = $request->productNo;
        $product = products::find($request->id);
        if ($unit->is_master == 0) {
            $updatedproduct = products::where('id', $request->id)->update(
                [
                    'All_QUENTITY' => ($product->QUENTITY_all_Retails - $request->return_quentity) / $product->retail_uom_quntToParent,
                    'QUENTITY' => (int) (($product->QUENTITY_all_Retails - $request->return_quentity) / $product->retail_uom_quntToParent),
                    'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $request->return_quentity)) % $product->retail_uom_quntToParent),
                    'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $request->return_quentity),
                ]
            );
            products::where('parent_inv_itemcard_id', $request->id)->update(
                [
                    'All_QUENTITY' => ($product->QUENTITY_all_Retails - $request->return_quentity),
                ]
            );
        } else {
            $updatedproduct = products::where('id', $request->id)->update(
                [
                    'All_QUENTITY' => ($product->All_QUENTITY - $request->return_quentity),
                    'QUENTITY' => (int) ($product->All_QUENTITY - $request->return_quentity),
                    'QUENTITY_Retail' => ($product->All_QUENTITY - $request->return_quentity) - ((int) ($product->All_QUENTITY - $request->return_quentity)),
                    'QUENTITY_all_Retails' => ($product->All_QUENTITY - $request->return_quentity) * $product->retail_uom_quntToParent,
                ]
            );
            products::where('parent_inv_itemcard_id', $request->id)->update(
                [
                    'All_QUENTITY' => ($product->All_QUENTITY) - $request->return_quentity * $product->retail_uom_quntToParent,
                ]
            );
        }








        $orderDetails = orderDetails::where('product_id', $request->id)
            ->where('order_owner', $request->ordernumber)->update(
                [
                    'returns_purchase' => $orderDetails->returns_purchase + $request->return_quentity,
                    'numberofpice' => $orderDetails->numberofpice - $request->return_quentity,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
        $i = 1;
        foreach (orderDetails::where('order_owner', $request->ordernumber)->get() as $item) {
            if ($item->numberofpice > 0) {
                $i = 0;
            }
        }
        if ($resource_purchases->Pay_Method_Name == 'Credit' && $i == 1) {
            resource_purchases::where('orderId', $request->ordernumber)->update(
                [
                    //     'In_debt' => $resource_purchases->In_debt 
                ]
            );
            $supplier = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update(
                [
                    'In_debt' => $supplier->In_debt + $resource_purchases->discount

                ]
            );
        } else {
            resource_purchases::where('orderId', $request->ordernumber)->update(
                [
                    //    'In_debt' => $resource_purchases->In_debt - $resource_purchases->discount

                ]
            );
        }
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل  بنجاج' : 'has been modified successfully';

        session()->flash('editpurchase', $message);
        $data = [];

        $orderOwner = orderTosupllier::find($request->ordernumber);
        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->get();

        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;
        $resource_purchases = resource_purchases::where('orderId', $request->ordernumber)->first();

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,
            'product' => $orderdetails
        ];
        //return $data;
        return view('products.purchase_return', compact('data'));
    }

    public function updateproductalldatapurchases(Request $request)
    {

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::find($request->id);

        $resource_purchases = resource_purchases::where('orderId', $orderDetails->order_owner)->first();

        $increasequantity = $request->quantity - $orderDetails->numberofpice;
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice))
                ]
            );
            // $supplier = supllier::find($resource_purchases->suplier_id);
            // supllier::where('id', $resource_purchases->suplier_id)->update(
            //     [
            //         'In_debt' => $supplier->In_debt - (($orderDetails->purchasingـprice * $increasequantity) + ($orderDetails->Added_Value *  $orderDetails->numberofpice))
            //     ]
            // );
        } else {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice))

                ]
            );
        }



        $productData = products::find($orderDetails->product_id);
        // products::where('id', $orderDetails->product_id)->Update([
        //     'numberofpice' => $productData->numberofpice - $orderDetails->numberofpice,
        // ]);
        orderDetails::where('id', $request->id)->update(
            [
                'numberofpice' => $orderDetails->numberofpice - $orderDetails->numberofpice
            ]
        );

        $avtPurcheseRate = Avt::find(2);

        $orderDetails = orderDetails::find($request->id);

        $resource_purchases = resource_purchases::where('orderId', $orderDetails->order_owner)->first();

        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    'In_debt' => $resource_purchases->In_debt + (($request->pricepurchases * $request->quantity) + (($request->pricepurchases * $request->quantity) * $avtPurcheseRate->AVT))
                ]
            );
            // $supplier = supllier::find($resource_purchases->suplier_id);
            // supllier::where('id', $resource_purchases->suplier_id)->update(
            //     [
            //         'In_debt' => $supplier->In_debt + (($request->pricepurchases * $request->quantity) + ($request->pricepurchases * $request->quantity * $avtPurcheseRate->AVT))
            //     ]
            // );
        } else {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    'In_debt' => $resource_purchases->In_debt + (($request->pricepurchases * $request->quantity) + ($request->pricepurchases * $request->quantity * $avtPurcheseRate->AVT))

                ]
            );
        }

        // $productData = products::find($orderDetails->product_id);
        // products::where('id', $orderDetails->product_id)->Update([
        //     'numberofpice' => $productData->numberofpice + $request->quantity,
        // ]);
        orderDetails::where('id', $request->id)->update(
            [
                'numberofpice' => $request->quantity,
                'purchasingـprice' => $request->pricepurchases,
                'Added_Value' => $request->pricepurchases * $avtPurcheseRate->AVT,
            ]
        );


        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل  بنجاج' : 'has been modified successfully';

        session()->flash('editpurchasein', $message);
        $recentsupllier = supllier::find($resource_purchases->suplier_id);

        $orderdetails = orderDetails::where('order_owner', $orderDetails->order_owner)->where('numberofpice', '>', 0)->get();
        // return  $orderdetails;
        $allProdctsD = [];
        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id
            ];
        }
        $data = [
            'message' => $message,
            'pay' => $resource_purchases->Pay_Method_Name,
            'recentsupllier' => $recentsupllier,
            "product" => $allProdctsD,
            "discount" => $resource_purchases->discount
        ];
        return $data;
    }

    public function increasePurchase(Request $request)
    {

        //

        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::find($request->id);
        $resource_purchases = resource_purchases::where('orderId', $orderDetails->order_owner)->first();
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    'In_debt' => $resource_purchases->In_debt + (($orderDetails->purchasingـprice * $request->increasequentity) + ($orderDetails->Added_Value * $request->increasequentity))
                ]
            );
            // $supplier = supllier::find($resource_purchases->suplier_id);
            // supllier::where('id', $resource_purchases->suplier_id)->update(
            //     [
            //         'In_debt' => $supplier->In_debt + (($orderDetails->purchasingـprice * $request->increasequentity) + ($orderDetails->Added_Value * $request->increasequentity))
            //     ]
            // );
        } else {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    'In_debt' => $resource_purchases->In_debt + (($orderDetails->purchasingـprice * $request->increasequentity) + ($orderDetails->Added_Value * $request->increasequentity))

                ]
            );
        }

        // $productData = products::find($orderDetails->product_id);
        // products::where('id', $orderDetails->product_id)->Update([
        //     'numberofpice' => $productData->numberofpice + $request->increasequentity,
        // ]);
        orderDetails::where('id', $request->id)->update(
            [
                'numberofpice' => $orderDetails->numberofpice + $request->increasequentity
            ]
        );
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل  بنجاج' : 'has been modified successfully';

        session()->flash('editpurchasein', $message);
        $recentsupllier = supllier::find($resource_purchases->suplier_id);

        $orderdetails = orderDetails::where('order_owner', $orderDetails->order_owner)->where('numberofpice', '>', 0)->get();
        // return  $orderdetails;
        $allProdctsD = [];
        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id
            ];
        }
        $data = [
            'pay' => $resource_purchases->Pay_Method_Name,
            'recentsupllier' => $recentsupllier,
            'In_debt' => $resource_purchases->In_debt,
            'discount' => $resource_purchases->discount,
            "product" => $allProdctsD
        ];
        return $data;
        return view('products.purchases', compact('data'));
    }



    public function updatePurchaseOrder(Request $request)
    {

        //

        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::where('order_owner', $request->ordernumber)->where('product_id', $request->id)->first();



        orderDetails::where('order_owner', $request->ordernumber)->where('product_id', $request->id)->update(
            [
                'numberofpice' => $orderDetails->numberofpice - $request->return_quentity
            ]
        );
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل  بنجاج' : 'has been modified successfully';

        session()->flash('editpurchasein', $message);

        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->where('numberofpice', '>', 0)->get();
        // return  $orderdetails;
        $listOfProduct = [];
        $count = 0;
        $totalAdded_value = 0;
        $totalPrice = 0;

        foreach ($orderdetails as $orderitem) {
            $totalAdded_value += $orderitem->numberofpice * $orderitem->Added_Value;
            $totalPrice += $orderitem->numberofpice * $orderitem->purchasingـprice;
            $count++;
            $listOfProduct[] = [
                "count" => $count,
                "productCode" => $orderitem->productData->barcode,
                "name" => $orderitem->productData->name,
                "product_id" => $orderitem->product_id,
                "quantity" => $orderitem->numberofpice,
                "purchasingـprice" => $orderitem->purchasingـprice,
                "Added_Value" => $orderitem->Added_Value,
                "total" => ($orderitem->numberofpice * $orderitem->Added_Value) + ($orderitem->numberofpice * $orderitem->purchasingـprice),
                "orderNo" => $orderitem->order_owner,
                "totalAdded_Value" => $totalAdded_value,
                "totalPrice" => $totalPrice
            ];
        }

        return $listOfProduct;
        return view('products.purchases', compact('data'));
    }


    public function updatePurchaseOrderToIncrease(Request $request)
    {

        //

        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::where('order_owner', $request->ordernumber)->where('product_id', $request->id)->first();



        orderDetails::where('order_owner', $request->ordernumber)->where('product_id', $request->id)->update(
            [
                'numberofpice' => $orderDetails->numberofpice + $request->increasequentity
            ]
        );
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل  بنجاج' : 'has been modified successfully';

        session()->flash('editpurchasein', $message);

        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->where('numberofpice', '>', 0)->get();
        // return  $orderdetails;
        $listOfProduct = [];
        $count = 0;
        $totalAdded_value = 0;
        $totalPrice = 0;

        foreach ($orderdetails as $orderitem) {
            $totalAdded_value += $orderitem->numberofpice * $orderitem->Added_Value;
            $totalPrice += $orderitem->numberofpice * $orderitem->purchasingـprice;
            $count++;
            $listOfProduct[] = [
                "count" => $count,
                "productCode" => $orderitem->productData->barcode,
                "product_name" => $orderitem->productData->name,
                "product_id" => $orderitem->product_id,
                "quantity" => $orderitem->numberofpice,
                "purchasingـprice" => $orderitem->purchasingـprice,
                "Added_Value" => $orderitem->Added_Value,
                "total" => ($orderitem->numberofpice * $orderitem->Added_Value) + ($orderitem->numberofpice * $orderitem->purchasingـprice),
                "orderNo" => $orderitem->order_owner,
                "totalAdded_Value" => $totalAdded_value,
                "totalPrice" => $totalPrice
            ];
        }

        return $listOfProduct;
    }


    public function updatePurchase(Request $request)
    {

        //

        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::find($request->id);
        $resource_purchases = resource_purchases::where('orderId', $orderDetails->order_owner)->first();
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    //  'recoveredـpieces' => $resource_purchases->recoveredـpieces + $request->return_quentity,
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity))
                ]
            );
            // $supplier = supllier::find($resource_purchases->suplier_id);
            // supllier::where('id', $resource_purchases->suplier_id)->update(
            //     [
            //         'In_debt' => $supplier->In_debt - (($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity))
            //     ]
            // );
        } else {
            resource_purchases::where('orderId', $orderDetails->order_owner)->update(
                [
                    //     'recoveredـpieces' => $resource_purchases->recoveredـpieces + $request->return_quentity,
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity))

                ]
            );
        }

        $productData = products::find($orderDetails->product_id);
        // products::where('id', $orderDetails->product_id)->Update([
        //     'numberofpice' => $productData->numberofpice - $request->return_quentity,
        // ]);
        orderDetails::where('id', $request->id)->update(
            [
                //'returns_purchase' => $orderDetails->returns_purchase + $request->return_quentity,
                'numberofpice' => $orderDetails->numberofpice - $request->return_quentity
            ]
        );
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل  بنجاج' : 'has been modified successfully';

        session()->flash('editpurchasein', $message);
        $recentsupllier = supllier::find($resource_purchases->suplier_id);

        $orderdetails = orderDetails::where('order_owner', $orderDetails->order_owner)->where('numberofpice', '>', 0)->get();
        // return  $orderdetails;
        $allProdctsD = [];
        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id
            ];
        }
        $data = [
            'pay' => $resource_purchases->Pay_Method_Name,
            'recentsupllier' => $recentsupllier,
            "product" => $allProdctsD,
            'In_debt' => $resource_purchases->In_debt,
            'discount' => $resource_purchases->discount,
        ];
        return $data;
        return view('products.purchases', compact('data'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        //====

        //return $request;
        $orderDetails = orderDetails::where('product_id', $request->id)
            ->where('order_owner', $request->ordernumber)->first();
        //return $orderDetails;
        $resource_purchases = resource_purchases::where('orderId', $request->ordernumber)->first();
        //return $resource_purchases;
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $request->ordernumber)->update(
                [
                    'recoveredـpieces' => $resource_purchases->recoveredـpieces + $orderDetails->numberofpice,
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
            $supplier = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update(
                [
                    'In_debt' => $supplier->In_debt - (($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice))
                ]
            );
        } else {
            resource_purchases::where('orderId', $request->ordernumber)->update(
                [
                    'recoveredـpieces' => $resource_purchases->recoveredـpieces + $orderDetails->numberofpice,
                    'In_debt' => $resource_purchases->In_debt - (($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),


                ]
            );
        }

        $productData = products::find($request->id);
        products::where('id', $request->id)->Update([
            'numberofpice' => $productData->numberofpice - $orderDetails->numberofpice,
        ]);
        orderDetails::where('product_id', $request->id)
            ->where('order_owner', $request->ordernumber)->update(
                [
                    'returns_purchase' => $orderDetails->returns_purchase + $orderDetails->numberofpice,
                    'numberofpice' => $orderDetails->numberofpice - $orderDetails->numberofpice,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );

        //===
        $i = 1;
        foreach (orderDetails::where('order_owner', $request->ordernumber)->get() as $item) {
            if ($item->numberofpice > 0) {
                $i = 0;
            }
        }
        if ($resource_purchases->Pay_Method_Name == 'Credit' && $i == 1) {
            //  resource_purchases::where('orderId', $request->ordernumber)->update(
            //     [
            //         'In_debt' => $resource_purchases->In_debt - $resource_purchases->discount
            //     ]
            // );
            $supplier = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update(
                [
                    'In_debt' => $supplier->In_debt + $resource_purchases->discount
                ]
            );
        } else {
            // resource_purchases::where('orderId', $request->ordernumber)->update(
            //     [
            //         'In_debt' => $resource_purchases->In_debt - $resource_purchases->discount

            //     ]
            // );
        }

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم حذف  بنجاح' : 'has been deleted successfully';

        session()->flash('delete', $message);
        $orderOwner = orderTosupllier::find($request->ordernumber);
        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->get();

        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;
        $resource_purchases = resource_purchases::where('orderId', $request->ordernumber)->first();

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,

            'product' => $orderdetails
        ];
        //return $data;
        return view('products.purchase_return', compact('data'));
    }


    public function getProductdJsonDecode($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $product = products::find($id);

        return json_encode($product);
    }
}
