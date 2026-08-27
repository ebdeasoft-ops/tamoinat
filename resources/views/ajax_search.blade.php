@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;
@endphp
<table class="table text-md-nowrap text-center our-table" id="example2" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
    <col style="width:5%">
    <col style="width:15%">
    <col style="width:20%">
    <col style="width:10%">
    <col style="width:10%">
    <col style="width:10%">
    <col style="width:15%">
    <col style="width:15%">


    <thead>
        <tr>
            <th style="font-size: 15px" class="border-bottom-0">#</th>
            <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
            <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
            <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
            <th style="font-size: 15px" class="border-bottom-0">{{__('home.productlocation')}}</th>
            <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
            <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
            <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>



        </tr>
    </thead>
    <tbody class="">
        <?php $i = 0;
        ?>

        @foreach ($data as $product)
        <?php $i++ ?>

        <tr id="<?php echo $product['id']; ?>">
            <td id="tableData" dir=ltr>{{ $product->id }}</td>
            <td id="tableData" dir=ltr>{{ $product->barcode }}</td>
            <td id="tableData" data-target="product_name">{{ $product->name }}</td>
            <td id="tableData" data-target="product_name">{{ $product->branch->name }}</td>
            <td id="tableData" data-target="numberofpice">{{ $product->Product_Location }}</td>
            <td id="tableData" data-target="numberofpice">{{ $product->QUENTITY_all_Retails }}</td>
            <td id="tableData" data-target="numberofpice">{{ $product->cost_price_retail }}</td>
            <td id="tableData" data-target="numberofpice">{{ $product->price_retail }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br>
<div class="justify-content-start" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>



@else
<div class="alert alert-danger">
{{__('home.notfounddata')}}  </div>
@endif