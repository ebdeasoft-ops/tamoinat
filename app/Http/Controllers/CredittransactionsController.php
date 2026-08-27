<?php

namespace App\Http\Controllers;

use App\Models\credittransactions;
use App\Models\transactiontosuplliers;

use App\Models\customers;
use App\Models\supllier;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;


class CredittransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateVoncher(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
     
        // return $request;
       $transaction= credittransactions::find($request->transactionId);        //    return $clientId;
        $customerdata = customers::find($transaction->customer_id);

        $updateCustomer = customers::where('id', $transaction->customer_id)->update(
            [
                'Balance' => $customerdata->Balance+ $transaction->recive_amount - $request->cashreceivedupdate
            ]
        );
        $customerdata = customers::find($transaction->customer_id);

        $createTransaction =   credittransactions::find($request->transactionId)->update(
            [
                'recive_amount' => $request->cashreceivedupdate,
                'pay_method' => $request->payupdate,
                'currentblance' =>  $customerdata->Balance,
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),
            ]
        );
        if ($request->payupdate == "Cash") {
            $pay = __('report.cash');
        }elseif ($request->payupdate == "Bank_transfer") {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }

        $data = [

            'id' => $request->transactionId,
            'name' => $customerdata->name,
            'Limit_credit' => $customerdata->Limit_credit,
            'Balance' => $customerdata->Balance,
            'method_pay' => $pay,
            'recive_amount' => $request->cashreceivedupdate

        ];
        // return $data['transaction']['name'];
        return $data;
    }
    public function create(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        $this->validate($request, [
            'cashreceived' => 'required|numeric  ',

        ]);
        // return $request;
        $clientId = $request->clientnamesearch;
        //    return $clientId;
        $customerdata = customers::find($clientId);

        $updateCustomer = customers::where('id', $request->clientnamesearch)->update(
            [
                'Balance' => $customerdata->Balance - $request->cashreceived,
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),

            ]
        );
        $allcustomers = customers::get();
        $customerdata = customers::find($clientId);
        $createTransaction =   credittransactions::create(
            [
                'user_id' => Auth()->user()->id,
                'customer_id' =>  $clientId,
                'recive_amount' => $request->cashreceived,
                'branchs_id' => Auth()->user()->branchs_id,
                'pay_method' => $request->pay,
                'currentblance' =>  $customerdata->Balance,
                'note' =>  $request->notes,
                'Pay_Method_Name' => $request->pay,
                'created_at'  =>  \Carbon\Carbon::now()->addHours(3),
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),
            ]
        );
        if ($request->pay == "Cash") {
            $pay = __('report.cash');
        }elseif ($request->pay == "Bank_transfer") {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }

        $data = [

            'id' => $createTransaction->id,
            'name' => $customerdata->name,
            'Limit_credit' => $customerdata->Limit_credit,
            'Balance' => $customerdata->Balance,
            'method_pay' => $pay,
            'recive_amount' => $request->cashreceived

        ];
        // return $data['transaction']['name'];
        return $data;
    }

    // return view('acountes.voncher',compact('data'));    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    //return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'cashreceived' => 'required|numeric',

        ]);
        $suplliertId = $request->clientnamesearch;
        $supllierIn_debt =  supllier::find($suplliertId);
        supllier::where('id', $suplliertId)->update(
            [
                'In_debt' => $supllierIn_debt->In_debt - $request->cashreceived,
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),

            ]
        );
        $supllierIn_debt =  supllier::find($suplliertId);

        $transactiontosupllier = transactiontosuplliers::create(
            [
                'user_id' => Auth()->user()->id,
                'branchs_id' => Auth()->user()->branchs_id,
                'suplier_id' => $suplliertId,
                'paidـamount' => $request->cashreceived,
                'notes' => $request->notes??'-',
                'currentblance'=>$supllierIn_debt->In_debt,

                'Pay_Method_Name' => $request->pay,
                'created_at'  =>  \Carbon\Carbon::now()->addHours(3),
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),
            ]
        );
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        }else if ($request->pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data = [
            
                'id' =>  $transactiontosupllier->id,
                'name' => $supllierIn_debt->name,
                'Balance' => $supllierIn_debt->In_debt,
                'method_pay' => $pay,
                'paid_amount' => $request->cashreceived
        

        ];
         return $data;
      //  return view('acountes.reciept_decoment', compact('data'));
    }



    public function updaterecieptdecoument(Request $request)
    {
        //
        // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $transaction = transactiontosuplliers::find($request->transactionId);
        $suplliertId = $transaction->suplier_id;
        $supllierIn_debt =  supllier::find($suplliertId);
        supllier::where('id', $suplliertId)->update(
            [
                'In_debt' => $supllierIn_debt->In_debt+   $transaction->paidـamount- $request->cashreceivedupdate,
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),

            ]
        );
        $supllierIn_debt =  supllier::find($suplliertId);

        $transactiontosupllier = transactiontosuplliers::find($request->transactionId)->update(
            [
                'paidـamount' => $request->cashreceivedupdate,
                'Pay_Method_Name' => $request->payupdate,
                'currentblance'=>$supllierIn_debt->In_debt,
                'updated_at'  =>  \Carbon\Carbon::now()->addHours(3),
            ]
        );
    
        $allsuplliers = supllier::get();
        $supllierIn_debt =  supllier::find($suplliertId);
        if ($request->payupdate == 'Cash') {
            $pay = __('report.cash');
        }else if ($request->payupdate == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data = [
            
                'id' =>  $request->transactionId,
                'name' => $supllierIn_debt->name,
                'Balance' => $supllierIn_debt->In_debt,
                'method_pay' => $pay,
                'paid_amount' => $request->cashreceivedupdate
        

        ];
         return $data;
      //  return view('acountes.reciept_decoment', compact('data'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function show(credittransactions $credittransactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function edit(credittransactions $credittransactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, credittransactions $credittransactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(credittransactions $credittransactions)
    {
        //
    }
}
