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
            @if($product->parent_inv_itemcard_id==0)
            <tr id="<?php echo $product['id']; ?>">
                <td id="tableData" dir=ltr>{{ $product->id }}</td>
                <td id="tableData" dir=ltr>{{ $product->barcode }}</td>
                <td id="tableData" data-target="product_name">{{ $product->name }}</td>
                <td id="tableData" data-target="product_name">{{ $product->branch->name }}</td>
                <td id="tableData" data-target="numberofpice">{{ $product->Product_Location }}</td>
                <td id="tableData" data-target="numberofpice">
                    <?php
                    if ($product->does_has_retailunit == 1) {
                        $partial = $product->retail_uom_quntToParent;
                        $partialId = $product->retail_Uom->id;
                        $partialName = $product->retail_Uom->name;
                        $cost = $product->cost_pric;
                        $price = $product->price;
                    } else {
                        $partial = 1;
                        $partialId = '-';
                        $partialName = '-';
                        $cost = $product->cost_price;
                        $price = $product->price;
                    }
                    ?>
                    {{$product->All_QUENTITY}}
                           @if($product->parent_inv_itemcard_id==0)
                            {{ $product->Parent_uom->name}}

                            @else
                            <?php
                            $parentproduct = App\Models\products::find($product->parent_inv_itemcard_id);
                            ?>
                            {{ $parentproduct->retail_Uom->name}}

                            @endif</span>
                   
                </td>
               
                <td id="tableData" data-target="numberofpice">{{ $product->cost_price }}</td>
                <td id="tableData" data-target="numberofpice">{{ $product->price }}</td>
              
                <td id="tableData">

                    <button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick="chooseProduct('{{ $product->id }}','{{ $product->name }}','{{ $cost }}','{{ $price }}','{{ $product->barcode }}','{{ $product->All_QUENTITY* $partial }}','{{ $product->Parent_uom->id }}','{{$product->Parent_uom->name }}','{{ $partialId }}','{{ $partialName}}','{{ $product->item_type}}')">{{ __('home.Add') }}</button>


                </td>
            </tr>
            @endif
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
            {{__('home.notfounddata')}}
        </div>
        @endif