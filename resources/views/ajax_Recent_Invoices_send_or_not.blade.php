<style>
    /* ==========================================================================
       جدول نتائج البحث عن الفواتير السابقة — تصميم احترافي (Scoped)
       ========================================================================== */
    .pro-search-wrap {
        --ps-navy: #1b3358;
        --ps-navy-light: #23395D;
        --ps-border: #e3e7ee;
        --ps-radius: 10px;
        --ps-shadow: 0 1px 3px rgba(16, 24, 40, .06);
    }

    .pro-search-wrap .our-table {
        border: 1px solid var(--ps-border) !important;
        border-radius: var(--ps-radius);
        overflow: hidden;
        box-shadow: var(--ps-shadow);
    }

    .pro-search-wrap .our-table thead th {
        background: linear-gradient(135deg, var(--ps-navy) 0%, var(--ps-navy-light) 100%) !important;
        color: #fff !important;
        font-weight: 700 !important;
        border: none !important;
        padding: 12px 8px;
        vertical-align: middle;
    }

    .pro-search-wrap .our-table tbody td {
        border-color: var(--ps-border) !important;
        vertical-align: middle;
        padding: 10px 8px;
    }

    .pro-search-wrap .our-table tbody tr:nth-child(even) {
        background: #fafbfd;
    }

    .pro-search-wrap .our-table tbody tr:hover {
        background: #eef4ff;
    }

    .pro-search-wrap .pro-action-show {
        display: inline-flex !important;
        width: auto !important;
        max-width: fit-content;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: var(--ps-bg-soft, #f2f4f9);
        border: 1px solid var(--ps-border);
        border-radius: 20px;
        padding: 5px 16px;
        font-weight: 700;
        font-size: 12.5px;
        color: var(--ps-navy) !important;
        transition: all .15s ease;
        margin: 0 auto 6px auto;
    }

    .pro-search-wrap td {
        text-align: center;
    }

    .pro-search-wrap .pro-actions-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 4px 0;
    }

    .pro-search-wrap .pro-action-show:hover {
        background: var(--ps-navy);
        color: #fff !important;
        text-decoration: none;
        box-shadow: var(--ps-shadow);
    }

    .pro-search-wrap .pro-action-zatca {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none !important;
        border-radius: 20px !important;
        padding: 6px 14px !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
        box-shadow: var(--ps-shadow);
        transition: transform .15s ease, filter .15s ease;
    }

    .pro-search-wrap .pro-action-zatca:hover {
        transform: translateY(-2px);
        filter: brightness(1.08);
    }

    .pro-search-wrap .pro-action-zatca.pro-zatca-pass {
        background: linear-gradient(135deg, #7c8896 0%, #64748b 100%) !important;
        color: #fff !important;
    }

    .pro-search-wrap .pro-action-zatca.pro-zatca-upload {
        background: linear-gradient(135deg, #22b06b 0%, #16a34a 100%) !important;
        color: #fff !important;
    }

    .pro-search-wrap .pro-partition-details {
        display: block;
        font-size: 11.5px;
        color: #6b7fa3;
        margin-top: 2px;
    }

    .pro-search-wrap #ajax_pagination_in_search {
        margin-top: 10px;
    }

    .pro-search-wrap #ajax_pagination_in_search .pagination {
        gap: 4px;
    }

    .pro-search-wrap #ajax_pagination_in_search .page-link {
        border-radius: 6px !important;
        border: 1px solid var(--ps-border) !important;
        color: var(--ps-navy) !important;
        font-weight: 600;
    }

    .pro-search-wrap #ajax_pagination_in_search .page-item.active .page-link {
        background: var(--ps-navy) !important;
        border-color: var(--ps-navy) !important;
        color: #fff !important;
    }

    .pro-search-wrap .alert-danger {
        border: none !important;
        border-radius: var(--ps-radius) !important;
        box-shadow: var(--ps-shadow);
        font-weight: 600;
    }
</style>

@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;
@endphp
<div class="table-responsive pro-search-wrap">
    <table class="table text-md-nowrap  text-center our-table" id="SearchProductTable" width="100%">
        <col style="width:2%">
        <col style="width:10%">
        <col style="width:31%">
        <col style="width:10%">
        <col style="width:7%">
        <col style="width:9%">
        <col style="width:6%">
        <col style="width:20%">

        <thead>
            <tr>
                <th class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                <th class="border-bottom-0">{{ __('home.sallerName') }} </th>
                <th class="border-bottom-0">{{ __('home.clietName') }}</th>
                <th class="border-bottom-0">{{ __('home.date') }}</th>
                <th class="border-bottom-0">{{ __('home.branch') }}</th>
                <th class="border-bottom-0">{{ __('home.total') }}</th>
                <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                <th class="border-bottom-0">{{ __('home.operations') }}</th>


            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>

            @foreach ($data as $product)
            <?php $i++; ?>

            <tr id="<?php echo $product['id']; ?>">
                <td data-target="id">{{ $product->id }}</td>
                <td data-target="id">{{ $product->user->name??'' }}</td>
                <td dir="ltr" data-target="id">
                    {{ $product->customer->name??'' }}
                </td>
                <td data-target="numberofpice">{{ $product->created_at }}</td>
                <td data-target="numberofpice">{{ $product->branch->name }}
                </td>
                <td data-target="numberofpice">
                    <?php
                    $avt = App\Models\Avt::find(1);
                    $saleavt = $avt->AVT;

                    ?>
                    {{ round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)==0?__('home.return'):round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)  }}
                </td>

                <?php
                $pay = '';
                if ($product->Pay == 'Cash') {
                    $pay = __('report.cash');
                } elseif ($product->Pay == 'Shabka') {
                    $pay = __('report.shabka');
                } elseif ($product->Pay == "Credit") {
                    $pay = __('report.credit');
                } elseif ($product->Pay == "Bank_transfer") {
                    $pay = __('home.Bank_transfer');
                } else {
                    $pay = __('home.Partition of the amount');
                }

                ?>
                <td>{{$pay}}

                    @if($product->Pay =="Partition")
                    <span class="pro-partition-details">
                    {{__('report.cash')}} : {{round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)==0?__('home.return'):round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)-$product->bankamount-$product->Bank_transfer }}
                    {{__('report.shabka')}} : {{$product->bankamount}}
                    {{__('home.Bank_transfer')}} : {{$product->Bank_transfer}}
                    </span>
                    @endif

                </td>

                <td>
                    <div class="pro-actions-cell">
                    <a class="dropdown-item pro-action-show" href="showInvoiceRecent/{{ $product->id }}"><i class="fas fa-print"></i>
                        {{ __('home.show') }}
                    </a>



     @if($product->sent_to_zatca_status=='PASS')
                        <a class="modal-effect btn btn-sm pro-action-zatca pro-zatca-pass" data-effect="effect-scale"   href="dwonloadxml/{{ $product->id }}" target="_blank" >{{ __('home.zatcapass') }}&nbsp;<i class="fa-solid fa-download"></i></i></a>

                        @else
                        <a id='sendnowzatca' class="modal-effect btn btn-sm pro-action-zatca pro-zatca-upload" data-effect="effect-scale" data-id="{{ $product->id }}" data-toggle="modal" href="#uploadzatca" title="تعديل بيانات العميل ">{{ __('home.uploadzatca') }}&nbsp;<i class="fa-regular fa-paper-plane"></i></a>


                        @endif
                    </div>
                </td>

            </tr>
            @endforeach
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