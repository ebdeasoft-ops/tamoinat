<?php

namespace App\Http\Controllers;

use App\Models\units;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InvUomRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class UnitsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        $data = units::where('branchs_id', auth()->user()->branchs_id)->paginate(20);
        return view('products.units', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('products.createunit');
    }
    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $search_by_text = $request->search_by_text;
            $is_master_search = $request->is_master_search;
            if ($search_by_text == '') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "name";
                $operator1 = "LIKE";
                $value1 = "%{$search_by_text}%";
            }
            if ($is_master_search == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "is_master";
                $operator2 = "=";
                $value2 = $is_master_search;
            }
            $data = units::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->orderBy('id', 'DESC')->paginate(20);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = $info->user->name;
                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = $info->user->name;
                    }
                }
            }
            return view('products.ajax_search', ['data' => $data]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvUomRequest  $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        try {
            $com_code = auth()->user()->branchs_id;
            //check if not exsits
            $checkExists = units::where(['name' => $request->name, 'branchs_id' => $com_code])->first();
            // return $checkExists;
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['is_master'] = $request->is_master;
                $data['active'] = $request->active;
                $data['created_at'] = \Carbon\Carbon::now()->addHours(3);
                $data['added_by'] = auth()->user()->id;
                $data['branchs_id'] = $com_code;
                units::create($data);
                $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'لقد تم اضافة البيانات بنجاح' : 'Data has been added successfully';

                return redirect()->route('units')->with(['success' => $message]);
            } else {
                $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عفوا اسم الوحدة مسجل من قبل' : 'Sorry, the unit name is already registered';

                return redirect()->back()

                    ->with(['error' => $message])
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عفوا حدث خطأ ما' : 'Sorry, something went wrong';

            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }
    public function delete($id)
    {
       // return $id;
        try {
            $item_row = units::find($id);
            if (!empty($item_row)) {
                $flag = $item_row->delete();
                if ($flag) {
                    $message = LaravelLocalization::getCurrentLocale() == 'ar' ? '   تم حذف البيانات بنجاح' : 'Data has been deleted successfully';

                    return redirect()->back()
                        ->with(['success' => $message]);
                } else {
                    $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عفوا حدث خطأ ما' : 'Sorry, something went wrong';

                    return redirect()->back()
                        ->with(['error' => $message]);
                }
            } else {
                $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عفوا غير قادر الي الوصول للبيانات المطلوبة' : 'Sorry, unable to access the requested data';

                return redirect()->back()
                    ->with(['error' => $message]);
            }
        } catch (\Exception $ex) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عفوا حدث خطأ ما' : 'Sorry, something went wrong';

            return redirect()->back()
                ->with(['error' => $message . $ex->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\units  $units
     * @return \Illuminate\Http\Response
     */
    public function show(units $units)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\units  $units
     * @return \Illuminate\Http\Response
     */
    public function edit($id,InvUomRequest $request)
    {
        //
        try {
            $com_code = auth()->user()->branchs_id;
            $data = units::select()->find($id);

            if (empty($data)) {
            return redirect()->route('admin.uoms.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            $checkExists = units::where(['name' => $request->name, 'branchs_id' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists != null) {
                $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عفوا اسم الوحدة مسجل من قبل' : 'Sorry, the unit name is already registered';

            return redirect()->back()
            ->with(['error' => $message])
            ->withInput();
            }
          
            $data_to_update['is_master'] = $request->is_master;
            $data_to_update['name'] = $request->name;
            $data_to_update['active'] = $request->active;
            $data_to_update['added_by'] = auth()->user()->id;
            $data_to_update['updated_at'] =\Carbon\Carbon::now()->addHours(3);

            units::where(['id' => $id,'branchs_id' => $com_code])->update($data_to_update);
            

            return redirect()->route('unit.go.update')->with(['success' => 'لقد تم تحديث البيانات بنجاح']);
            } catch (\Exception $ex) {
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
            ->withInput();
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\units  $units
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
        $com_code = auth()->user()->branchs_id;
        $data = units::select()->find($id);
        
        //check if this uom used befor  نتحقق من الوحده هل تم استخدامها بالفعل ام ليس بعد
        //check in suppliers_with_orders_details نتحقق من المشتريات
        // $suppliers_with_orders_detailsCount=get_count_where(new Suppliers_with_orders_details(),array('com_code'=>$com_code,'uom_id'=>$data['id']));
        // //check in Sales_invoices_details نتحقق من المبيعات
        // $sales_invoices_detailsCount=get_count_where(new Sales_invoices_details(),array('com_code'=>$com_code,'uom_id'=>$data['id']));
        //$total_counter_used=$suppliers_with_orders_detailsCount+$sales_invoices_detailsCount;
        return view('products.updateunit', ['data' => $data,'total_counter_used'=>1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\units  $units
     * @return \Illuminate\Http\Response
     */
    public function destroy(units $units)
    {
        //
    }
}
