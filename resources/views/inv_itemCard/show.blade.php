@extends('layouts.master')
@section('title')
{{ __('home.show productdata') }}
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


@section('content')
<div class="card">
<div class="card-header">
   <h3 class="card-title card_title_center">  {{ __('home.show productdata') }}
</h3>
</div>
<!-- /.card-header -->
<div class="card-body">
   @if (@isset($data) && !@empty($data))
   <div class="row">
      <table id="example2" class="table table-bordered table-hover">
         <tr>
            <td colspan="3">
               <label >{{__('home.productNo')}}</label> <br>
               {{ $data['item_code'] }}
               <input type="hidden" id="token_search" value="{{csrf_token() }}">
               <input type="hidden" id="ajax_search_movements" value="{{ route('admin.itemcard.ajax_search_movements') }}">
            </td>
         </tr>
         <tr>
            <td>
               <label> {{__('home.barcode')}}</label> <br>
               {{ $data['barcode'] }}
            </td>
            <td>
               <label> {{__('home.product')}}</label> <br>
               {{ $data['name'] }}
            </td>
            <td>
               <label>   {{__('home.typeitem')}}</label> <br>
               @if($data['item_type']==1) {{__('home.without_validity')}}  @elseif($data['item_type']==2)     {{__('home.validity')}}  @else غير محدد @endif
            </td>
         </tr>
         <tr>
            <td>
               <label> {{__('home.itemclass')}}</label> <br>
               {{ $data['inv_itemcard_categories_name'] }}
            </td>
            <td>
               <label>{{__('home.parentProduct')}}</label> <br>
               {{ $data['parent_item_name'] }}
            </td>
            <td>
               <label>{{__('home.parentunit')}}   </label> <br>
               {{ $data['Uom_name'] }}
            </td>
         </tr>
         <tr>
            <td @if($data['does_has_retailunit']==0) colspan="3"   @endif >
            <label>   {{__('home.haspartialunit')}}</label> <br>
            @if($data['does_has_retailunit']==1) {{__('home.yes')}}  @else   {{__('home.No')}} @endif
            </td> 
            @if($data['does_has_retailunit']==1)
            <td>
               <label>  {{__('home.Retail unit of measure')}}</label> <br>
               {{ $data['retail_uom_name'] }}
            </td>
            <td>
               <label>  {{__('home.The number of retail units')}}{{ $data['retail_uom_name']  }}  {{__('home.For the main')}} {{ $data['Uom_name']  }} </label> <br>
               {{ $data['retail_uom_quntToParent']*1 }}
            </td>
            @endif
         </tr>
         <tr>
            <td @if($data['does_has_retailunit']==0) colspan="3"   @endif >
            <label>   {{__('home.haspartialunit')}}</label> <br>
            @if($data['does_has_retailunit']==1) {{__('home.yes')}}  @else   {{__('home.No')}} @endif
            </td> 
            @if($data['does_has_retailunit']==1)
            <td>
               <label>  {{__('home.Retail unit of measure')}}</label> <br>
               {{ $data['retail_uom_name'] }}
            </td>
            <td>
               <label>  {{__('home.The number of retail units')}} {{ $data['retail_uom_name']  }} {{__('home.For the main')}} {{ $data['Uom_name']  }} </label> <br>
               {{ $data['retail_uom_quntToParent']*1 }}
            </td>
            @endif
         </tr>
         <tr>
            <td>
               <label> {{__("home.Sector price per unit")}}(  {{ $data['Uom_name']  }})</label> <br>
               {{ $data['price']*1 }}
            </td>
       
         </tr>
         <tr>
            <td @if($data['does_has_retailunit']==0) colspan="3"   @endif >
            <label> {{__("home.Purchase cost price per unit")}}(  {{ $data['Uom_name']  }})</label> <br>
            {{ $data['cost_price']*1 }}
            </td>
            @if($data['does_has_retailunit']==1) 
            <td>
               <label> {{__("home.Sector price per unit")}}(  {{ $data['retail_uom_name']  }})</label> <br>
               {{ $data['price_retail']*1 }}
            </td>
          
            @endif
         </tr>
         @if($data['does_has_retailunit']==1)
         <tr>
            <td colspan="1">
               <label> {{__("home.Purchase cost price per unit")}}(  {{ $data['retail_uom_name']  }})</label> <br>
               {{ $data['cost_price_retail']*1 }} 
            </td>
         </tr>
         @endif
         <tr>
            <td colspan="2">
            {{__("home.Showـtheـavailableـquantityـtoـtheـcustomer")}}(
               @if( $data['parent_inv_itemcard_id']  !=0)
                     {{ $data['parentProduct']->All_QUENTITY*1 }} {{ $data['Uom_name']  }}  
                @else
                 {{ $data['All_QUENTITY']*1  }} {{ $data['Uom_name']  }}  
                 @endif
                 )
            </td>
         </tr>
         <tr>
            <td>
               <label>  {{__('home.pricestatus')}}</label> <br>
               @if($data['has_fixced_price']==1)  {{__('home.constPrice')}}  @else  {{__('home.changePrice')}} @endif
            </td>
            <td colspan="2">
               <label>  {{__('home.statusactive')}}</label> <br>
               @if($data['active']==1) {{ __('users.active') }}  @else  {{ __('users.disactive') }} @endif
            </td>
         </tr>
         <tr>
            <td >  {{ __('home.productImage') }}</td>
            <td colspan="2" >
               <div class="image" >
                  <img style="width:300px;height: 300px;" class="custom_img" src="{{ asset('assets/admin/uploads').'/'.$data['photo'] }}"  alt="لوجو الشركة">       
               </div>
            </td>
         </tr>
         <tr>
            <td > {{ __('home.updateddate') }}</td>
            <td colspan="2"> 
               @if($data['updated_by']>0 and $data['updated_by']!=null )
               @php
               $dt=new DateTime($data['updated_at']);
               $date=$dt->format("Y-m-d");
               $time=$dt->format("h:i");
               $newDateTime=date("A",strtotime($time));
               $newDateTimeType= (($newDateTime=='AM')?__('home.Am') :__('home.PM'));
               @endphp
               {{ $date }}
               {{ $time }}
               {{ $newDateTimeType }}
               {{__('home.by')}}
               {{ $data['updated_by_admin'] }}
               @else
               {{__('home.noUpdate')}}
                @endif
               <a href="{{ route('admin.itemcard.edit',$data['id']) }}" class="btn btn-sm btn-success">تعديل</a>
            </td>
         </tr>
      </table>
   </div>
   
      @else
      <div class="alert alert-danger">
{{__('home.notfounddata')}}      </div>
      @endif
   </div>
</div>
@endsection
@section("js")
<script src="{{ asset('assets/admin/js/inv_itemcard.js') }}"></script>
<script  src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });
</script>
@endsection