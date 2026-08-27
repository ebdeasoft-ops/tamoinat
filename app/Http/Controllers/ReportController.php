<?php

namespace App\Http\Controllers;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use App\Models\Avt;
use App\Models\convertcashboxToBank;
use App\Models\branchs;
use App\Models\Transfer_cash_to_the_next_day;
use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\orderDetails;
use App\Models\credittransactions;
use App\Models\customers;
use App\Models\transferMoney_to_mainbranch;
use App\Models\order_price_from_supplier;
use App\Models\product_movement_another_branch;
use App\Models\product_movement_another_branch_items;
use App\Models\resource_purchases;
use App\Models\sales;
use App\Models\supllier;
use App\Models\expenses;
use App\Models\orderTosupllier;
use App\Models\return_sales;
use App\Models\offer_price_to_customer;
use  App\Models\transactiontosuplliers;
use App\Models\products;
use App\Models\cash_from__bank;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Exportproducts;
use PDF;
use App\Models\User;
use App\Models\inv_itemcard_categorie;
use App\Models\units;
use App\Http\Requests\ItemcardRequest;
use App\Http\Requests\ItemcardRequestUpdate;
use App\Models\inv_itemcard_categories;



class ReportController extends Controller
{
    //


    public function Bank_Transfer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('reports.Bank_Transfer', compact('data'));
    }

    public function Expired_Products()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    
    $com_code = auth()->user()->branchs_id;
    $data =products::where( 'branchs_id' , $com_code)->where( 'item_type' , 2)->whereDate('expaire_date', '<=', \Carbon\Carbon::now()->addHours(3)->format("Y-m-d"))->orderby('id','DESC')->paginate(PAGINATION_COUNT);
    //  $this->get_cols_where_p(new products(), array("*"), array('branchs_id' => $com_code), 'id', 'DESC', PAGINATION_COUNT);
    if (!empty($data)) {
    foreach ($data as $info) {
    $info->added_by_admin = $this->get_field_value(new User(), 'name', array('id' => $info->added_by));
    $info->inv_itemcard_categories_name =$this->get_field_value(new inv_itemcard_categories(), 'name', array('id' => $info->inv_itemcard_categories_id));
    $info->Uom_name = $this->get_field_value(new units(), 'name', array('id' => $info->uom_id));
    $info->retail_uom_name = $this->get_field_value(new units(), 'name', array('id' => $info->retail_uom_id));
    if ($info->updated_by > 0 and $info->updated_by != null) {
    $info->updated_by_admin = $this->get_field_value(new User(), 'name', array('id' => $info->updated_by));
    }
    }
    }
    $inv_itemcard_categories =$this->get_cols_where(new inv_itemcard_categories(), array('id', 'name'), array('branchs_id' => $com_code, 'active' => 1), 'id', 'DESC');
    return view('reports.expaireproducts', ['data' => $data, 'inv_itemcard_categories' => $inv_itemcard_categories]);
    }


    public function ConvertBoxtobankReport()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('reports.convert_cash_to_bank', compact('data'));
    }
    public function Bank_Statement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('reports.bankDecument', compact('data'));
    }
    public function Customer_account_statement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Customer_account_statement');
    }


 
    
    public function searchbankDecument(Request $request)
    {
       //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->branch == '-' ) {
            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay','!=','Cash')->where('Pay','!=','Credit')->where('save',1)->get();
            $credittransactions = credittransactions::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('pay_method','!=','Cash')->where('pay_method','!=','Credit')->get();
            $transactiontosuplliers = transactiontosuplliers::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay_Method_Name','!=','Cash')->where('Pay_Method_Name','!=','Credit')->get();
            $expenses = expenses::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay_Method_Name','!=','Cash')->get();
            $resource_purchases = resource_purchases::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay_Method_Name','!=','Cash')->where('Pay_Method_Name','!=','Credit')->where('save',1)->get();
            $cash_from__bank = cash_from__bank::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('payment_method','!=','Cash')->get();
            $convertcashboxToBank = convertcashboxToBank::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
           // return $Invoices;


        }else{


            $Invoices = invoices::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay','!=','Cash')->where('Pay','!=','Credit')->where('save',1)->get();
            $credittransactions = credittransactions::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('pay_method','!=','Cash')->where('pay_method','!=','Credit')->get();
            $transactiontosuplliers = transactiontosuplliers::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay_Method_Name','!=','Cash')->where('Pay_Method_Name','!=','Credit')->get();
            $expenses = expenses::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay_Method_Name','!=','Cash')->get();
            $resource_purchases = resource_purchases::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay_Method_Name','!=','Cash')->where('Pay_Method_Name','!=','Credit')->where('save',1)->get();
            $cash_from__bank = cash_from__bank::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('payment_method','!=','Cash')->get();
            //return $Invoices;
            $convertcashboxToBank = convertcashboxToBank::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();

        }

$data=[];
$avt=Avt::find(1);
$saleavt = $avt->AVT;
foreach(  $Invoices  as   $Invoice){
    $pays = '';
    if ($Invoice->Pay == 'Shabka') {
        $pays = __('report.shabka');
    }elseif($Invoice->Pay == "Bank_transfer") {
        $pays = __('home.Bank_transfer');
    } else {
        $pays = __('home.Partition of the amount');
    }
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.sales'),
            'payment'=>$pays,
            'in'=>1,
            'user'=>  $Invoice->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->Bank_transfer +$Invoice->bankamount )
        ];
    }
  



foreach(  $resource_purchases  as   $Invoice){
    $pays = '';
    if ($Invoice->Pay_Method_Name == 'Shabka') {
        $pays = __('report.shabka');
    }else{
        $pays = __('home.Bank_transfer');
    } 
    $orderTosupllier=orderTosupllier::find($Invoice->orderId);
   // return $orderTosupllier;
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.purchases'),
            'payment'=>$pays,
            'in'=>0,

            'user'=>  $orderTosupllier->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->In_debt ) -(($Invoice->discount ) )
        ];
}

foreach(   $convertcashboxToBank   as   $Invoice){
    $pays = '';
  
    $orderTosupllier=orderTosupllier::find($Invoice->orderId);
   // return $orderTosupllier;
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.convertboxtobank'),
            'payment'=>'-',
            'in'=>1,
            'user'=>  $Invoice->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->amount )
        ];
}


foreach(  $credittransactions  as   $Invoice){
    $pays = '';
    if ($Invoice->pay_method == 'Shabka') {
        $pays = __('report.shabka');
    }else{
        $pays = __('home.Bank_transfer');
    } 
    $orderTosupllier=orderTosupllier::find($Invoice->orderId);
   // return $orderTosupllier;
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.voucher'),
            'payment'=>$pays,
            'in'=>1,
            'user'=>  $Invoice->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->recive_amount )
        ];
}
foreach(  $transactiontosuplliers  as   $Invoice){
    $pays = '';
    if ($Invoice->Pay_Method_Name == 'Shabka') {
        $pays = __('report.shabka');
    }else{
        $pays = __('home.Bank_transfer');
    } 
    $orderTosupllier=orderTosupllier::find($Invoice->orderId);
   // return $orderTosupllier;
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.Receipt document'),
            'payment'=>$pays,
            'in'=>0,
            'user'=>  $Invoice->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->paidـamount )
        ];
}


foreach(  $expenses  as   $Invoice){
    $pays = '';
    if ($Invoice->Pay_Method_Name == 'Shabka') {
        $pays = __('report.shabka');
    }else{
        $pays = __('home.Bank_transfer');
    } 
    $orderTosupllier=orderTosupllier::find($Invoice->orderId);
   // return $orderTosupllier;
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.other_expenses'),
            'payment'=>$pays,
            'in'=>0,
            'user'=>  $Invoice->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->Theـamountـpaid )
        ];
}

foreach(  $cash_from__bank  as   $Invoice){
    $pays = '';
    if ($Invoice->Pay_Method_Name == 'Shabka') {
        $pays = __('report.shabka');
    }else{
        $pays = __('home.Bank_transfer');
    } 
    $orderTosupllier=orderTosupllier::find($Invoice->orderId);
   // return $orderTosupllier;
        $data[]=[
            'date'=>  $Invoice->created_at,
            'type'=>__('home.shabka_bank'),
            'payment'=>$pays,
            'in'=>1,
            'user'=>  $Invoice->user->name,
            'branch'=>  $Invoice->branch->name,
            'amount'=>($Invoice->the_amount )
        ];
}
usort($data, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
  });
        return view('reports.printBankStatment', compact('data'))->with('start_at',$request->start_at)->with('end_at',$request->end_at)->with('branch',$request->branch);

    }

    public function search_Customer_account_statement(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::where('customer_id', $request->UserId)->where('Pay', 'Credit')->where('Price', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        $credittransactions = credittransactions::where('customer_id', $request->UserId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        // return $Invoices;
        $customer = customers::find($request->UserId);
        $data = [$Invoices, $credittransactions];
       // return $data;
        return view('reports.print_Customer_account_statement', compact('data'))->with('start_at', $request->start_at)->with('end_at', $request->end_at)->with('customerId', $request->UserId)->with('customerName', $customer->name);
    }
    public function searchConvertBoxtobankReport(Request $request)
    {
        $data = convertcashboxToBank::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();

        return view('reports.convert_cash_to_bank', compact('data'));
    }


    public function transactionsToMasterBranch()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('reports.transactionsToMasterBranch', compact('data'));
    }

    public function products_Transfer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('reports.product_Transfer', compact('data'));
    }
    public function Delivery_notes()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Delivery_notes');
    }


    public function Customersـexceededـgraceـperiod()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $customers = customers::where('Balance', '!=', 0)->get();
        $Customersـexceededـgraceـperiod = [];
        foreach ($customers as $customer) {
            $ffdate = $customer->updated_at;
            $tdate = \Carbon\Carbon::now()->addHours(3)::now();
            $start = \Carbon\Carbon::parse($ffdate);
            $end =  \Carbon\Carbon::parse($tdate);
            $diff_in_days = $end->diffInDays($start);
            if ($diff_in_days > $customer->grace_period_in_days) {
                $Customersـexceededـgraceـperiod[] = $customer;
            }
            // $Customersـexceededـgraceـperiod
        }
        return view('reports.Customersexceededgraceperiod', compact('Customersـexceededـgraceـperiod'));
    }
    public function VAT()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.VAT');
    }




    public function Best_selling_products()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Best_selling_products');
    }



    public function budgetsheet()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        return view('reports.budget sheet');
    }


    public function stockquantity()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [
            'display' => 1
        ];
        return view('reports.stockquantity', compact('data'))->with('branchdata', '-' . "/" . '==' . "/" . '1');
    }





    public function shift_detailes()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.shift_detailes');
    }




    public function Supplier_credit_payment()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Supplier_credit_payment');
    }

    public function print_supplierList()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.print_supplierList');
    }
    public function print_customeList()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.print_customeList');
    }
    public function Customerlist()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.customerList');
    }

    public function customerـpurchases()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.customerpurchases');
    }

    public function credit_collection()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.credit_collection');
    }


    public function purchasereports()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.purchasereports');
    }

    public function Purchasesـfromـsuppliers()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Purchases_from_suppliers');
    }

    public function PurchasesـfromـsuppliersNew()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.purchaseFromSupplierreports');
    }


    public function Refundـofـresourceـpurchases()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Refund_of_resource_purchases');
    }




    public function Requestـaـquoteـfromـtheـsupplier()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Request_A_quote_from_supplier');
    }


    public function product_sales()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.product_sales');
    }

    public function report_returns_sale()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.report_returns_sale');
    }


    public function salesـprofits()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.sales_profits');
    }
    public function supplierList()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.supplierList');
    }
    public function showallBranchs()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('users.show_branchs');
    }

    public function Expenses()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Expenses');
    }



    public function Creditsales()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Creditsales');
    }

    public function employeeـsales()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.employeesales');
    }

    public function Requestـoffersـfromـsuppliers()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('reports.Request_offers_from_suppliers');
    }


    public function report_offer_price_customer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $Invoices = null;
        return view('reports.report_offer_price_customer', compact('Invoices'));
    }




    public function show_offer_price_customer(Request $request)
    {
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $userId = $request->UserId;
        if ($userId == '-') {
            $Invoices = offer_price_to_customer::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();

            return view('reports.report_offer_price_customer', compact('Invoices'))->with('userId', $userId);
        }
        $Invoices = offer_price_to_customer::where('customer_id', $userId)->get();
        return view('reports.report_offer_price_customer', compact('Invoices'))->with('userId', $userId);
    }


    public function search_Delivery_notes(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->UserId;
        if ($supplierId == '-') {
            $Invoices = resource_purchases::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;

            return view('reports.Delivery_notes', compact('Invoices'))->with('supplierId', $supplierId);
        }
        $Invoices = resource_purchases::where('orderId', $request->UserId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        //return $Invoices;
        return view('reports.Delivery_notes', compact('Invoices'))->with('supplierId', $supplierId);
    }
    public function searchtransactionsToMasterBranch(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->UserId;
        if ($request->branch == '-') {
            $tansactions = transferMoney_to_mainbranch::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;
            $data = [
                'start_at' => $request->start_at,
                "end_at" => $request->end_at,
                "transactions" => $tansactions
            ];
            return view('reports.print_transactionsToMasterBranch', compact('data'));
        }
        $tansactions = transferMoney_to_mainbranch::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        //return $Invoices;
        $data = [
            'start_at' => $request->start_at,
            "end_at" => $request->end_at,
            "transactions" => $tansactions
        ];
        return view('reports.print_transactionsToMasterBranch', compact('data'));
    }

    public function search_Bank_Transfer(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];

        $data = cash_from__bank::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        //return $Invoices;

        return view('reports.Bank_Transfer', compact('data'));
    }


    public function search_products_Transfer(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $transactions = [];

        $transctions = product_movement_another_branch::where('branch_from', $request->branch_from)->where('branch_to', $request->branch_to)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('reciveInvoiceNumber', '!=', 0)->get();
        $data = [
            "start_at" => $request->start_at,
            "end_at" => $request->end_at,
            "branch_to" =>  $request->branch_to,
            "branch_from" => $request->branch_from,
            "transctions" => $transctions
        ];
        //  return $data;
        return view('reports.product_Transfer', compact('data'));
    }






    public function search_shift_detailes(Request $request)
    {
        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->UserId;
        if ($request->branch == '-' && $request->pay == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;

            return view('reports.shift_detailes', compact('Invoices'))->with('pay', [$request->pay, $request->branch]);
        }
        if ($request->branch == '-' && $request->pay == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;       
            return view('reports.shift_detailes', compact('Invoices'))->with('supplierId', $supplierId);
        } elseif ($request->branch != '-' && $request->pay == '-') {
            $Invoices = invoices::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;

            return view('reports.shift_detailes', compact('Invoices'))->with('pay', [$request->pay, $request->branch]);
        } elseif ($request->branch == '-' && $request->pay != '-') {
            $Invoices = invoices::where('Pay', $request->pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;

            return view('reports.shift_detailes', compact('Invoices'))->with('pay', [$request->pay, $request->branch]);
        } else {
            $Invoices = invoices::where('branchs_id', $request->branch)->where('Pay', $request->pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;
            return view('reports.shift_detailes', compact('Invoices'))->with('pay', [$request->pay, $request->branch]);
        }
    }






    public function search_Supplier_credit_payment(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->supplierId == '-') {
            $Invoices = transactiontosuplliers::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;
            return view('reports.Supplier_credit_payment', compact('Invoices'))->with('supplierId', $request->supplierId);
        }
        $Invoices = transactiontosuplliers::where('suplier_id', $request->supplierId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        //return $Invoices;
        return view('reports.Supplier_credit_payment', compact('Invoices'))->with('supplierId', $request->supplierId);
    }

    public function printExpensesReportlast($branch, $reason, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $start_at = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        //return $request;
        if ($branch == '-') {
            if ($reason == '-') {
                $Invoices = expenses::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            } else {
                $Invoices = expenses::where('reasonId_id', $reason)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            }
        } else {
            if ($reason == '-') {
                $Invoices = expenses::where('branchs_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            } else {
                $Invoices = expenses::where('branchs_id', $branch)->where('reasonId_id', $reason)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            }
        }
        return view('reports.printExpensesReport', compact('Invoices'))->with('branch', $branch);
    }
    public function print_Bank_Transfer($branch, $startat, $end_at)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $branchname = branchs::find($branch);
        $branchname = $branchname->name;
        $data = [];

        $transactions = cash_from__bank::where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $data = [
            'start_at' => $startat[0],
            "end_at" => $end_at[0],
            "branch" => $branchname,
            "transactions" => $transactions
        ];
        return view('reports.print_bank_transfer', compact('data'));
    }

    public function print_products_Transfer($branch_from, $branch_to, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $transactions = [];

        $transctions = product_movement_another_branch::where('branch_from', $branch_from)->where('branch_to', $branch_to)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('reciveInvoiceNumber', '!=', 0)->get();
        $data = [
            "start_at" => $startat,
            "end_at" => $end_at,
            "branch_to" =>  $branch_to,
            "branch_from" => $branch_from,
            "transctions" => $transctions
        ];
        return view('reports.print_transction_product', compact('data'));
    }

    public function printExpensesReport(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        //return $request;
        if ($request->branch == '-') {
            $Invoices = expenses::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        } else {
            $Invoices = expenses::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        }
        return view('reports.Expenses', compact('Invoices'))->with('branch', $request->branch);
    }

    public function search_stockquantity(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        //  return  $request;
        $display = $request->choosequantitytodisplay;

        if ($request->branch == '-') {
            if ($display == '==') {
                $products = products::where('All_QUENTITY', $request->quantity)->where('parent_inv_itemcard_id',0)->paginate(50);
            } else {
                $products = products::where('All_QUENTITY', $display, $request->quantity)->where('parent_inv_itemcard_id',0)->paginate(50);
            }



            return view('reports.stockquantity', compact('products'))->with('branchdata', '-' . "/" . $display . "/" . $request->quantity);
        } else {
            if ($display == '==') {
                $products = products::where('All_QUENTITY', $request->quantity)->where('branchs_id', $request->branch)->where('parent_inv_itemcard_id',0)->paginate(50);
            } else {
                $products = products::where('All_QUENTITY', $display, $request->quantity)->where('branchs_id', $request->branch)->where('parent_inv_itemcard_id',0)->paginate(50);
            }
            //   return $products;

            return view('reports.stockquantity', compact('products'))->with('branchdata',  $request->branch . "/" . $display . "/" . $request->quantity);
        }
    }

    public function search_stockquantityPagination($searchtext, $branchId)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        if ($branchId == '-') {
            $products = products::where('product_name', 'LIKE', '%' . $searchtext . '%')->orwhere('Product_Code', 'LIKE', '%' . $searchtext . '%')->where('parent_inv_itemcard_id',0)->paginate(50);
            return $products;
        }
        $products = products::where('branchs_id', $branchId)->where('product_name', 'LIKE', '%' . $searchtext . '%')->orwhere('Product_Code', 'LIKE', '%' . $searchtext . '%')->where('parent_inv_itemcard_id',0)->paginate(50);
        return $products;
    }

    public function stockquantityPagination($request, $operation, $quantity)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request == '-') {
            $products = products::where('All_QUENTITY', $operation, $quantity)->paginate(50);

            return $products;
        } else {
            $products = products::where('branchs_id', $request)->where('All_QUENTITY', $operation, $quantity)->paginate(50);
        }
        return $products;
    }

    public function search_Expenses(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        //return $request;
        if ($request->branch == '-') {
            if ($request->enpenses_reason == '-') {
                $Invoices = expenses::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            } else {
                $Invoices = expenses::where('reasonId_id', $request->enpenses_reason)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            }
        } else {
            if ($request->enpenses_reason == '-') {
                $Invoices = expenses::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            } else {
                $Invoices = expenses::where('reasonId_id', $request->enpenses_reason)->where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            }
        }
        return view('reports.Expenses', compact('Invoices'))->with('branch_id', [$request->branch, $request->enpenses_reason]);
    }

    public function search_Best_selling_products(Request $request)
    {
        //return  $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->branch == '-') {
            $bestSaleing = sales::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('quantity', ">", 0)->where('save', 1)->get();
        } else {
            $bestSaleing = sales::where('branch_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('quantity', ">", 0)->where('save', 1)->get();
        }
        //return $bestSaleing;

        $bestselling = [];
        $listofId = [];
        $i = 0;
        foreach ($bestSaleing as $product) {
            if (!in_array($product->product_id, $listofId)) {
                if ($request->branch == '-') {
                    $bestSaleingofproduct = sales::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('product_id', $product->product_id)->where('quantity', ">", 0)->where('save', 1)->get();
                } else {
                    $bestSaleingofproduct = sales::where('branch_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('product_id', $product->product_id)->where('quantity', ">", 0)->where('save', 1)->get();
                }
                $numberOfSall = 0;
                foreach ($bestSaleingofproduct as $saleproduct) {

                    $numberOfSall += $saleproduct->quantity;
                    $bestselling[$i] = [
                        'productcode' => $saleproduct->productData->barcode,
                        'productname' => $saleproduct->productData->name,
                        'numberofsall' => $numberOfSall,
                        'branch' => $saleproduct->branch->name,
                        'end_at' => $request->end_at,
                        'start_at' => $request->start_at
                    ];
                }
                $i++;
            }
            $listofId[] = $product->product_id;
        }
        //return gettype($data);
        return view('reports.Best_selling_products', compact('bestselling'))->with('branch_id', $request->branch);
    }









    public function search_credit_collection(Request $request)
    {
        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $UserId = $request->UserId;
        if ($UserId == '-') {
            $Invoices = credittransactions::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();

            return view('reports.credit_collection', compact('Invoices'))->with('customer_id', $UserId);
        }
        $Invoices = credittransactions::where('customer_id', $UserId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        //return $Invoices;
        return view('reports.credit_collection', compact('Invoices'))->with('customer_id', $UserId);
    }






    public function search_VAT(Request $request)
    {
       app()->setLocale(LaravelLocalization::getCurrentLocale());
        //return $request;
        $totalVatSales = 0;
        $totalVatPrachese = 0;
        $totalvarExpenses = 0;

        $avt = Avt::find(1);
        $avtpurchases = Avt::find(2);
        $saleavt = $avt->AVT;
        $purchasesavt = $avtpurchases->AVT;
        $countsales = 0;
        $countpurchase = 0;
        $countexpanses = 0;
        $purachasereturntax = 0;
        $salesreturntax = 0;
        $totalpurchase = 0;
        $totalreturnpurchase= 0;

        $countofreturnsaleslist = [];
        $countofreturnpurshaseslist = [];
        if ($request->branch == '-') {
            // $invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            $expenses = expenses::where('expensesAvt', 1)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            // foreach ($invoices as $invoice) {
            //     $totalVatSales += $invoice->Bank_transfer+$invoice->creaditamount+$invoice->bankamount+$invoice->cashamount ;
            // }
                        $totalVatSales = invoices::where('save', 1)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)
    ->selectRaw('
        SUM(Bank_transfer + creaditamount + bankamount + cashamount) as total_vat_sales
    ')
    ->value('total_vat_sales');
            foreach ($expenses as $expense) {
                $totalvarExpenses +=  $expense->Theـamountـpaid;
            }
    
            foreach ($expenses as $expense) {
                $totalvarExpenses +=  $expense->Theـamountـpaid;
            }
            
    $resource_purchases = resource_purchases::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            foreach ($resource_purchases as $item) {
            foreach (orderDetails::where('order_owner', $item->orderId)->get()  as $orderDetailes) {
                $totalVatPrachese += $orderDetailes->Added_Value * $orderDetailes->numberofpice;
                $purachasereturntax += $orderDetailes->Added_Value * $orderDetailes->returns_purchase;
                $totalreturnpurchase+=$orderDetailes->purchasingـprice * $orderDetailes->returns_purchase  ;
                $totalpurchase+=   $orderDetailes->purchasingـprice * $orderDetailes->numberofpice  ;
                
                if ($orderDetailes->returns_purchase > 0 && !in_array($orderDetailes->order_owner, $countofreturnpurshaseslist)) {
                    $countofreturnpurshaseslist[] = $orderDetailes->order_owner;
                }
            }
                
            }

            $returnsales = return_sales::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            foreach ($returnsales as $invoice) {

                $salesreturntax += (($invoice->return_Unit_Price * $invoice->return_quantity) - $invoice->discountvalue - $invoice->discountoninvoice) ;
                if (!in_array($invoice->invoice_id, $countofreturnsaleslist)) {
                    $countofreturnsaleslist[] = $invoice->invoice_id;
                }
            }
$countsales = invoices::where('branchs_id', $request->branch)
    ->where('save', 1)
    ->whereBetween('created_at', [$request->start_at, $request->end_at])
    ->count();            $countpurchase = count($resource_purchases);
            $countexpanses = count($expenses);
        } else {
            // $invoices = invoices::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            $expenses = expenses::where('expensesAvt', 1)->where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();

            // foreach ($invoices as $invoice) {
            //     $totalVatSales += $invoice->Bank_transfer+$invoice->creaditamount+$invoice->bankamount+$invoice->cashamount ;
            // }
            
            $totalVatSales = invoices::where('branchs_id', $request->branch)
    ->where('save', 1)->whereDate('created_at', '>=',  $request->start_at)->whereDate('created_at', '<=', $request->end_at)
    ->selectRaw('
        SUM(Bank_transfer + creaditamount + bankamount + cashamount) as total_vat_sales
    ')
    ->value('total_vat_sales');
            foreach ($expenses as $expense) {
                $totalvarExpenses +=  $expense->Theـamountـpaid;
            }
    
    $resource_purchases = resource_purchases::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            foreach ($resource_purchases as $item) {
            foreach (orderDetails::where('order_owner', $item->orderId)->get()  as $orderDetailes) {
                $totalVatPrachese += $orderDetailes->Added_Value * $orderDetailes->numberofpice;
                $purachasereturntax += $orderDetailes->Added_Value * $orderDetailes->returns_purchase;
                $totalreturnpurchase+=$orderDetailes->purchasingـprice * $orderDetailes->returns_purchase  ;
                $totalpurchase+=   $orderDetailes->purchasingـprice * $orderDetailes->numberofpice  ;
                
                if ($orderDetailes->returns_purchase > 0 && !in_array($orderDetailes->order_owner, $countofreturnpurshaseslist)) {
                    $countofreturnpurshaseslist[] = $orderDetailes->order_owner;
                }
            }
                $totalpurchase-=round(($item->discount),2);
            }

              

              
              
            $returnsales = return_sales::where('branch_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            foreach ($returnsales as $invoice) {
                $salesreturntax += (($invoice->return_Unit_Price * $invoice->return_quantity) - $invoice->discountvalue - $invoice->discountoninvoice) ;
                if (!in_array($invoice->invoice_id, $countofreturnsaleslist)) {
                    $countofreturnsaleslist[] = $invoice->invoice_id;
                }
            }
$countsales = invoices::where('branchs_id', $request->branch)->
    where('save', 1)
    ->whereBetween('created_at', [$request->start_at, $request->end_at])
    ->count();       
    $countpurchase = count($resource_purchases);
            $countexpanses = count($expenses);
        }
        $data = [
            'returncountsales' => count($countofreturnsaleslist),
            'returncountpurchases' =>  count($countofreturnpurshaseslist),
            'salesreturntax' => $salesreturntax*$saleavt,
            'salesreturn_withodtaxtax' => $salesreturntax,
            'purachasereturntax' => $purachasereturntax,
            'totalpurchase' => $totalpurchase,
            'totalreturnpurchase' => $totalreturnpurchase,
            'purachasereturntax' => $purachasereturntax,
            'totalVatPrachese_tax' => $totalVatPrachese,
            'countsales' => $countsales,
            'countpurchase' => $countpurchase,
            'countexpanses' => $countexpanses,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'totalVatSales' =>  (($totalVatSales*100/115)*$saleavt),
            'totalVatPrachese' => $totalVatPrachese,
            'total_sale'=>($totalVatSales*100/115),
            'totalvarExpenses' => round($totalvarExpenses * 100 / 115),
            
        ];
                // return view('reports.print_VAT', compact('data'));

        return view('reports.print_VAT', compact('data'))->with('branch', $request->branch);
    }












    public function search_purchasereports(Request $request)
    {
        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->productname;
        if ($supplierId == '-') {
            $products = orderDetails::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            //return $Invoices;

            return view('reports.purchasereports', compact('products'))->with('supplierId', $supplierId);
        }
        $products = orderDetails::where('product_id', $supplierId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        //return $Invoices;
        return view('reports.purchasereports', compact('products'))->with('supplierId', $supplierId);
    }




    public function search_customerـpurchases(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->branch == '-' && $request->UserId == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } elseif ($request->branch == '-' && $request->UserId != '-') {
            $Invoices = invoices::where('customer_id', $request->UserId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } elseif ($request->branch != '-' && $request->UserId == '-') {
            $Invoices = invoices::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } else {
            $Invoices = invoices::where('customer_id', $request->UserId)->where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        }
        //return $products;
        return view('reports.customerpurchases', compact('Invoices'))->with('branch', [$request->UserId, $request->branch])->with('userid', [$request->UserId, $request->branch]);
    }




    public function search_Refundـofـresourceـpurchases(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->branch != '-') {
            $Invoices = resource_purchases::where('branchs_id', $request->branch)->where('recoveredـpieces', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            return view('reports.Refund_of_resource_purchases', compact('Invoices'))->with('branch_id', $request->branch);
        } else {
            $Invoices = resource_purchases::where('recoveredـpieces', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            return view('reports.Refund_of_resource_purchases', compact('Invoices'))->with('branch_id', $request->branch);
        }
        //return $Invoices;



    }








    public function search_budgetsheet(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $salesdebit = 0;
        $creadit = 0;
        $cash = 0;
        $shabka = 0;
     


        //return $month;
        $start_at = $request->start_at;
        $end_at = $request->end_at;

        $returnsalescash = 0;
        $returnsalescredit = 0;
        $returnsalesshabka = 0;
        $returnSalespartial = 0;
        $returnSalesBankTransfer = 0;
        $returnsalespartialshabka = 0;


        $returnpurchasecash = 0;
        $returnpurchasecredit = 0;
        $returnpurchaseshabka = 0;
        $returnpurchasebanktransfer = 0;


        $salescash = 0;
        $salescredit = 0;
        $salesshabka = 0;
        $salesBankTransfer = 0;


        $purchesecash = 0;
        $purchesecredit = 0;
        $purcheseshabka = 0;
        $purchasebankTransfer = 0;

   
        $expenses_cash = 0;
        $expenses_shabka = 0;
        $expenses_banktransfer = 0;




        $benfitcradit = 0;
        $benfitshabka = 0;
        $benfitcash = 0;
        $benfitBank_transfer = 0;

  
        $bank_cash = 0;
        $bank_shabka = 0;
        $Invoices = [];
        $pirchese = [];
        $credittransactions = [];
        $transactiontosuplliers = [];
        $expenses = [];
        $returnsales = [];
        $returnpurchases = [];

        

        $branchname = __('users.allbranchs');
        if ($request->branch == '-') {
            $convertcashboxToBank = convertcashboxToBank::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $returnsales = return_sales::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $returnpurchases = resource_purchases::where('recoveredـpieces', '!=', 0)->whereDate('updated_at', '>=', $start_at)->whereDate('updated_at', '<=', $end_at)->get();
           $totals = invoices::where('save', 1)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)
    ->selectRaw('
        SUM(cashamount) AS total_cash,
        SUM(bankamount) AS total_bank,
        SUM(creaditamount) AS total_credit,
        SUM(Bank_transfer) AS total_transfer
    ')->first();            $pirchese = resource_purchases::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
         
            $Transfer_cash_to_the_next_dayList = Transfer_cash_to_the_next_day::whereDate('created_at', $end_at)->get();
            $date = date("Y-m-d", strtotime('-24 hours', strtotime($end_at)));

            $Transfer_cash_from_the_last_dayList = Transfer_cash_to_the_next_day::whereDate('created_at', $date)->get();
            $transferMoney_to_mainbranch = [];
        } else {
            $branchname = branchs::find($request->branch);
            $branchname = $branchname->name;
            $convertcashboxToBank = convertcashboxToBank::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $returnsales = return_sales::where('branch_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $returnpurchases = resource_purchases::where('branchs_id', $request->branch)->where('recoveredـpieces', '!=', 0)->whereDate('updated_at', '>=', $start_at)->whereDate('updated_at', '<=', $end_at)->get();
           //$Invoices = invoices::where('save', 1)->where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
           $totals = invoices::where('save', 1)->where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)
    ->selectRaw('
        SUM(cashamount) AS total_cash,
        SUM(bankamount) AS total_bank,
        SUM(creaditamount) AS total_credit,
        SUM(Bank_transfer) AS total_transfer
    ')->first();

            $pirchese = resource_purchases::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
           
            $credittransactions = credittransactions::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $transactiontosuplliers = transactiontosuplliers::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
        
         
        }
 
        $avt = Avt::find(1);
        $saleavt = $avt->AVT;
        $invoiceId = 0;
        if ($returnsales != []) {

            $valuewithoudtax = 0;
            $discountoninvoice = 0;
            $invoicesId = [];
            $i = 0;
            foreach ($returnsales as $returnsale) {
                if (!in_array($returnsale->invoice_id, $invoicesId)) {
                    $invoicesId[] = $returnsale->invoice_id;
                }
            }

            foreach ($invoicesId as $id) {
                $cominvoice = invoices::find($id);
                if ($cominvoice->Pay == 'Cash') {

                    foreach (return_sales::where('invoice_id', $id)->get() as $returnsale) {
                        $valuewithoudtax += ($returnsale->return_Unit_Price * $returnsale->return_quantity) - $returnsale->discountvalue - $returnsale->discountoninvoice;
                    }
                    $returnsalescash += ($valuewithoudtax)  + (($valuewithoudtax) * $saleavt);
                    $valuewithoudtax = 0;
                } elseif ($cominvoice->Pay == 'Credit') {

                    foreach (return_sales::where('invoice_id', $id)->get() as $returnsale) {
                        $valuewithoudtax += ($returnsale->return_Unit_Price * $returnsale->return_quantity) - $returnsale->discountvalue - $returnsale->discountoninvoice;
                    }
                    $returnsalescredit += ($valuewithoudtax)  + (($valuewithoudtax) * $saleavt);
                    $valuewithoudtax = 0;
                } elseif ($cominvoice->Pay == 'Shabka') {

                    foreach (return_sales::where('invoice_id', $id)->get() as $returnsale) {
                        $valuewithoudtax += ($returnsale->return_Unit_Price * $returnsale->return_quantity) - $returnsale->discountvalue - $returnsale->discountoninvoice;
                    }
                    $returnsalesshabka += ($valuewithoudtax)  + (($valuewithoudtax) * $saleavt);
                    $valuewithoudtax = 0;
                } elseif ($cominvoice->Pay == 'Bank_transfer') {

                    foreach (return_sales::where('invoice_id', $id)->get() as $returnsale) {
                        $valuewithoudtax += ($returnsale->return_Unit_Price * $returnsale->return_quantity) - $returnsale->discountvalue - $returnsale->discountoninvoice;
                    }
                    $returnSalesBankTransfer += ($valuewithoudtax)  + (($valuewithoudtax) * $saleavt);
                    $valuewithoudtax = 0;
                } else {
                    foreach (return_sales::where('invoice_id', $id)->get() as $returnsale) {
                        $valuewithoudtax += ($returnsale->return_Unit_Price * $returnsale->return_quantity) - $returnsale->discountvalue - $returnsale->discountoninvoice;

                        $returnsalespartialshabka += $returnsale->returnshabkavalue;
                    }
                    $returnSalespartial += ($valuewithoudtax)  + (($valuewithoudtax) * $saleavt);
                    $valuewithoudtax = 0;
                }
            }
        }




        if ($returnpurchases != []) {

            foreach ($returnpurchases as $returnpurchase) {
                if ($returnpurchase->Pay_Method_Name == 'Cash') {
                    $allreturn = 1;
                    $returnpurchasesdetiales = orderDetails::where('order_owner', $returnpurchase->orderId)->where('returns_purchase', '!=', 0)->get();
                    foreach ($returnpurchasesdetiales as $returnpurchasesdetiale) {

                        $returnpurchasecash += ($returnpurchasesdetiale->purchasingـprice + $returnpurchasesdetiale->Added_Value) * $returnpurchasesdetiale->returns_purchase;

                        if ($returnpurchasesdetiale->All_QUENTITY != 0) {
                            $allreturn = 0;
                        }
                    }
                    if ($allreturn == 1) {
                        $resource_purchases1 =  resource_purchases::where('orderId', $returnpurchase->orderId)->first();
                        $returnpurchasecash -= $resource_purchases1->discount;
                    }
                } elseif ($returnpurchase->Pay_Method_Name == 'Credit') {
                    $allreturn = 1;

                    $returnpurchasesdetiales = orderDetails::where('order_owner', $returnpurchase->orderId)->where('returns_purchase', '!=', 0)->get();
                    foreach ($returnpurchasesdetiales as $returnpurchasesdetiale) {

                        $returnpurchasecredit += ($returnpurchasesdetiale->purchasingـprice + $returnpurchasesdetiale->Added_Value) * $returnpurchasesdetiale->returns_purchase;
                        if ($returnpurchasesdetiale->All_QUENTITY != 0) {
                            $allreturn = 0;
                        }
                    }
                    if ($allreturn == 1) {
                        $resource_purchases1 =  resource_purchases::where('orderId', $returnpurchase->orderId)->first();
                        $returnpurchasecredit -= $resource_purchases1->discount;
                    }
                } elseif ($returnpurchase->Pay_Method_Name == 'Bank_transfer') {
                    $allreturn = 1;

                    $returnpurchasesdetiales = orderDetails::where('order_owner', $returnpurchase->orderId)->where('returns_purchase', '!=', 0)->get();
                    foreach ($returnpurchasesdetiales as $returnpurchasesdetiale) {

                        $returnpurchasebanktransfer += ($returnpurchasesdetiale->purchasingـprice + $returnpurchasesdetiale->Added_Value) * $returnpurchasesdetiale->returns_purchase;
                        if ($returnpurchasesdetiale->All_QUENTITY != 0) {
                            $allreturn = 0;
                        }
                    }
                    if ($allreturn == 1) {
                        $resource_purchases1 =  resource_purchases::where('orderId', $returnpurchase->orderId)->first();
                        $returnpurchasebanktransfer -= $resource_purchases1->discount;
                    }
                } else {
                    $allreturn = 1;

                    $returnpurchasesdetiales = orderDetails::where('order_owner', $returnpurchase->orderId)->where('returns_purchase', '!=', 0)->get();
                    foreach ($returnpurchasesdetiales as $returnpurchasesdetiale) {
                        $returnpurchaseshabka += ($returnpurchasesdetiale->purchasingـprice + $returnpurchasesdetiale->Added_Value) * $returnpurchasesdetiale->returns_purchase;
                        if ($returnpurchasesdetiale->All_QUENTITY != 0) {
                            $allreturn = 0;
                        }
                    }
                    if ($allreturn == 1) {
                        $resource_purchases1 =  resource_purchases::where('orderId', $returnpurchase->orderId)->first();
                        $returnpurchaseshabka -= $resource_purchases1->discount;
                    }
                }
            }

$shippingandunloadingCost=0;
            if (1) {

      
$salescash = $totals->total_cash ?? 0;
$salesshabka = $totals->total_bank ?? 0;
$salescredit = $totals->total_credit ?? 0;
$salesBankTransfer = $totals->total_transfer ?? 0;


                //return $salescredit;
                foreach ($pirchese as $purchese) {
                    $shippingandunloadingCost += $purchese['shipping fee'] + $purchese['Other expenses'];


                    if ($purchese->Pay_Method_Name == 'Cash') {
                        $All_QUENTITY = 0;
                        foreach (orderDetails::where('order_owner', $purchese->orderId)->get() as $item) {
                            $All_QUENTITY = $item->numberofpice + $item->returns_purchase;
                            $purchesecash += $All_QUENTITY * $item->purchasingـprice + $item->Added_Value * $All_QUENTITY;
                        }
                        $purchesecash -= $purchese->discount;
                    } elseif ($purchese->Pay_Method_Name == 'Credit') {

                        $All_QUENTITY = 0;
                        foreach (orderDetails::where('order_owner', $purchese->orderId)->get() as $item) {
                            $All_QUENTITY = $item->numberofpice + $item->returns_purchase;
                            $purchesecredit += $All_QUENTITY * $item->purchasingـprice + $item->Added_Value * $All_QUENTITY;
                        }
                        $purchesecredit -= $purchese->discount;
                        // $purchesecredit += $purchese->In_debt;
                    } elseif ($purchese->Pay_Method_Name == 'Bank_transfer') {
                        $All_QUENTITY = 0;
                        foreach (orderDetails::where('order_owner', $purchese->orderId)->get() as $item) {
                            $All_QUENTITY = $item->numberofpice + $item->returns_purchase;
                            $purchasebankTransfer += $All_QUENTITY * $item->purchasingـprice + $item->Added_Value * $All_QUENTITY;
                        }
                        $purchasebankTransfer -= $purchese->discount;
                        //  $purchasebankTransfer += $purchese->In_debt;
                    } else {
                        $All_QUENTITY = 0;
                        foreach (orderDetails::where('order_owner', $purchese->orderId)->get() as $item) {
                            $All_QUENTITY = $item->numberofpice + $item->returns_purchase;
                            $purcheseshabka += $All_QUENTITY * $item->purchasingـprice + $item->Added_Value * $All_QUENTITY;
                        }
                        $purcheseshabka -= $purchese->discount;
                    }
                }
            }
     

            if ($expenses != []) {


                foreach ($expenses as $expense) {

                    if ($expense->Pay_Method_Name == 'Cash') {
                        $expenses_cash += $expense->Theـamountـpaid;
                    } elseif ($expense->Pay_Method_Name == 'Bank_transfer') {
                        $expenses_banktransfer += $expense->Theـamountـpaid;
                    } else {
                        $expenses_shabka += $expense->Theـamountـpaid;
                    }
                }
                $creadit_customers = customers::where('Balance', '!=', 0)->get();
                $credit_suppliers = supllier::where('In_debt', '!=', 0)->get();
                $creadit_customer_amount = 0;
                $credit_supplier_amount = 0;

                foreach ($creadit_customers as $creadit_customer) {
                    $creadit_customer_amount +=  $creadit_customer->Balance;
                }

                foreach ($credit_suppliers as $credit_supplier) {
                    $credit_supplier_amount +=  $credit_supplier->In_debt;
                }
                // return  $credit_supplier_amount;
            }
            $data = [
                'transferMoney_to_mainbranchshabka' => 0,
                'transferMoney_to_mainbranchCash' => 0,
                'transferMoney_to_mainbranchshabkafrombranchas' => [],
                'reportforbranch' => $request->branch,

                'returnsalescash' => round($returnsalescash, 1),
                'returnsalescredit' => round($returnsalescredit, 2),
                'returnsalesshabka' => round($returnsalesshabka, 2),
                'returnSalespartial' => round($returnSalespartial, 2),
                'returnsalespartialshabka' => round($returnsalespartialshabka, 2),
                'returnSalesBankTransfer' => round($returnSalesBankTransfer, 2),


                'returnpurchasecash' => round($returnpurchasecash, 2),
                'returnpurchasecredit' => round($returnpurchasecredit, 2),
                'returnpurchaseshabka' => round($returnpurchaseshabka, 2),
                'returnpurchasebanktransfer' => round($returnpurchasebanktransfer, 2),

                'Transfer_cash_to_the_next_day' => 0,
                'Transfer_cash_from_the_last_day' => 0,
                'totalconvertlastDay' => 0,

                'shippingandunloadingCost' => round(0, 2),
                'salescash' => round($salescash, 2),
                'salescredit' => round($salescredit, 2),
                'salesshabka' => round($salesshabka, 2),
                'salesBankTransfer' => round($salesBankTransfer, 2),

                'purchesecash' => round($purchesecash, 2),
                'purchesecredit' => round($purchesecredit, 2),
                'purcheseshabka' => round($purcheseshabka, 2),
                'purchasebankTransfer' => round($purchasebankTransfer, 2),

                'credittransaction_cash' => round(0, 2),
                'credittransaction_shabka' => round(0, 2),
                'credittransaction_banktransfer' => round(0, 2),

                'transactiontosuplliers_cash' => round(0, 2),
                'transactiontosuplliers_shabka' => round(0, 2),
                'transactiontosuplliers_banktransfer' => round(0, 2),

                'expenses_cash' => round($expenses_cash, 2),
                'expenses_shabka' => round($expenses_shabka, 2),
                'expenses_banktransfer' => round($expenses_banktransfer, 2),

                'cash_last_month' => round(0, 2),
                'creadit_customer_amount' => round(0, 2),
                'credit_supplier_amount' => round(0, 2),
                "start_at" => $start_at,
                "end_at" => $end_at,
                'bank_cash' => $bank_cash,
                'bank_shabka' => $bank_shabka,
                'branch' => $branchname,

                'benfitcradit' => $benfitcradit,
                'benfitshabka' => $benfitshabka,
                'benfitcash' => $benfitcash,
                'benfitBank_transfer' => $benfitBank_transfer,
                'convertcashboxToBankitemamount' => 0
            ];
            return view('reports.print_budget_sheet', compact('data'));
        }
    }

    public function print_Transfer_products($invoiceId)
    {
        $product_movement_another_branch_data = product_movement_another_branch::find($invoiceId);
        $items = product_movement_another_branch_items::where('order_id', $invoiceId)->get();
        $data = [
            "invoice" => $product_movement_another_branch_data,
            "itemsdetails" => $items
        ];
        return view('supProcesses.print_send_product', compact('data'));
    }

    //adition AboFhad

    public function addetions($request)
    {


        $start_at = $request->start_at;
        $end_at = $request->end_at;

        $salescash = 0;


        $purchesecash = 0;


        $credittransaction_cash = 0;


        $transactiontosuplliers_cash = 0;

        $expenses_cash = 0;


        $dorgcash = 0;

        $Invoices = [];
        $pirchese = [];
        $credittransactions = [];
        $transactiontosuplliers = [];
        $expenses = [];
        if ($request->branch == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $pirchese = resource_purchases::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $credittransactions = credittransactions::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $transactiontosuplliers = transactiontosuplliers::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $expenses = expenses::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
        } else {
            $Invoices = invoices::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $pirchese = resource_purchases::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $credittransactions = credittransactions::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $transactiontosuplliers = transactiontosuplliers::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            $expenses = expenses::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
        }
        //return $Invoices;
        foreach ($Invoices as $invoice) {
            if ($invoice->Pay == 'Cash') {
                $salescash += $invoice->Price + $invoice->Added_Value;
            }
        }

        foreach ($pirchese as $purchese) {


            if ($purchese->Pay_Method_Name == 'Cash') {
                $purchesecash += $purchese->In_debt;
            }
        }

        foreach ($credittransactions as $credittransaction) {

            if ($credittransaction->pay_method    == 'Cash') {
                $credittransaction_cash += $credittransaction->recive_amount;
            }
        }

        foreach ($transactiontosuplliers as $transactiontosupllier) {

            if ($transactiontosupllier->Pay_Method_Name == 'Cash') {
                $transactiontosuplliers_cash += $transactiontosupllier->paidـamount;
            }
        }


        foreach ($expenses as $expense) {

            if ($expense->Pay_Method_Name == 'Cash') {
                $expenses_cash += $expense->Theـamountـpaid;
            }
        }
        $total_cash_dorg = ($salescash + $credittransaction_cash) - ($expenses_cash + $transactiontosuplliers_cash  + $purchesecash);

        return $total_cash_dorg;
    }



    //end addetion








    public function search_Purchasesـfromـsuppliers(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $pay = $request->pay;
        $branch = $request->branch;

        if ($request->clientnamesearch == '-') {


            if ($pay == '-' && $branch == '-') {
                $Invoices = resource_purchases::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

                return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch, '-']);
            } elseif ($pay != '-' && $branch == '-') {
                $Invoices = resource_purchases::where('Pay_Method_Name', $pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

                return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch], '-');
            } elseif ($pay == '-' && $branch != '-') {
                $Invoices = resource_purchases::where('branchs_id', $branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

                return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch], '-');
            }
            $Invoices = resource_purchases::where('branchs_id', $branch)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch, '-']);
        } else {
            if ($pay == '-' && $branch == '-') {
                $Invoices = resource_purchases::where('suplier_id', $request->clientnamesearch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

                return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch, $request->clientnamesearch]);
            } elseif ($pay != '-' && $branch == '-') {
                $Invoices = resource_purchases::where('suplier_id', $request->clientnamesearch)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

                return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch, $request->clientnamesearch]);
            } elseif ($pay == '-' && $branch != '-') {
                $Invoices = resource_purchases::where('suplier_id', $request->clientnamesearch)->where('branchs_id', $branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

                return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch, $request->clientnamesearch]);
            }
            $Invoices = resource_purchases::where('suplier_id', $request->clientnamesearch)->where('branchs_id', $branch)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            return view('reports.Purchases_from_suppliers', compact('Invoices'))->with('pay', [$pay, $branch, $request->clientnamesearch]);
        }
    }






    public function search_Requestـaـquoteـfromـtheـsupplier(Request $request)
    {
        //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->supplierId;
        if ($supplierId == '-' && $request->branch == '-') {
            $Invoices = order_price_from_supplier::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;

        } elseif ($supplierId != '-' && $request->branch == '-') {
            $Invoices = order_price_from_supplier::where('suplier_id', $supplierId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        } elseif ($supplierId == '-' && $request->branch != '-') {
            $Invoices = order_price_from_supplier::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        } else {
            $Invoices = order_price_from_supplier::where('suplier_id', $supplierId)->where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        }
        //return $Invoices;
        return view('reports.Request_A_quote_from_supplier', compact('Invoices'))->with('supplierId', [$supplierId, $request->branch]);
    }




    public function search_Requestـoffersـfromـsuppliers(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->supplierId;
        if ($supplierId == '-') {
            $Invoices = orderTosupllier::where('Limit_credit', '')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            //return $Invoices;        
            return view('reports.Request_offers_from_suppliers', compact('Invoices'))->with('supplierId', $supplierId);
        }
        $Invoices = orderTosupllier::where('Limit_credit', '')->where('suplier_id', $request->supplierId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        //return $Invoices;        

        return view('reports.Request_offers_from_suppliers', compact('Invoices'))->with('supplierId', $supplierId);
    }



    public function search_product_sales(Request $request)
    {
        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $productId = $request->productNo ?? '-';
        if ($productId == '-') {
            session()->flash('notfountreturnproduct', __('home.productnotfount'));
            $Invoices = [];
            return view('reports.product_sales', compact('Invoices'))->with('branch_Id', $request->branch);
        } else {
            if ($request->branch == '-' && $productId == '-') {
                $products = sales::where('product_id', $productId)->where('quantity', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            } elseif ($request->branch == '-' && $productId != '-') {
                $products = sales::where('product_id', $productId)->where('quantity', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            } elseif ($request->branch != '-' && $productId == '-') {
                $products = sales::where('branch_id', $request->branch)->where('quantity', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            } else {
                $products = sales::where('product_id', $productId)->where('branch_id', $request->branch)->where('quantity', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            }
        }


        //return $products;
        return view('reports.product_sales', compact('products'))->with('branch_Id', $request->branch);
    }


    public function viewnetworksales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //  return $request;

        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay', 'SHABKA')->get();

        //return $Invoices;
        return view('reports.networksales', compact('Invoices'));
    }

    public function employeeSalesSearch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //  return $request;

        $Invoices = invoices::where('user_id', $request->productname)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

        //return $Invoices;
        return view('reports.employeesales', compact('Invoices'));
    }
    public function viewCashsales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay', 'Cash')->get();


        return view('reports.Cashsales', compact('Invoices'));
    }


    public function search_report_returns_sale(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->branch == '-') {
            $Invoices = return_sales::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        } else {
            $Invoices = return_sales::where('branch_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        }

        //return $Invoices;
        return view('reports.report_returns_sale', compact('Invoices'))->with('branch_Id', $request->branch);
    }
    public function Show_return_Sales_Details($request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $return_sales = return_sales::where('invoice_id', $request)->get();
        $InvoiceSale = invoices::where('id', $request)->first();
        $data = [
            'invoiceData' => $InvoiceSale,
            'salesData' => $return_sales
        ];

        //return $data;
        return view('reports.report_returns_sale_details', compact('data'));
    }

    public function salesـprofitssearch(Request $request)
    {
        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->UserId == '-' && $request->branch == '-') {

            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } elseif ($request->UserId != '-' && $request->branch == '-') {

            $Invoices = invoices::where('customer_id', $request->UserId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } elseif ($request->UserId == '-' && $request->branch != '-') {

            $Invoices = invoices::where('branchs_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } else {
            $Invoices = invoices::where('branchs_id', $request->branch)->where('customer_id', $request->UserId)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        }
        //return $Invoices;

        return view('reports.sales_profits', compact('Invoices'))->with('userId', $request->UserId)->with('branch_id', $request->branch);
    }


    public function viewCreditsales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('Pay', 'Credit')->get();
        return view('reports.Creditsales', compact('Invoices'));
    }


    public function print_Supplier_credit_payment($supplierId, $startat, $end_at)
    {
        //return $request;
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($supplierId == '-') {
            $Invoices = transactiontosuplliers::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;
            return view('reports.print_Supplier_credit_payment', compact('Invoices'))->with('supplierId', $supplierId);
        }

        //return $Invoices;
        $Invoices = transactiontosuplliers::where('suplier_id', $supplierId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        //return $Invoices;
        return view('reports.print_Supplier_credit_payment', compact('Invoices'))->with('supplierId', $supplierId);
    }



    public function print_shift_detailes($branch, $pay, $startat, $end_at)
    {
        // return $request;
        $start_at = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($branch == '-' && $pay == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

            return view('reports.print_shift_detailes', compact('Invoices'));
        }
        if ($branch == '-' && $pay == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;       
            return view('reports.print_shift_detailes', compact('Invoices'));
        } elseif ($branch != '-' && $pay == '-') {
            $Invoices = invoices::where('branchs_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

            return view('reports.print_shift_detailes', compact('Invoices'))->with('pay', [$pay, $branch]);
        } elseif ($branch == '-' && $pay != '-') {
            $Invoices = invoices::where('Pay', $pay)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

            return view('reports.print_shift_detailes', compact('Invoices'))->with('pay', [$pay, $branch]);
        } else {
            $Invoices = invoices::where('branchs_id', $branch)->where('Pay', $pay)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;
            return view('reports.print_shift_detailes', compact('Invoices'));
        }
    }











    public function printReportemployeeSales($usertId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $Invoices = invoices::where('user_id', $usertId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();



        //  return $usertId;
        return view('reports.print_report_employee_sales', compact('Invoices'));
    }
    public function print_credit_collection($userId, $startat, $end_at)
    {

        // return $request;
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $UserId = $userId;
        if ($UserId == '-') {
            $Invoices = credittransactions::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

            return view('reports.print_credit_collection', compact('Invoices'));
        }
        $Invoices = credittransactions::where('customer_id', $UserId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        // return $Invoices;
        return view('reports.print_credit_collection', compact('Invoices'));
    }
    public function  print_purchasereports($productId, $startat, $end_at)
    {



        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $productId;
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        if ($supplierId == '-') {
            $products = orderDetails::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

            return view('reports.print_purchasereports', compact('products'));
        }
        $products = orderDetails::where('product_id', $productId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        //return $Invoices;
        return view('reports.print_purchasereports', compact('products'));
    }






    public function print_Purchasesـfromـsuppliers($branch, $pay, $supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $Invoices = [];
        if ($supplierId) {
            if ($pay == '-' && $branch == '-') {
                $Invoices = resource_purchases::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            } elseif ($pay != '-' && $branch == '-') {
                $Invoices = resource_purchases::where('Pay_Method_Name', $pay)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            } elseif ($pay == '-' && $branch != '-') {
                $Invoices = resource_purchases::where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            } else {
                $Invoices = resource_purchases::where('Pay_Method_Name', $pay)->where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            }
        } else {
            if ($pay == '-' && $branch == '-') {
                $Invoices = resource_purchases::where('suplier_id', $supplierId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            } elseif ($pay != '-' && $branch == '-') {
                $Invoices = resource_purchases::where('suplier_id', $supplierId)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            } elseif ($pay == '-' && $branch != '-') {
                $Invoices = resource_purchases::where('suplier_id', $supplierId)->where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            } else {
                $Invoices = resource_purchases::where('suplier_id', $supplierId)->where('Pay_Method_Name', $pay)->where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            }
        }



        return view('reports.print_Purchases_from_suppliers', compact('Invoices'))->with('pay', $pay);
    }



    public function print_Refundـofـresourceـpurchases($branch_id, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        if ($branch_id != '-') {
            $Invoices = resource_purchases::where('branchs_id', $branch_id)->where('recoveredـpieces', '!=', 0)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();

            return view('reports.print_Refund_of_resource_purchases', compact('Invoices'));
        }
        $Invoices = resource_purchases::where('recoveredـpieces', '!=', 0)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();

        return view('reports.print_Refund_of_resource_purchases', compact('Invoices'));
    }


    public function printReportoffer_price_customer($userId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        if ($userId == '-') {
            $Invoices = offer_price_to_customer::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=',   $end_at)->get();
            return view('reports.printReportoffer_price_customer', compact('Invoices'));
        }
        $Invoices = offer_price_to_customer::where('customer_id', $userId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=',   $end_at)->get();



        // return $Invoices;
        return view('reports.printReportoffer_price_customer', compact('Invoices'));
    }


    public function print_Requestـaـquoteـfromـtheـsupplier($branch, $supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $supplierId = $supplierId;




        if ($supplierId == '-' && $branch == '-') {
            $Invoices = order_price_from_supplier::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

        } elseif ($supplierId != '-' && $branch == '-') {
            $Invoices = order_price_from_supplier::where('suplier_id', $supplierId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        } elseif ($supplierId == '-' && $branch != '-') {
            $Invoices = order_price_from_supplier::where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        } else {
            $Invoices = order_price_from_supplier::where('branchs_id', $branch)->where('suplier_id', $supplierId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        }






        return view('reports.print_Request_A_quote_from_supplier', compact('Invoices'));
    }







    public function printDelivery_notes($orderId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $supplierId = $orderId;
        if ($supplierId == '-') {
            $Invoices = resource_purchases::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
            //return $Invoices;

            return view('reports.print_Report_delivery_notes', compact('Invoices'));
        }
        $Invoices = resource_purchases::where('orderId', $supplierId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        //return $Invoices;
        return view('reports.print_Report_delivery_notes', compact('Invoices'));
    }

    public function print_report_order_from_supplier($supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);

        //


        if ($supplierId == '-') {
            $Invoices = orderTosupllier::where('Limit_credit', '')->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=',   $end_at)->get();
            return view('reports.print_report_order_from_supplier', compact('Invoices'));
        }
        $Invoices = orderTosupllier::where('Limit_credit', '')->where('suplier_id', $supplierId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=',   $end_at)->get();

        //



        // return $Invoices;
        return view('reports.print_report_order_from_supplier', compact('Invoices'));
    }



    public function printReportProfitSales($branch, $UserId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);

        if ($UserId == '-' && $branch == '-') {

            $Invoices = invoices::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        } elseif ($UserId != '-' && $branch == '-') {

            $Invoices = invoices::where('customer_id', $UserId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        } elseif ($UserId == '-' && $branch != '-') {

            $Invoices = invoices::where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        } else {
            $Invoices = invoices::where('customer_id', $UserId)->where('branchs_id', $branch)->where('customer_id', $UserId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        }


        // return $Invoices;
        return view('reports.printReportProfitSales', compact('Invoices'));
    }



    public function printReportProductSales($branch, $productId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);





        if ($branch == '-') {
            $products = sales::where('product_id', $productId)->where('quantity', '!=', 0)->whereDate('created_at', '>=',  $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        } else {
            $products = sales::where('product_id', $productId)->where('branch_id', $branch)->where('quantity', '!=', 0)->whereDate('created_at', '>=',  $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        }


        //return $products;
        return view('reports.printReportProductSales', compact('products'));
    }

    public function print_customerـpurchases($branch, $customerId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $Invoices = [];
        $typeinvoise = '';
        $salesreport = 'no';
        if ($customerId == '-' && $branch == "-") {

            $Invoices = invoices::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
        } else {

            if ($customerId == "-" && $branch != "-") {
                $Invoices = invoices::where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            } elseif ($customerId != "-" && $branch == "-") {
                $Invoices = invoices::where('customer_id', $customerId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            } else {
                $Invoices = invoices::where('branchs_id', $branch)->where('customer_id', $customerId)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            }




            $typeinvoise = __('report.customerpurchases');
        }


        $data = [
            'invoices' => $Invoices,
            'typeinvoise' => $typeinvoise,
            'salesreport' => 'no'
        ];
        //  return $data;
        return view('reports.print_customer_purchases', compact('data'));
    }






    public function printInvoicesReport($branch, $pay, $startat, $end_at)
    {
        //return $request
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        $Invoices = [];
        $typeinvoise = '';
        $salesreport = 'no';
        if ($pay == '-' && $branch == "-") {

            $Invoices = invoices::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            $salesreport = 'yes';
            $typeinvoise = 'Seles report';
        } else {

            if ($pay == "-" && $branch != "-") {
                $Invoices = invoices::where('branchs_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            } elseif ($pay != "-" && $branch == "-") {
                $Invoices = invoices::where('Pay', $pay)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            } else {
                $Invoices = invoices::where('branchs_id', $branch)->where('Pay', $pay)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->where('save', 1)->get();
            }




            $typeinvoise = $pay;
        }


        $data = [
            'invoices' => $Invoices,
            'typeinvoise' => $typeinvoise,
            'salesreport' => $salesreport
        ];
        if ($typeinvoise == 'Seles report') {
            return view('reports.printReportsales', compact('data'));
        }
        //  return $data;
        return view('reports.printReportInvoices', compact('data'));
    }
    public function salesReport()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return  view('reports.sales_report');
    }




    public function Stocktaking()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
          return Excel::download(new Exportproducts(), 'PRODECTS'.'.xlsx');

       
    }

    public function Stocktakingpdf()
    {
        return Excel::download(new Exportproducts, 'products.pdf', \Maatwebsite\Excel\Excel::DOMPDF, [
            // 'Content-Type' => 'text/csv',
        ]);
    }



    public function salesReportsearch(Request $request)
    {
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($request->pay == "-" && $request->branch == "-") {
            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } elseif ($request->pay == "-" && $request->branch != "-") {
            $Invoices = invoices::where('branchs_id', $request->branch)->where('save', 1)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        } elseif ($request->pay != "-" && $request->branch == "-") {
            $Invoices = invoices::where('Pay', $request->pay)->where('save', 1)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        

        } else {
            $Invoices = invoices::where('branchs_id', $request->branch)->where('save', 1)->where('Pay', $request->pay)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();

        }
        return view('reports.sales_report', compact('Invoices'))->with('pay', [$request->pay, $request->branch]);
    }

    public function print_return_Report($branch, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $startat = str_split($startat, 10);
        $end_at = str_split($end_at, 10);
        if ($branch == '-') {
            $Invoices = return_sales::whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        } else {
            $Invoices = return_sales::where('branch_id', $branch)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        }




        //return  $Invoices;
        return view('reports.print_report_sales_returen', compact('Invoices'));
    }


    public function printstockquantity($branch, $operation, $quantity)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($branch == '-') {
            if ($operation == '==') {
                $products = products::where('All_QUENTITY', $quantity)->where('parent_inv_itemcard_id',0)->get();
            } else {
                $products = products::where('All_QUENTITY', $operation, $quantity)->where('parent_inv_itemcard_id',0)->get();
            }
        } else {
            if ($operation == '==') {
                $products = products::where('All_QUENTITY', $quantity)->where('branchs_id', $branch)->where('parent_inv_itemcard_id',0)->get();
            } else {
                $products = products::where('branchs_id', $branch)->where('All_QUENTITY', $operation, $quantity)->where('parent_inv_itemcard_id',0)->get();
            }
        }
        return view('reports.printstockquantity', compact('products'));
    }




    public function printBest_selling_products($branch, $start_at, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($branch == '-') {
            $bestSaleing = sales::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('quantity', ">", 0)->where('save', 1)->get();
        } else {
            $bestSaleing = sales::where('branch_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('quantity', ">", 0)->where('save', 1)->get();
        }
        //return $bestSaleing;

        $bestselling = [];
        $listofId = [];
        $i = 0;
        foreach ($bestSaleing as $product) {
            if (!in_array($product->product_id, $listofId)) {
                //   return $bestSaleing;

                if ($branch == '-') {
                    $bestSaleingofproduct = sales::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('product_id', $product->product_id)->where('quantity', ">", 0)->where('save', 1)->get();
                } else {
                    $bestSaleingofproduct = sales::where('branch_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('product_id', $product->product_id)->where('quantity', ">", 0)->where('save', 1)->get();
                }
                $numberOfSall = 0;
                foreach ($bestSaleingofproduct as $saleproduct) {

                    $numberOfSall += $saleproduct->quantity;
                    $bestselling[$i] = [
                        'productcode' => $saleproduct->productData->barcode,
                        'productname' => $saleproduct->productData->name,
                        'numberofsall' => $numberOfSall,
                        'branch' => $saleproduct->branch->name,
                        'end_at' => $end_at,
                        'start_at' => $start_at
                    ];
                }
                $i++;
            }
            $listofId[] = $product->product_id;
        }

        // $bestselling=asort($bestSaleing);
        // return $bestSaleing;
        return view('reports.printBest_selling_products', compact('bestselling'))->with('date', [$start_at, $end_at]);
    }





    public function print_VAT($branch, $start_at, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        //return $request;
        $totalVatSales = 0;
        $totalVatPrachese = 0;
        $totalvarExpenses = 0;
        $avt = Avt::find(1);
        $avtpurchases = Avt::find(2);
        $saleavt = $avt->AVT;
        $purchasesavt = $avtpurchases->AVT;


        if ($branch == '-') {
            $invoices = invoices::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save',1)->get();
            $expenses = expenses::where('expensesAvt', 1)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();


            foreach ($invoices as $invoice) {
                $totalVatSales += ($invoice->Price - $invoice->discount) * $saleavt;
            }
            foreach ($expenses as $expense) {
                $totalvarExpenses +=  $expense->Theـamountـpaid * $purchasesavt;
            }
            $resource_purchases = resource_purchases::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save',1)->get();
            foreach ($resource_purchases as $resource_purchase) {
                $ordersDetails = orderDetails::where('order_owner', $resource_purchase->orderId)->where('numberofpice', '>', 0)->get();
                foreach ($ordersDetails as $orderDetailes) {
                    $totalVatPrachese += $orderDetailes->Added_Value * $orderDetailes->numberofpice;
                }
            }
        } else {
            $invoices = invoices::where('branchs_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save',1)->get();
            $expenses = expenses::where('expensesAvt', 1)->where('branchs_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->get();

            foreach ($invoices as $invoice) {
                $totalVatSales += ($invoice->Price - $invoice->discount) * $saleavt;
            }
            foreach ($expenses as $expense) {
                $totalvarExpenses +=  $expense->Theـamountـpaid * $purchasesavt;
            }
            $resource_purchases = resource_purchases::where('branchs_id', $branch)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save',1)->get();
            foreach ($resource_purchases as $resource_purchase) {
                $ordersDetails = orderDetails::where('order_owner', $resource_purchase->orderId)->where('numberofpice', '>', 0)->get();
                foreach ($ordersDetails as $orderDetailes) {
                    $totalVatPrachese += $orderDetailes->Added_Value * $orderDetailes->numberofpice;
                }
            }
        }

        $data = [
            'start_at' => $start_at,
            'end_at' => $end_at,
            'totalVatSales' =>  $totalVatSales,
            'totalVatPrachese' => $totalVatPrachese,
            'totalvarExpenses' => $totalvarExpenses
        ];

        return view('reports.print_VAT', compact('data'));
    }



    function get_cols_where_row_orderby($model, $columns_names = array(), $where = array(), $order_field="id",$order_type="DESC")
    {
    $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->first();
    return $data;
    }
    
    public function get_cols_where_p($model=null, $columns_names = array(), $where = array(), $order_field="id",$order_type="DESC",$pagination_counter=13)
    {
    $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->paginate($pagination_counter);
    return $data;
    }
    function get_cols_where($model=null, $columns_names = array(), $where = array(), $order_field="id",$order_type="DESC")
    {
    $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->get();
    return $data;
    }
    function get_field_value($model=null, $field_name=null , $where = array())
    {
    $data = $model::where($where)->value($field_name);
    return $data;
    }
    function get_cols_where_row($model=null, $columns_names = array(), $where = array())
    {
    $data = $model::select($columns_names)->where($where)->first();
    return $data;
    }
    }
