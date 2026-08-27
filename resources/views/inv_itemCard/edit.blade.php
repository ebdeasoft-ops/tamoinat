@extends('layouts.master')
@section('css')

<!-- Internal Data table css -->

<!--Internal  Datatable js -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

{{-- <style>
        tr:nth-child(even) {
            background-color: #dde2ef !important;
            
            color: white;
        }
    </style> --}}


@section('title')
{{__('home.update product')}}@stop

@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title card_title_center">{{__('home.update product')}}</h3>
   </div>
   <!-- /.card-header -->
   <?php
   $avtSaleRate = App\Models\Avt::find(1);
   $avtSaleRate = $avtSaleRate->AVT;
   ?>
   <input type="text" class="form-control " id="avtValue" name="avtValue" value="{{$avtSaleRate}}" hidden>

   <div class="card-body">
      <input type="hidden" id="ajax_url_ajax_check_barcode" value="{{ route('admin.itemcard.ajax_check_barcode') }}">
      <input type="hidden" id="ajax_url_ajax_check_name" value="{{ route('admin.itemcard.ajax_check_name') }}">
      <input type="hidden" id="token_search" value="{{csrf_token() }}">
      <form action="{{ route('admin.itemcard.update',$data['id']) }}" method="post" enctype="multipart/form-data">
         <div class="row">
            @csrf
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.barcode')}} <span id="barcodeCheckMessage"> </span></label>
                  <input name="barcode" id="barcode" class="form-control" value="{{ old('name',$data['barcode']) }}" placeholder="ادخل  باركود الصنف">
                  @error('barcode')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.product')}} <span id="nameCheckMessage"> </span></label>
                  <input name="name" id="name" class="form-control" value="{{ old('name',$data['name']) }}" placeholder="ادخل اسم الصنف">
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.typeitem')}}</label>
                  <select name="item_type" id="item_type" class="form-control">
                     <option value=""> {{__('home.chooseOne')}}</option>
                     <option @if(old('item_type',$data['item_type'])==1) selected="selected" @endif value="1"> {{__('home.without_validity')}}</option>
                     <option @if(old('item_type',$data['item_type'])==2) selected="selected" @endif value="2"> {{__('home.validity')}} </option>
                  </select>
                  @error('item_type')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.itemclass')}}</label>
                  <select name="inv_itemcard_categories_id" id="inv_itemcard_categories_id" class="form-control select2 ">
                     <option value=""> {{__('home.chooseOne')}}</option>
                     @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                     @foreach ($inv_itemcard_categories as $info )
                     <option {{  old('inv_itemcard_categories_id',$data['inv_itemcard_categories_id'])==$info->id ? 'selected' : ''}} value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
                  @error('inv_itemcard_categories_id')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.parentproduct')}}</label>
                  <select name="parent_inv_itemcard_id" id="parent_inv_itemcard_id" class="form-control select2 ">
                     <option selected value="0"> {{__('home.isparent')}}</option>
                   
                  </select>
                  @error('inv_itemcard_categories_id')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.parentunit')}}</label>
                  <select name="uom_id" id="uom_id" class="form-control ">
                     <option value=""> {{__('home.chooseOne')}}</option>

                     @if (@isset($inv_uoms_parent) && !@empty($inv_uoms_parent))
                     @foreach ($inv_uoms_parent as $info )
                     <option {{  old('uom_id',$data['uom_id'])==$info->id ? 'selected' : ''}} value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
                  @error('uom_id')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.haspartialunit')}} </label>
                  <select name="does_has_retailunit" id="does_has_retailunit" class="form-control">
                     <option value=""> {{__('home.chooseOne')}}</option>

                     <option {{  old('does_has_retailunit',$data['does_has_retailunit'])==1 ? 'selected' : ''}} value="1"> {{__('home.yes')}} </option>
                     <option {{  old('does_has_retailunit',$data['does_has_retailunit'])==0 ? 'selected' : ''}} value="0"> {{__('home.No')}}</option>
                  </select>
                  @error('does_has_retailunit')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6  " @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif id="retail_uom_idDiv">
               <div class="form-group">
                  <label> {{__('home.Retail unit of measure')}} (<span class="parentuomname"></span>)</label>
                  <select name="retail_uom_id" id="retail_uom_id" class="form-control ">
                     <option value=""> {{__('home.chooseOne')}}</option>
                     @if (@isset($inv_uoms_child) && !@empty($inv_uoms_child))
                     @foreach ($inv_uoms_child as $info )
                     <option {{  old('retail_uom_id',$data['retail_uom_id'])==$info->id ? 'selected' : ''}} value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
                  @error('retail_uom_id')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6 relatied_retial_counter " @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
               <div class="form-group">
                  <label><span style="color:red"> {{__('home.The number of retail units')}}</span> <span class="childuomname"></span><span style="color:red"> {{__('home.For the main')}}</span><span class="parentuomname"></span> </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="retail_uom_quntToParent" id="retail_uom_quntToParent" class="form-control" value="{{ old('retail_uom_quntToParent',$data['retail_uom_quntToParent']*1) }}" placeholder="ادخل  عدد وحدات التجزئة">
                  @error('retail_uom_quntToParent')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            @if($data['parent_inv_itemcard_id']==0)
            <div class="col-md-6 relatied_parent_counter ">
               <div class="form-group">
                  <label> {{__('home.reamingquantity')}} (<span class="parentuomname"></span>) </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="reamingquantity" id="reamingquantity"  class="form-control" value="{{ old('',$data['All_QUENTITY']) }}" placeholder="ادخل السعر ">
                  @error('price')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>   <div class="col-md-6 relatied_parent_counter ">
               <div class="form-group">
                  <label> {{__('home.saleperpice')}} (<span class="parentuomname"></span>) </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="priceWithTax" id="priceWithTax" onkeyup="changePriceWithTax()" class="form-control" value="{{ old('price',round($data['price']+($data['price']*$avtSaleRate),2)) }}" placeholder="ادخل السعر ">
                  @error('price')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6 relatied_parent_counter ">
               <div class="form-group">
                  <label> {{__('home.sellingproduct without tax')}} (<span class="parentuomname"></span>) </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="price" id="price" class="form-control" value="{{ old('price',$data['price']*1) }}" onkeyup="priceParentRatioChange()" placeholder="ادخل السعر ">
                  @error('price')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>


            <div class="col-md-6 relatied_parent_counter ">
               <div class="form-group">
                  <label> {{__('home.purchase price per unit')}}(<span class="parentuomname"></span>) </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="cost_price" id="cost_price" class="form-control" onchange="cost_priceRatioChange()" value="{{ old('cost_price',$data['cost_price']*1) }}" placeholder="ادخل السعر ">
                  @error('cost_price')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6 relatied_retial_counter " @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
               <div class="form-group">
                  <label> {{__('home.purchase price per unit')}} (<span class="childuomname"></span>) {{__('home.without_tax')}} </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="cost_price_retail" id="cost_price_retail" class="form-control" onchange="cost_price_retailParentRatioChange()" value="{{ old('cost_price_retail',$data['cost_price_retail']*1) }}" placeholder="ادخل السعر ">
                  @error('cost_price_retail')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6 relatied_retial_counter " @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
               <div class="form-group">
                  <label>  {{__('home.saleperpice')}}(<span class="childuomname"></span>) </label>
                  <input hidden  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="price_retail" id="price_retail" class="form-control" value="{{ old('price_retail',round($data['price_retail']*1,2)) }}" placeholder="ادخل السعر " hidden >
                  <input  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="price_retail_with_tax" id="price_retail_with_tax" class="form-control" value="{{ old('price_retail',round($data['price_retail']+($data['price_retail']*$avtSaleRate),2)) }}" placeholder="ادخل السعر " onchange="priceRatioChange()">
                  @error('price_retail')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            @endif
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.pricestatus')}} </label>
                  <select name="has_fixced_price" id="has_fixced_price" class="form-control">
                     <option value="">{{__('home.chooseOne')}}</option>
                     <option {{  old('has_fixced_price',$data['has_fixced_price'])==1 ? 'selected' : ''}} value="1"> {{__('home.constPrice')}}</option>
                     <option {{  old('has_fixced_price',$data['has_fixced_price'])==0 ? 'selected' : ''}} value="0"> {{__('home.changePrice')}}</option>
                  </select>
                  @error('has_fixced_price')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.statusactive')}}</label>
                  <select name="active" id="active" class="form-control">
                     <option value=""> {{__('home.statusactive')}}</option>
                     <option {{  old('active',$data['active'])==1 ? 'selected' : ''}} value="1"> {{ __('users.active') }}</option>
                     <option {{  old('active',$data['active'])==0 ? 'selected' : ''}} value="0"> {{ __('users.disactive') }}</option>
                  </select>
                  @error('active')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6" style="border:solid 5px #000 ; margin:10px;">
               <div class="form-group">
                  <label> {{__('home.photo')}}</label>
                  <img id="uploadedimg" src="#" alt="uploaded img" style="width: 200px; width: 200px;">
                  <input onchange="readURL(this)" type="file" id="Item_img" name="Item_img" class="form-control">
                  @error('active')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div id="oldimage">
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="form-group text-center">
               <button id="do_edit_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{__('home.update')}}</button>
               <a href="{{ route('admin.itemcard.index') }}" class="btn btn-sm btn-danger">{{__('users.back')}}</a>
            </div>
         </div>
   </div>
   </form>
</div>
</div>
</div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <!-- Ionicons js -->
    <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!--Internal  pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script src="{{ asset('assets/js/inv_itemcard.js') }}"></script>
<script src="{{ asset('assets/js/inv_itemcard.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

   function changePriceWithTax() {
      quantity = $("#retail_uom_quntToParent").val()

      console.log("erweeweeweww - - - - -")
      price = $('#priceWithTax').val();
      avtvalue = (price * 100) / 115
      $('#price').val(avtvalue.toFixed(3));
      $("#price_retail").val($("#price").val() / quantity)
      $("#price_retail_with_tax").val(($("#priceWithTax").val()) / quantity)

   }

   function changeAvtValue(avt) {
      quantity = $("#retail_uom_quntToParent").val()

      avt = $('#avtValue').val();
      price = $('#price').val();
      $('#priceWithTax').val(((price * 1) + Math.round((price * avt) * 1000) / 1000).toFixed(2));
      $("#price_retail").val(($("#price").val()) / quantity)
      $("#price_retail_with_tax").val(($("#priceWithTax").val()) / quantity)

   }
   var uom_id = $("#uom_id").val();
   if (uom_id != "") {
      var name = $("#uom_id option:selected").text();
      $(".parentuomname").text(name);
   }

   var uomretail_uom_id_id = $("#retail_uom_id").val();
   if (retail_uom_id != "") {
      var name = $("#retail_uom_id option:selected").text();
      $(".childuomname").text(name);
   }

   function priceRatioChange() {

      quantity = $("#retail_uom_quntToParent").val()
      $("#price_retail").val(($("#price_retail_with_tax").val()* 100) / 115 )


   }

   function priceParentRatioChange() {

      quantity = $("#retail_uom_quntToParent").val()
      $("#price_retail").val($("#price").val() / quantity)
      avt = $('#avtValue').val();
      price = $('#price').val();
      $('#priceWithTax').val(((price * 1) + Math.round((price * avt) * 1000) / 1000).toFixed(2));
      $("#price_retail").val($("#price").val() / quantity)
      $("#price_retail_with_tax").val(($("#priceWithTax").val()) / quantity)

   }

   function cost_priceRatioChange() {

      quantity = $("#retail_uom_quntToParent").val()
      $("#cost_price_retail").val($("#cost_price").val() / quantity)


   }

   function cost_price_retailParentRatioChange() {

      quantity = $("#retail_uom_quntToParent").val()
      $("#cost_price").val(quantity * $("#cost_price_retail").val())

   }
</script>
@endsection