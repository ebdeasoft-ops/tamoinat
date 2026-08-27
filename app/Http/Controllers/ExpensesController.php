<?php

namespace App\Http\Controllers;
use App\Models\Expenses_reasons;

use App\Models\expenses;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class ExpensesController extends Controller
{

    function __construct() {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
      }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateExpenses(Request $request)
    {
        //
      //  return $request;
      $expense=expenses::find($request->transactionId);
        $reason_data=Expenses_reasons::find($request->reasoneupdate);

     $expense=  expenses::find($request->transactionId)->update([

            'Pay_Method_Name'=>$request->payupdate,
            'Reasonforspendingmoney'=>$reason_data->expenses_reason,
            'reasonId_id'=>$request->reasoneupdate ,
            'expensesAvt'=>$reason_data->expensesAvt ,
            'updated_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'Theـamountـpaid'=>$request->cashreceivedupdate
        ]);
        $expense=expenses::find($request->transactionId);

        if ($request->payupdate == 'Cash') {
            $pay = __('report.cash');
        } if ($request->payupdate == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data=[
            'id'=>$expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$request->cashreceivedupdate,
                'expense'=>$expense->Reasonforspendingmoney,

       
        ];
        return $data;
        return view('acountes.cash expense',compact('data'));
    }



    public function store(Request $request)
    {
        //
      //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'cashreceived' => 'required|numeric',
           
        ]);
        $reason_data=Expenses_reasons::find($request->reasone);

     $expense=  expenses::create([

            'user_id'=>Auth()->user()->id,
            'Pay_Method_Name'=>$request->pay,
            'branchs_id'=>Auth()->user()->branchs_id,
            'Reasonforspendingmoney'=>$reason_data->expenses_reason,
            'reasonId_id'=>$request->reasone ,
            'expensesAvt'=>$reason_data->expensesAvt ,
            'created_at'  =>  $request->date, 
            'updated_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'Theـamountـpaid'=>$request->cashreceived
        ]);
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        } if ($request->pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data=[
            'id'=>$expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$request->cashreceived,
                'expense'=>$expense->Reasonforspendingmoney,

       
        ];
        return $data;
        return view('acountes.cash expense',compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function ExpensesOwner(Request $request)
    {
        //
      //  return $request;

      //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'cashreceived' => 'required|numeric',
           
        ]);
        $expense=expenses::create([
            'user_id'=>Auth()->user()->id,
            'Pay_Method_Name'=>$request->pay,
            'Reasonforspendingmoney'=>$request->reasone,
            'reasonId_id'=>$request->reasonId_id,
            'created_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'updated_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'Theـamountـpaid'=>$request->cashreceived
        ]);
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        } else {
            $pay = __('report.shabka');
        }
        $data=[
                'id'=>  $expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$request->cashreceived,
                'Reasonforspendingmoney'=>$request->reasone,

         
        ];
        return $data;
        return view('acountes.Expensesowner',compact('data'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit(expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, expenses $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(expenses $expenses)
    {
        //
    }
}
