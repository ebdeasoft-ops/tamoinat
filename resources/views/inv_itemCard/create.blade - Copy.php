@extends('layouts.master')
@section('title')
{{__('supprocesses.addproduct')}}
@stop

@section('contentheaderactive')
اضافة
@endsection
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title card_title_center">{{__('supprocesses.addproduct')}}</h3>
   </div>
   <!-- /.card-header -->
   <br>
   <br>
   <div class="card-body">
   
      <input type="hidden" id="ajax_url_ajax_check_barcode" value="{{ route('admin.itemcard.ajax_check_barcode') }}" >
      <input type="hidden" id="ajax_url_ajax_check_name" value="{{ route('admin.itemcard.ajax_check_name') }}" >
      <input type="hidden" id="token_search" value="{{csrf_token() }}">
      <form action="{{ route('admin.itemcard.store') }}" method="post" enctype="multipart/form-data" >
         <div class="row">
            @csrf
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.barcodeifnotfount')}}</label> <span id="barcodeCheckMessage"> </span>
                  <input name="barcode" id="barcode" autofocus class="form-control" value="{{ old('barcode') }}" placeholder="{{__('home.barcode')}}"  >
                  @error('barcode')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label> {{__('home.product')}}</label>  <span id="nameCheckMessage"> </span>
                  <input name="name" id="name" class="form-control" value="{{ old('name') }}"    >
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label>    {{__('home.typeitem')}}</label>
                  <select name="item_type" id="item_type" class="form-control">
                     <option value=""> {{__('home.chooseOne')}}</option>
                     <option   @if(old('item_type')==1) selected="selected"  @endif value="1"> {{__('home.without_validity')}}</option>
                     <option   @if(old('item_type')==2) selected="selected"  @endif value="2">   {{__('home.validity')}} </option>
                  </select>
                  @error('item_type')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label>    {{__('home.itemclass')}}</label>
                  <select name="inv_itemcard_categories_id" id="inv_itemcard_categories_id" class="form-control ">
                     <option value=""> {{__('home.chooseOne')}}</option>
                     @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                     @foreach ($inv_itemcard_categories as $info )
                     <option @if(old('inv_itemcard_categories_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
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
                  <label>  {{__('home.parentproduct')}}</label>
                  <select name="parent_inv_itemcard_id" id="parent_inv_itemcard_id" class="form-control ">
                     <option selected value="0">  {{__('home.isparent')}}</option>
                     @if (@isset($item_card_data) && !@empty($item_card_data))
                     @foreach ($item_card_data as $info )
                     <option @if(old('parent_inv_itemcard_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
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
                  <label>     {{__('home.parentunit')}}</label>
                  <select name="uom_id" id="uom_id" class="form-control ">
                     <option value="">   {{__('home.chooseOne')}}</option>
                     @if (@isset($inv_uoms_parent) && !@empty($inv_uoms_parent))
                     @foreach ($inv_uoms_parent as $info )
                     <option @if(old('uom_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
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
                  <label>{{__('home.haspartialunit')}}   </label>
                  <select name="does_has_retailunit" id="does_has_retailunit" class="form-control">
                     <option value=""> {{__('home.chooseOne')}}</option>
                     <option   @if(old('does_has_retailunit')==1) selected="selected"  @endif value="1"> {{__('home.yes')}} </option>
                     <option @if(old('does_has_retailunit')==0 and old('does_has_retailunit')!="" ) selected="selected"   @endif value="0"> {{__('home.No')}}</option>
                  </select>
                  @error('does_has_retailunit')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6  " @if(old('does_has_retailunit')!=1 ) style="display: none;" @endif  id="retail_uom_idDiv"> 
            <div class="form-group">
               <label> {{__('home.Retail unit of measure')}} (<span class="parentuomname"></span>)</label>
               <select name="retail_uom_id" id="retail_uom_id" class="form-control ">
                  <option value="">  {{__('home.chooseOne')}}</option>
                  @if (@isset($inv_uoms_child) && !@empty($inv_uoms_child))
                  @foreach ($inv_uoms_child as $info )
                  <option @if(old('retail_uom_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                  @endif
               </select>
               @error('retail_uom_id')
               <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
         <div class="col-md-6 relatied_retial_counter "  @if(old('retail_uom_id')=="" ) style="display: none;" @endif> 
         <div class="form-group">
            <label ><span style="color:red"> {{__('home.The number of retail units')}}</span> <span class="childuomname"></span><span style="color:red"> {{__('home.For the main')}}</span><span class="parentuomname"></span>  </label>
            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="retail_uom_quntToParent" id="retail_uom_quntToParent" class="form-control"  value="{{ old('retail_uom_quntToParent') }}" placeholder="ادخل  عدد وحدات التجزئة"  >
            @error('retail_uom_quntToParent')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
   </div>
   <div class="col-md-6 relatied_parent_counter "  @if(old('uom_id')=='' ) style="display: none;" @endif> 
   <div class="form-group">
   <label>{{__('home.Wholesale price per unit')}}(<span class="parentuomname"></span>)  </label>
   <input type="number" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="price" id="price" class="form-control"  value="{{ old('price')??0 }}" placeholder="ادخل السعر " >
   @error('price')
   <span class="text-danger">{{ $message }}</span>
   @enderror
   </div>
</div>
<div class="col-md-6 relatied_retial_counter " @if(old('retail_uom_id')=="" ) style="display: none;" @endif> 
<div class="form-group">
<label >  {{__('home.Sector price per unit')}}(<span class="childuomname"></span>)  </label>
<input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="price_retail" id="price_retail" class="form-control"  value="{{ old('price_retail') }}" placeholder="ادخل السعر " >
@error('price_retail')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>
</div>
<div class="col-md-6 relatied_parent_counter "  @if(old('uom_id')=='' ) style="display: none;" @endif> 
<div class="form-group">
<label > {{__('home.Purchase cost price per unit')}}(<span class="parentuomname"></span>)  </label>
<input type="number" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="cost_price" id="cost_price" class="form-control"  value="{{ old('cost_price')??0 }}" placeholder="ادخل السعر " >
@error('cost_price')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>
</div>


<div class="col-md-6 relatied_retial_counter " @if(old('retail_uom_id')=="" ) style="display: none;" @endif> 
<div class="form-group">
<label > {{__('home.purchase price per unit')}}(<span class="childuomname"></span>)  </label>
<input type="number" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="cost_price_retail" id="cost_price_retail" class="form-control"  value="{{ old('cost_price_retail')??0 }}" placeholder="ادخل السعر " >
@error('cost_price_retail')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>
</div>
<div class="col-md-6">
<div class="form-group"> 
<label>   {{__('home.pricestatus')}} </label>
<select name="has_fixced_price" id="has_fixced_price" class="form-control">
<option value="">{{__('home.chooseOne')}} </option>
<option   @if(old('has_fixced_price')==1) selected="selected"  @endif value="1"> {{__('home.constPrice')}}</option>
<option @if(old('has_fixced_price')==0 and old('active')!="" ) selected="selected"   @endif value="0">{{__('home.changePrice')}}</option>
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
<option value="">   {{__('home.chooseOne')}}</option>
<option   @if(old('active')==1) selected="selected"  @endif value="1"> {{ __('users.active') }}</option>
<option @if(old('active')==0 and old('active')!="" ) selected="selected"   @endif value="0"> {{ __('users.disactive') }}</option>
</select>
@error('active')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>
</div>
<div class="col-md-6" style="border:solid 5px #000 ; margin:10px;">
<div class="form-group"> 
<label>    {{__('home.photo')}}</label>
<img id="uploadedimg" src="#" alt="uploaded img" style="width: 200px; width: 200px;" >        
<input onchange="readURL(this)" type="file" id="Item_img" name="Item_img" class="form-control">
@error('active')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>
</div>  
<div class="col-md-12">
<div class="form-group text-center">
<button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">  {{__('home.confirm')}}</button>
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
<script src="{{ URL::asset('assets/js/inv_itemcard.js') }}"></script>

<script>
   var uom_id=$("#uom_id").val();
   if(uom_id!=""){
     var name=$("#uom_id option:selected").text();  
       $(".parentuomname").text(name); 
   }
   
   var uomretail_uom_id_id=$("#retail_uom_id").val();
   if(retail_uom_id!=""){
     var name=$("#retail_uom_id option:selected").text();  
       $(".childuomname").text(name); 
   }
</script>
@endsection