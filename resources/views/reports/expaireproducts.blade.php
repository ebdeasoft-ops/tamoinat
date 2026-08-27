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

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.expairdate') }}
@stop

@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title card_title_center"> {{ __('home.expairdate') }}</h3>
      <input type="hidden" id="token_search" value="{{csrf_token() }}">
      <input type="hidden" id="ajax_search_url" value="{{ route('admin.itemcard.ajax_search') }}">

   </div>
   <!-- /.card-header -->
   <div class="card-body">
      <div class="row">
         <div class="col-md-4">
            <input checked type="radio" name="searchbyradio" id="searchbyradio" value="barcode"> {{__('home.product')}}
            <input type="radio" name="searchbyradio" id="searchbyradio" value="item_code">
            <input type="radio" name="searchbyradio" id="searchbyradio" value="name">
            <input style="margin-top: 6px !important;" type="text" id="search_by_text" placeholder=" اسم - باركود - كود للصنف" class="form-control"> <br>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> {{__('home.itemclass')}}</label>
               <select name="item_type_search" id="item_type_search" class="form-control">
                  <option value="all"> {{__('home.searchbyall')}}</option>
                  <option value="1"> {{__('home.without_validity')}}</option>
                  <option value="2"> {{__('home.validity')}}</option>
               </select>
               @error('item_type')
               <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> {{__('home.catogeries')}}</label>
               <select class="form-control select2" name="inv_itemcard_categories_id_search" id="inv_itemcard_categories_id_search" class="form-control ">
                  <option value="all"> {{__('home.searchbyall')}}</option>
                  @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                  @foreach ($inv_itemcard_categories as $info )
                  <option value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                  @endif
               </select>
               @error('inv_itemcard_categories_id')
               <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
         <div class="clearfix"></div>
         <div id="ajax_responce_serarchDiv" class="col-md-12">
            @if (@isset($data) && !@empty($data))
            @php
            $i=1;
            @endphp
            <table id="example2" class="table our-table border mb-0 table-responsive text-center">
               <col style="width:5%">
               <col style="width:15% ">
               <col style="width:10%">
               <col style="width:15%">
               <col style="width:10%">
               <col style="width:7.5%">
               <col style="width:7.5%">
               <col style="width:7.5%">
               <col style="width:7.5%">
               <col style="width:15%">
               <thead class="custom_thead">
                  <th> {{__('home.barcode')}} </th>
                  <th> {{ __('home.productImage') }} </th>
                  <th>{{__('home.product')}} </th>
                  <th>{{__('home.proDate')}} </th>
                  <th>{{__('home.expDate')}} </th>
                  <th> {{__('home.typeitem')}} </th>
                  <th> {{__('home.itemclass')}} </th>
                  <th>{{__('home.parentProduct')}}</th>
                  <th> {{__('home.parentunit')}} </th>
                  <th> {{__('home.stock')}} </th>
                  <th> {{__('home.statusactive')}}</th>
                  <th>{{__('home.operations')}}</th>
               </thead>
               <tbody>
                  @foreach ($data as $info )
                  <tr>
                     <td>{{ $info->barcode }}</td>
                     <td> <img style="width:80px;height:80px;" class="custom_img" src="{{ asset('assets/admin/uploads').'/'.$info->photo }}" alt="لوجو الشركة">
                     </td>
                     <td>{{ $info->name }}</td>
                     <td>{{ $info->prodection_date }}</td>
                     <td>{{ $info->expaire_date }}</td>
                     <td>@if($info->item_type==1) {{__('home.without_validity')}} @elseif($info->item_type==2) {{__('home.validity')}} @else غير محدد @endif</td>
                     <td>{{ $info->inv_itemcard_categories_name }}</td>
                     @if($info->parent_inv_itemcard_id!=0)
                     <td>{{ $info->parentProduct->name }}</td>

                     @else
                     <td> {{__('home.isparent')}} </td>
                     @endif
                     <td>{{ $info->Uom_name }}</td>
                     @if($info->parent_inv_itemcard_id!=0)
                     <td>{{ $info->parentProduct->All_QUENTITY*1 }} {{ $info->Uom_name }}</td>

                     @else
                     <td>{{ $info->All_QUENTITY*1 }} {{ $info->Uom_name }}</td>
                     @endif
                     <td>@if($info->active==1) {{__('users.active')}} @else {{ __('users.disactive') }} @endif</td>
                     <td>
                        @can('Product data change')

                        <a href="{{ route('admin.itemcard.edit',$info->id) }}" class="btn btn-sm  btn-primary"> {{ __('users.update') }}</a>
                        <a href="{{ route('admin.itemcard.show',$info->id) }}" class="btn btn-sm   btn-info"> {{ __('home.show') }}</a>
                        @endcan

                        <a target="_blank" href="{{ route('admin.itemcard.generate_barcode',$info->id) }}" class="btn btn-sm   btn-success">{{__('home.barcode')}} <i class="fa fa-print"></i></a>


                     </td>
                  </tr>
                  @php
                  $i++;
                  @endphp
                  @endforeach
               </tbody>
            </table>
            <br>
            {{ $data->links() }}
            @else
            <div class="alert alert-danger">
               عفوا لاتوجد بيانات لعرضها !!
            </div>
            @endif
         </div>
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
@endsection