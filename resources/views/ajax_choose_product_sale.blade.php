@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;
@endphp
<div class="table-responsive">
    <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
        <col style="width:5%">
        <col style="width:14%">
        <col style="width:28%">
        <col style="width:10%">
        <col style="width:10%">
        <col style="width:13%">
        <col style="width:10%">
        <col style="width:10%">

        <thead>
            <tr>
                <th style="font-size: 15px" class="border-bottom-0">#</th>
                <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.productlocation')}}</th>

                <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>
                <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



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
                <td id="tableData" data-target="numberofpice">
                   
                    @if( $product->All_QUENTITY<=0) <span style="color:red;font-size:14px">{{($product->All_QUENTITY)." / ".__("home.notavailable")}}</span>
                    @if($product->parent_inv_itemcard_id==0)
                            {{ $product->Parent_uom->name}}

                            @else
                            <?php
                            $parentproduct = App\Models\products::find($product->parent_inv_itemcard_id);
                            ?>
                            {{ $parentproduct->retail_Uom->name}}

                            @endif</span>

                        @else
                        <span style="color:green;font-size:14px">{{$product->All_QUENTITY}}
                            @if($product->parent_inv_itemcard_id==0)
                            {{ $product->Parent_uom->name}}

                            @else
                            <?php
                            $parentproduct = App\Models\products::find($product->parent_inv_itemcard_id);
                            ?>
                            {{ $parentproduct->retail_Uom->name}}

                            @endif</span>


                        @endif
                    </td>

                <td id="tableData" data-target="numberofpice">{{ $product->cost_price }}</td>
                <td id="tableData" data-target="numberofpice">{{round( $product->price,2) }}</td>

                <td id="tableData">

                    @if($product->All_QUENTITY<=0) <button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-danger" data-dismiss="modal" onclick="chooseProduct('{{$product->id}}','{{$product->barcode}}','{{$product->name}}','{{$product->cost}}','{{$product->price}}','{{$product->Product_Location}}','{{$product->All_QUENTITY}}','{{ $product->Parent_uom->id }}','{{$product->Parent_uom->name }}','{{ 1 }}','{{ 1}}','{{ $product->has_fixced_price}}')">{{__('home.Add')}}</button>

                        @else

                        <button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick="chooseProduct('{{$product->id}}','{{$product->barcode}}','{{$product->name}}','{{$product->cost}}','{{$product->price}}','{{$product->Product_Location}}','{{$product->All_QUENTITY}}','{{ $product->Parent_uom->id }}','{{ 1 }}','{{ 1}}','{{ $product->has_fixced_price}}')">{{__('home.Add')}}</button>


                        @endif



                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <br>
        <div class="justify-content-start" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>



        @else
        <div class="alert alert-danger">
        {{__('home.notfounddata')}}         </div>
        @endif