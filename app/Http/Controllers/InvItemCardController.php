<?php
//لاتنسونا من صالح الدعاء
//أخي الكريم هذا الكود هو اول 130 ساعة بالكورس الي نهاية الدورة الفيدو رقم  231- اما باقي أكواد الدورة الثانية للتطوير النظام موجوده بالدورة ولابد ان تكتبها بنفسك لأهميتها وللإستفادة

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Inv_itemCard;
use App\Models\User;
use App\Models\products;
use App\Models\inv_itemcard_categorie;
use App\Models\units;
use App\Http\Requests\ItemcardRequest;
use App\Http\Requests\ItemcardRequestUpdate;
use App\Models\inv_itemcard_categories;
use App\Models\Sales_invoices;
use App\Models\Sales_invoices_details;
use App\Models\Suppliers_with_orders_details;
use App\Models\Inv_itemcard_movements_categories;
use App\Models\Inv_itemcard_movements_types;
use App\Models\Store;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;



class InvItemCardController extends Controller
{
public function index()
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

$com_code = auth()->user()->branchs_id;
$data = $this->get_cols_where_p(new products(), array("*"), array('branchs_id' => $com_code), 'id', 'DESC', PAGINATION_COUNT);
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
return view('inv_itemCard.index', ['data' => $data, 'inv_itemcard_categories' => $inv_itemcard_categories]);
}


public function generate_barcode($id)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

$data = $this->get_cols_where_row(new products(), array("barcode","name"), array("id" => $id));
if (empty($data)) {
    return redirect()->route('admin.itemcard.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
    }
    return view("inv_itemCard.generate_barcode",['data'=>$data]);

}

public function search(Request $request)
{
    return products::where('name', 'like', '%' . $request->q . '%')
        ->limit(20)
        ->get(['id', 'name']);
}



public function create()
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

$com_code = auth()->user()->branchs_id;




$inv_itemcard_categories = $this->get_cols_where(new inv_itemcard_categories(), array('id', 'name'), array('branchs_id' => 1), 'id', 'DESC');
$inv_uoms_parent = $this->get_cols_where(new units(), array('id', 'name'), array('branchs_id' => 1, 'active' => 1, 'is_master' => 1), 'id', 'DESC');
$inv_uoms_child = $this->get_cols_where(new units(), array('id', 'name'), array('branchs_id' => 1,  'is_master' => 0), 'id', 'DESC');
$item_card_data = [];

return view('inv_itemCard.create', ['inv_itemcard_categories' => $inv_itemcard_categories, 'inv_uoms_parent' => $inv_uoms_parent, 'inv_uoms_child' => $inv_uoms_child, 'item_card_data' => $item_card_data]);
}




public function store(Request $request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());
    
try {
$com_code = auth()->user()->branchs_id;
//set item code for itemcard
$row = products::where('branchs_id', Auth()->user()->branchs_id)->orderBy('id', 'desc')->first();
if (!empty($row)) {
$data_insert['item_code'] = $row['item_code'] + 1;
} else {
$data_insert['item_code'] = 1;
}
if ($request['uom_id']=='0'|| $request['inv_itemcard_categories_id']=='0') {
    return redirect()->back()
    ->with(['error' => 'عفوا باركود الصنف مسجل من قبل'])
    ->withInput();
    }
//check if not exsits for barcode
if ($request->barcode != '') {
$checkExists_barcode = products::where('branchs_id', Auth()->user()->branchs_id)->where(['barcode' => $request->barcode, 'branchs_id' => $com_code])->first();
if (!empty($checkExists_barcode)) {
return redirect()->back()
->with(['error' => 'عفوا باركود الصنف مسجل من قبل'])
->withInput();
} else {
$data_insert['barcode'] = $request->barcode;
}
} else {
$data_insert['barcode'] = "item" . $data_insert['item_code'];
}
//check if not exsits for name
$checkExists_barcode = products::where('branchs_id', Auth()->user()->branchs_id)->where(['name' => $request->name, 'branchs_id' => $com_code])->first();
if (!empty($checkExists_barcode)) {
return redirect()->back()
->with(['error' => 'عفوا اسم الصنف مسجل من قبل'])
->withInput();
}

$data_insert['name'] = $request->name;
$data_insert['item_type'] = $request->item_type;
$data_insert['inv_itemcard_categories_id'] = $request->inv_itemcard_categories_id;
$data_insert['uom_id'] = $request->uom_id;
$data_insert['price'] = $request->price??0;
// $data_insert['nos_gomla_price'] = $request->nos_gomla_price;
// $data_insert['gomla_price'] = $request->gomla_price;
$data_insert['cost_price'] = $request->cost_price??0;
$data_insert['does_has_retailunit'] = $request->does_has_retailunit;
$data_insert['parent_inv_itemcard_id'] = $request->parent_inv_itemcard_id;
if ($data_insert['parent_inv_itemcard_id'] == "") {
$data_insert['parent_inv_itemcard_id'] = 0;
}
if ($data_insert['does_has_retailunit'] == 1) {
$data_insert['retail_uom_quntToParent'] = $request->retail_uom_quntToParent;
$data_insert['retail_uom_id'] = $request->retail_uom_id;
$data_insert['price_retail'] = $request->price_retail??0;
// $data_insert['nos_gomla_price_retail'] = $request->nos_gomla_price_retail;
// $data_insert['gomla_price_retail'] = $request->gomla_price_retail;
$data_insert['cost_price_retail'] = $request->cost_price_retail??0;
}
$data_insert['photo'] ='productunKnown.png';

if ($request->has('Item_img')) {
$request->validate([
'Item_img' => 'required|mimes:png,jpg,jpeg|max:2000',
]);
$the_file_path = uploadImage('assets//admin//uploads', $request->Item_img);
$data_insert['photo'] =$the_file_path;
}

$data_insert['has_fixced_price'] = $request->has_fixced_price;
$data_insert['active'] = $request->active;
$data_insert['added_by'] = auth()->user()->id;
$data_insert['updated_by'] = auth()->user()->id;
$data_insert['created_at'] = date("Y-m-d H:i:s");
$data_insert['date'] = date("Y-m-d");
$data_insert['date'] = date("Y-m-d");
$data_insert['branchs_id'] = $com_code;
products::create($data_insert);
return redirect()->route('admin.itemcard.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
} catch (\Exception $ex) {
return redirect()->back()
->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
->withInput();
}
}


public function show($id)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

$data = get_cols_where_row(new products(), array("*"), array("id" => $id));
$com_code = auth()->user()->branchs_id;
$data['added_by_admin'] = get_field_value(new User(), 'name', array('id' => $data['added_by']));
$data['inv_itemcard_categories_name'] = get_field_value(new inv_itemcard_categories(), 'name', array('id' => $data['inv_itemcard_categories_id']));
$data['parent_item_name'] = get_field_value(new products(), 'name', array('id' => $data['parent_inv_itemcard_id']));
$data['Uom_name'] = get_field_value(new units(), 'name', array('id' => $data['uom_id']));
if ($data['does_has_retailunit'] == 1) {
$data['retail_uom_name'] = get_field_value(new units(), 'name', array('id' => $data['retail_uom_id']));
}
if ($data['updated_by'] > 0 and $data['updated_by']  != null) {
$data['updated_by_admin'] = get_field_value(new User(), 'name', array('id' => $data['updated_by']));
}

return view('inv_itemCard.show', ['data' => $data]);
}


public function update($id, ItemcardRequestUpdate $request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());
try {
$com_code = auth()->user()->branchs_id;
$data = get_cols_where_row(new products(), array("*"), array("id" => $id));
if (empty($data)) {
return redirect()->route('admin.itemcard.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
}
if ($request->has('item_type')) {
if ($request->item_type == "") {
return redirect()->back()
->with(['error' => 'من فضلك اختر نوع الصنف'])
->withInput();
}
if ($request->item_type == "") {
return redirect()->back()
->with(['error' => 'من فضلك اختر نوع الصنف'])
->withInput();
}
if ($request->uom_id == "") {
return redirect()->back()
->with(['error' => 'من فضلك اختر  وحدة القياس الاب'])
->withInput();
}
if ($request->does_has_retailunit == "") {
return redirect()->back()
->with(['error' => 'من فضلك اختر  هل للصنف وحدة تجزئة'])
->withInput();
}
if ($request->does_has_retailunit == 1) {
if ($request->retail_uom_id == "") {
return redirect()->back()
->with(['error' => 'من فضلك اختر  وحدة القياس التجزئة'])
->withInput();
}
if ($request->retail_uom_quntToParent == "" || $request->retail_uom_quntToParent == 0) {
return redirect()->back()
->with(['error' => 'من فضلك ادخل النسبة مابين وحدة قياس الاب  والابن'])
->withInput();
}
}
}

//check if not exsits for barcode
if ($request->barcode != '') {
$checkExists_barcode = products::where(['barcode' => $request->barcode, 'branchs_id' => $com_code])->where("id", "!=", $id)->first();
if (!empty($checkExists_barcode)) {
return redirect()->back()
->with(['error' => 'عفوا باركود الصنف مسجل من قبل'])
->withInput();
} else {
$data_insert['barcode'] = $request->barcode;
}
}
//check if not exsits for name
$checkExists_barcode = products::where(['name' => $request->name, 'branchs_id' => $com_code])->where("id", "!=", $id)->first();
if (!empty($checkExists_barcode)) {
return redirect()->back()
->with(['error' => 'عفوا اسم الصنف مسجل من قبل'])
->withInput();
}

$productdata=products::find($id);
    $productdata=products::where('id',$id)->update(
        [
            'price_with_tax'=>$request->priceWithTax,
            'price'=>$request->price,
            'cost_price' => $request->cost_price_retail??$productdata->cost_price_retail,
            'All_QUENTITY'=>round($request->reamingquantity* $request->retail_uom_quntToParent)
        ]
    );



$data_to_update['name'] = $request->name;
$data_to_update['All_QUENTITY'] = $request->reamingquantity;
$data_to_update['inv_itemcard_categories_id'] = $request->inv_itemcard_categories_id;
$data_to_update['price'] = $request->price??$productdata->price;
// $data_to_update['nos_gomla_price'] = $request->nos_gomla_price;
// $data_to_update['gomla_price'] = $request->gomla_price;
$data_to_update['cost_price'] = $request->cost_price??$productdata->cost_price;
$data_to_update['parent_inv_itemcard_id'] = $request->parent_inv_itemcard_id;
if ($data_to_update['parent_inv_itemcard_id'] == "") {
$data_to_update['parent_inv_itemcard_id'] = 0;
}
if ($request->has('item_type')) {
$data_to_update['item_type'] = $request->item_type;
$data_to_update['uom_id'] = $request->uom_id;
$data_to_update['does_has_retailunit'] = $request->does_has_retailunit;
if ($data_to_update['does_has_retailunit'] == 1) {
$data_to_update['retail_uom_quntToParent'] = $request->retail_uom_quntToParent;
$data_to_update['retail_uom_id'] = $request->retail_uom_id;
}
} else {
$data_to_update['does_has_retailunit'] = $data['does_has_retailunit'];
}
if ($data_to_update['does_has_retailunit'] == 1) {
$data_to_update['price_retail'] = $request->price_retail??$productdata->price_retail;

$data_to_update['cost_price_retail'] = $request->cost_price_retail??$productdata->cost_price_retail;
}
if ($request->has('Item_img')) {
$request->validate([
'Item_img' => 'required|mimes:png,jpg,jpeg|max:2000',
]);
$oldphotoPath = $data['photo'];
$the_file_path = uploadImage('assets//admin//uploads', $request->Item_img);
if (file_exists('assets/admin/uploads/' . $oldphotoPath) and !empty($oldphotoPath)  and $oldphotoPath !='productunKnown.png') {
unlink('assets/admin/uploads/' . $oldphotoPath);
}
// return $the_file_path;
$data_to_update['photo'] = $the_file_path;
}
$data_to_update['barcode'] = $request->barcode;
$data_to_update['has_fixced_price'] = $request->has_fixced_price;
$data_to_update['active'] = $request->active;
$data_to_update['updated_by'] = auth()->user()->id;
$data_to_update['updated_at'] = date("Y-m-d H:i:s");
update(new products(), $data_to_update, array('id' => $id, 'branchs_id' => $com_code));
return redirect()->route('admin.itemcard.index')->with(['success' => 'لقد تم تحديث البيانات بنجاح']);
} catch (\Exception $ex) {
return redirect()->back()
->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
->withInput();
}
}


public function ajax_search(Request $request)
{
if ($request->ajax()) {
$search_by_text = $request->search_by_text;
$item_type = $request->item_type;
$inv_itemcard_categories_id = $request->inv_itemcard_categories_id;
$searchbyradio = $request->searchbyradio;
if ($item_type == 'all') {
$field1 = "id";
$operator1 = ">";
$value1 = 0;
} else {
$field1 = "item_type";
$operator1 = "=";
$value1 = $item_type;
}
if ($inv_itemcard_categories_id == 'all') {
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "inv_itemcard_categories_id";
$operator2 = "=";
$value2 = $inv_itemcard_categories_id;
}
if ($search_by_text != '') {
if ($searchbyradio == 'barcode') {
$field3 = "barcode";
$operator3 = "=";
$value3 = $search_by_text;
} elseif ($searchbyradio == 'item_code') {
$field3 = "item_code";
$operator3 = "=";
$value3 = $search_by_text;
} else {
$field3 = "name";
$operator3 = "like";
$value3 = "%{$search_by_text}%";
}
} else {
//true 
$field3 = "id";
$operator3 = ">";
$value3 = 0;
}
$data = products::where($field1, $operator1, $value1)->where('branchs_id',Auth()->user()->branchs_id)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
if (!empty($data)) {
foreach ($data as $info) {
$info->added_by_admin = get_field_value(new User(), 'name', array('id' => $info->added_by));
$info->inv_itemcard_categories_name = get_field_value(new inv_itemcard_categories(), 'name', array('id' => $info->inv_itemcard_categories_id));
$info->parent_item_name = get_field_value(new products(), 'name', array('id' => $info->parent_inv_itemcard_id));
$info->Uom_name = get_field_value(new units(), 'name', array('id' => $info->uom_id));
$info->retail_uom_name = get_field_value(new units(), 'name', array('id' => $info->retail_uom_id));
if ($info->updated_by > 0 and $info->updated_by != null) {
$info->updated_by_admin = get_field_value(new User(), 'name', array('id' => $info->updated_by));
}
}
}
return view('inv_itemCard.ajax_search', ['data' => $data]);
}
}


public function ajax_check_barcode (Request $request)
{
if ($request->ajax()) {
$barcode = $request->barcode;
$com_code=auth()->user()->branchs_id;
$checkExists_barcode = products::where(['barcode' => $request->barcode, 'branchs_id' => $com_code])->first();
if (!empty($checkExists_barcode)) {
return json_encode('not_allowed');
}else{
    return json_encode('allowed');
}

}
}


public function ajax_check_name (Request $request)
{
if ($request->ajax()) {
$name = $request->name;
$com_code=auth()->user()->branchs_id;
$checkExists_name = products::where(['name' => $request->name, 'branchs_id' => $com_code])->first();
if (!empty($checkExists_name)) {
return json_encode('not_allowed');
}else{
    return json_encode('allowed');
}

}
}






public function edit($id)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

$data = get_cols_where_row(new products(), array("*"), array("id" => $id));
$com_code = auth()->user()->branchs_id;
$inv_itemcard_categories = get_cols_where(new inv_itemcard_categories(), array('id', 'name'), array( 'active' => 1), 'id', 'DESC');
$inv_uoms_parent = get_cols_where(new units(), array('id', 'name'), array( 'active' => 1, 'is_master' => 1), 'id', 'DESC');
$inv_uoms_child = get_cols_where(new units(), array('id', 'name'), array('active' => 1, 'is_master' => 0), 'id', 'DESC');
 $item_card_data = [];
// $counterUsedin_with_suppliers = get_count_where(new Suppliers_with_orders_details(), array("com_code" => $com_code, "item_code" => $data['item_code']));
// $counterUsedin_with_sales = get_count_where(new Sales_invoices_details(), array("com_code" => $com_code, "item_code" => $data['item_code']));
// $counterUsedBefore = $counterUsedin_with_suppliers + $counterUsedin_with_sales;

return view('inv_itemCard.edit', ['data' => $data, 'inv_itemcard_categories' => $inv_itemcard_categories, 'inv_uoms_parent' => $inv_uoms_parent, 'inv_uoms_child' => $inv_uoms_child, 'item_card_data' => $item_card_data]);
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