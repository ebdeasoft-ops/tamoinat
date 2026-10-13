{{ app()->setLocale($getLocale) }}
@if (isset($data) && $data->isNotEmpty())
    <div class="table-responsive p-3">
        <table class="table text-center our-table" id="SearchProductTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('home.productNo') }}</th>
                    <th>{{ __('home.product') }}</th>
                    <th>{{ __('home.branch') }}</th>
                    <th>{{ __('home.productlocation') }}</th>
                    <th>{{ __('home.quantity') }}</th>
                    @can('System setting')
                        <th>{{ __('home.purchaseproductwithouttax') }}</th>
                    @endcan
                    <th>{{ __('home.sellingproduct without tax') }}</th>
                    <th>{{ __('home.Add') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    @php
                        // بيانات المنتج الأب (لو موجود) بنحسبها مرة واحدة بس هنا
                        // وبنستخدمها في عمود الكمية وفي زرار الإضافة مع بعض، عشان منكررش الاستعلام
                        // ملحوظة: لازم "&&" مش "||" هنا -- لو حطينا "||" هيبقى $hasParent = true
                        // دايمًا تقريبًا (عشان id != 0 بتبقى true لأي منتج حقيقي)، وده كان بيخلي
                        // المنتجات المستقلة تتعامل غلط كإنها أب لنفسها، وكمان ممكن يكسر الصفحة لو
                        // main_product كانت 0 فعلاً (find(0) بترجع null وبعدين numberofpice على null = خطأ).
                        $hasParent = $product->id != 0 && $product->id != $product->main_product;
                        $productMain = $hasParent ? App\Models\products::find($product->main_product) : null;
                    @endphp
                    <tr id="{{ $product->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->Product_Code }}</td>
                        <td class="font-weight-bold">{{ $product->product_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $product->branch->name ?? '**' }}</span></td>
                        <td>{{ $product->Product_Location }}</td>
                        <td>
                            @if($hasParent && $productMain)
                           <span class="text-success fw-bold" style="font-size: 1.1rem;">{{ $productMain->numberofpice }} ({{ $productMain->unit }})</span>

                            @else
                         <span class="text-success fw-bold" style="font-size: 1.1rem;">{{ $product->numberofpice }} ({{ $product->unit }})</span>

                            @endif
                        </td>
                        @can('System setting')
                            <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                        @endcan
                        <td class="fw-bold text-primary">{{ number_format($product->sale_price, 2) }}</td>
                        <td>
                            <div class="d-flex flex-column align-items-center">
                                <button class="btn btn-action btn-add" data-dismiss="modal"
                                    onclick="chooseProduct('{{$product->id}}','{{$product->Product_Code}}','{{$product->product_name}}','{{$product->purchasingـprice}}','{{$product->sale_price}}','{{$product->Product_Location}}','{{$product->numberofpice}}','{{$currentrow}}','{{ ($hasParent && $productMain) ? $product->main_product : 0 }}','{{ ($hasParent && $productMain) ? addslashes($productMain->product_name) : '' }}','{{ ($hasParent && $productMain) ? $productMain->numberofpice : '' }}')">
                                    <i class="fa fa-plus-circle me-1"></i> {{ __('home.Add') }}
                                </button>


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>
    </div>
@else
    <div class="alert alert-danger text-center">
        <i class="fa fa-info-circle"></i> {{ __('home.notfounddata') }}
    </div>
@endif