<?php

namespace App\Http\Controllers;

use App\Models\branchs;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class BranchsController extends Controller
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

        return view('users.Add_branch');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
      //  return $request;
      app()->setLocale(LaravelLocalization::getCurrentLocale());

$createBranch=branchs::create([
'name'=> $request-> breanchName ,
'place'=>$request->branchLoction   ,
'created_at'=> \Carbon\Carbon::now()->addHours(3)
]);
if($createBranch!=null){
    session()->flash('create','تم انشاء الفرع  بنجاج');
    return view('users.Add_branch');


}
else{
    session()->flash('notcreate','حدث مشكلة اثناء انشاء الفرع');
    return view('users.Add_branch');
}
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
     * @param  \App\Models\branchs  $branchs
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $branches=branchs::get();
        return view('Branches',compact('branches'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\branchs  $branchs
     * @return \Illuminate\Http\Response
     */
    public function updatebranch(Request $request)
    {
        //
       // return $request;
       app()->setLocale(LaravelLocalization::getCurrentLocale());

        branchs::where('id',$request->id)->update([
            'name'=>$request->breanchName,
            'place'=>$request->branchLoction
        ]);
        $branches=branchs::get();
        return view('Branches',compact('branches'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\branchs  $branchs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, branchs $branchs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\branchs  $branchs
     * @return \Illuminate\Http\Response
     */
    public function destroy(branchs $branchs)
    {
        //
    }
}
