<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transactiontosuplliers;
use App\Models\supllier;
use App\Models\User;
use App\Models\expenses;
use App\Models\convertcashboxToBank;
use App\Models\customers;
use App\Models\credittransactions;
use App\Models\invoices;
use App\Models\cash_from__bank;
use App\Models\Transfer_cash_to_the_next_day;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class AcountesController extends Controller
{
    //

    public function voncher()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $allcustomers = customers::get();
        $data = [
            "transaction" => [],
            "allcustomers" =>  $allcustomers,
        ];
        return view('acountes.voncher', compact('data'));
    }


    public function convertcashboxToBank()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];

        return view('acountes.convertcashboxToBank', compact('data'));
    }
    public function Transfer_cash_to_next_day()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];



        return view('acountes.Transfer_cash_to_next_day', compact('data'));
    }
    public function transferMainBranch()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = [];
        return view('acountes.Transfertomainbranch', compact('data'));
    }
    public function go_to_bank()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = [];
        return view('acountes.cash_from_bank', compact('data'));
    }
    public function confirmTransfertomainbranch()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = [];
        return view('acountes.confirm_transferTomainBranch', compact('data'));
    }
    public function Transfercashto_the_next_day(Request $request)
    {
    $data = Transfer_cash_to_the_next_day::create(
            [
                'user_id' => Auth()->user()->id,
                'branchs_id' => Auth()->user()->branchs_id,
                'amount' => $request->The_amount_transferred_amount,
                'note' => $request->notes,
                'created_at' =>$request->date,
            ]
        );
        $data1 = [
            'id' => $data->id,
            'user' => $data->user->name,
            'branch' => $data->branch->name,
            'the_amount' => $data->amount,
            'created_at' => $data->created_at->format('d/m/Y'),
        ];
        return $data1;
    }


    public function updatedecoumentcashNextDay(Request $request)
    {
       // return $request;
     Transfer_cash_to_the_next_day::find($request->transactionId)->update(
            [
               
                'amount' => $request->The_amount_transferred_amount,
                'note' => $request->notes,
            ]
        );
        $data = Transfer_cash_to_the_next_day::find($request->transactionId);
        $data1 = [
            'id' => $data->id,
            'user' => $data->user->name,
            'branch' => $data->branch->name,
            'the_amount' => $data->amount,
            'created_at' => $data->created_at->format('d/m/Y'),
        ];
        return $data1;
    }



    public function Add_blance_from_bank(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = cash_from__bank::create(
            [
                'user_id' => Auth()->user()->id,
                'branchs_id' => $request->branchs_id,
                'the_amount' => $request->cashreceived,
                'payment_method' => $request->pay,
                'created_at' => $request->start_at,
            ]
        );
        $pay = '-';
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        } elseif ($request->pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data = [
            'id' => $data->id,
            'user' => $data->user->name,
            'branchs' => $data->branch->name,
            'the_amount' => $request->cashreceived,
            'payment_method' => $pay,
            'created_at' => $request->start_at,
        ];
        return $data;
        return view('acountes.cash_from_bank', compact('data'));
    }



    public function updateAdd_blance_from_bank(Request $request)
    {
        $cash_from__bank = cash_from__bank::find($request->transactionId);
        $data = cash_from__bank::find($request->transactionId)->update(
            [
                'user_id' => Auth()->user()->id,
                'branchs_id' => $request->updatebranchs_id,
                'the_amount' => $request->cashreceivedupdate,
                'payment_method' => $request->payupdate,
            ]
        );
        $pay = '-';
        if ($request->payupdate == 'Cash') {
            $pay = __('report.cash');
        } elseif ($request->payupdate == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data1 = cash_from__bank::find($request->transactionId);

        $data = [
            'id' => $data1->id,
            'user' => $data1->user->name,
            'branchs' => $data1->branch->name,
            'the_amount' => $data1->the_amount,
            'payment_method' => $pay,
            'created_at' => $data1->created_at->format('d/m/Y'),
        ];
        return $data;
    }







    public function SearchconvertcashboxToBank(Request $request)
    {

        //eturn $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = convertcashboxToBank::create(
            [
                'from_user_id' => Auth()->user()->id,
                'amount' => $request->cashreceived,
                'branchs_id' => $request->branchs_id,
                'note' => $request->notes ?? "-",
                'created_at' => $request->start_at,
            ]
        );
        $data = [
            'user' => Auth()->user()->name,
            'amount' => $request->cashreceived,
            'branchs_id' => $data->branch->name,
            'note' => $request->notes ?? "-",
            'created_at' => $request->start_at,
            'id' => $data->id
        ];
        return $data;
        return view('acountes.convertcashboxToBank', compact('data'));
    }
    public function printconvertcashboxToBank(Request $request)
    {
        if ($request == null) {
            $data = [];
            session()->flash('nodataprint', '');
            return view('acountes.convertcashboxToBank', compact('data'));
        }
        //eturn $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = convertcashboxToBank::find($request->id);
        //  return $data;
        return view('acountes.printconvertcashboxToBank', compact('data'));
    }
    public function cashEcprnse()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $allcustomers = User::get();
        $data = [
            'transaction' => [],
            "allusers" =>  $allcustomers,
        ];
        return view('acountes.cash expense', compact('data'));
    }

    public function income()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $allcustomers = customers::get();
        $data = [
            "transaction" =>  [],
        ];
        return view('acountes.Expensesowner', compact('data'));
    }

    public function reciept_decoument()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $allcustomers = supllier::get();
        $data = [
            "transaction" => [],

            "allsupllier" =>  $allcustomers,
        ];
        return view('acountes.reciept_decoment', compact('data'));
    }






    public  function createTransfertomainbranch($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $transactiontosupllier = transactiontosuplliers::find($id);

        $supllierdata = supllier::find($transactiontosupllier->suplier_id);
        $data = [
            "transaction" => [
                'id' => $transactiontosupllier->id,
                'name' => $supllierdata->name,
                'Limit_credit' => $supllierdata->Limit_credit,
                'Balance' => $supllierdata->In_debt,
                'camp_name' => $supllierdata->comp_name,
                'camp_phone' => $supllierdata->phone,
                'date' => $transactiontosupllier->created_at,
                'method_pay' => $transactiontosupllier->Pay_Method_Name,
                'paid_amount' => $transactiontosupllier->paidـamount
            ],
        ];
        return view('acountes.print_voucher_to_supplier', compact('data'));
        # code...
    }






    public  function print_voucher(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $transactiontosupllier = transactiontosuplliers::find($request->id);

        $supllierdata = supllier::find($transactiontosupllier->suplier_id);
        //return  $supllierdata;
        $data = [
            "transaction" => [
                'id' => $transactiontosupllier->id,
                'name' => $supllierdata->name,
                'Limit_credit' => $supllierdata->Limit_credit,
                'Balance' => $supllierdata->In_debt,
                'camp_name' => $supllierdata->comp_name,
                'camp_phone' => $supllierdata->phone,
                'date' => $transactiontosupllier->created_at,
                'method_pay' => $transactiontosupllier->Pay_Method_Name,
                'paid_amount' => $transactiontosupllier->paidـamount
            ],
        ];
        return view('acountes.print_voucher_to_supplier', compact('data'));
        # code...
    }
    public  function print_expansedecoument(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $transactiontocustomer;
        $expense = expenses::find($request->id);
        if ($expense->Pay_Method_Name == 'Cash') {
            $pay = __('report.cash');
        } elseif ($expense->Pay_Method_Name == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        //return  $expense;
        $data = [
            'id' =>  $expense->id,
            'user' => Auth()->user()->name,
            'Pay_Method_Name' => $pay,
            'Theـamountـpaid' => $expense->Theـamountـpaid,
            'Reasonforspendingmoney' => $expense->Expenses_reasons->expenses_reason,


        ];
        //return $data;
        return view('acountes.print_cashexpenswe', compact('data'));
        # code...

    }


    public function print_reciept_ducoument(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $transactiontocustomer = credittransactions::find($request->id);
        //return $transactiontocustomer;
        $customerdata = customers::find($transactiontocustomer->customer_id);
        $data = [
            "transaction" => [
                "id" => $transactiontocustomer->id,
                'name' => $customerdata->name,
                'Limit_credit' => $customerdata->Limit_credit,
                'Balance' => $customerdata->Balance,
                'date' => $transactiontocustomer->created_at,
                'method_pay' => $transactiontocustomer->Pay_Method_Name,
                'paid_amount' => $transactiontocustomer->recive_amount
            ],
        ];
        return view('acountes.print_reciept_decoment_to_customer', compact('data'));
        # code...
    }
}
