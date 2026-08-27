@if (@isset($data) && !@empty($data) && count($data) >0)
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
               <col style="width:15%">   <thead class="custom_thead">
               <th> {{__('home.barcode')}} </th>
                  <th> {{ __('home.productImage') }} </th>
                  <th>{{__('home.product')}} </th>
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
                     <td>@if($info->item_type==1)
                        {{__('home.without_validity')}}
                        @elseif($info->item_type==2)
                        {{__('home.validity') }} {{__('home.expDate')}} :
                        <span style="color:red">{{$info->expaire_date}} </span>
                        @else غير محدد @endif
                     </td>
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
                        <br>
                        <br>
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
<div class="col-md-12" id="ajax_pagination_in_search">
   {{ $data->links() }}
</div>
@else
<div class="clearfix"></div>
<div class="alert alert-danger">
   عفوا لاتوجد بيانات لعرضها !!
</div>
@endif