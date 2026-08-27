<style>
    /* ==========================================================================
       جدول رصيد الموردين — تصميم احترافي (Scoped)
       ========================================================================== */
    .pro-supp-wrap {
        --ps-navy: #1b3358;
        --ps-navy-light: #23395D;
        --ps-border: #e3e7ee;
        --ps-radius: 10px;
        --ps-shadow: 0 1px 3px rgba(16, 24, 40, .06);
    }

    .pro-supp-wrap .our-table {
        border: 1px solid var(--ps-border) !important;
        border-radius: var(--ps-radius);
        overflow: hidden;
        box-shadow: var(--ps-shadow);
    }

    .pro-supp-wrap .our-table thead th {
        background: linear-gradient(135deg, var(--ps-navy) 0%, var(--ps-navy-light) 100%) !important;
        color: #fff !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border: none !important;
        padding: 12px 8px;
        vertical-align: middle;
    }

    .pro-supp-wrap .our-table tbody td {
        border-color: var(--ps-border) !important;
        vertical-align: middle;
        padding: 10px 8px;
    }

    .pro-supp-wrap .our-table tbody tr:nth-child(even) {
        background: #fafbfd;
    }

    .pro-supp-wrap .our-table tbody tr:hover {
        background: #eef4ff;
    }

    .pro-supp-wrap .pro-supp-balance {
        color: var(--ps-navy) !important;
        font-weight: 800 !important;
        font-size: 14px;
    }

    .pro-supp-wrap .pro-action-show {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: linear-gradient(135deg, var(--ps-navy) 0%, var(--ps-navy-light) 100%) !important;
        border: none !important;
        border-radius: 20px !important;
        padding: 6px 18px !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
        color: #fff !important;
        box-shadow: var(--ps-shadow);
        transition: transform .15s ease, filter .15s ease;
    }

    .pro-supp-wrap .pro-action-show:hover {
        transform: translateY(-2px);
        filter: brightness(1.08);
    }

    .pro-supp-wrap .pro-action-show svg {
        width: 15px;
        height: 15px;
    }

    .pro-supp-wrap #ajax_pagination_in_search .pagination {
        gap: 4px;
    }

    .pro-supp-wrap #ajax_pagination_in_search .page-link {
        border-radius: 6px !important;
        border: 1px solid var(--ps-border) !important;
        color: var(--ps-navy) !important;
        font-weight: 600;
    }

    .pro-supp-wrap #ajax_pagination_in_search .page-item.active .page-link {
        background: var(--ps-navy) !important;
        border-color: var(--ps-navy) !important;
        color: #fff !important;
    }

    .pro-supp-wrap .alert-danger {
        border: none !important;
        border-radius: var(--ps-radius) !important;
        box-shadow: var(--ps-shadow);
        font-weight: 600;
    }
</style>

@if (isset($data) && !$data->isEmpty() && count($data) > 0)
    <div class="table-responsive pro-supp-wrap">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" style="width: 100%;">
            <colgroup>
                <col style="width: 5%">
                <col style="width: 40%">
                <col style="width: 30%">
                <col style="width: 25%">
            </colgroup>

            <thead>
                <tr>
                    <th class="border-bottom-0">#</th>
                    <th class="border-bottom-0">{{ __('home.clietName') }}</th>
                    <th class="border-bottom-0">{{ __('home.total') }}</th>
                    <th class="border-bottom-0">{{ __('home.operations') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $index => $product)
                    <tr id="{{ $product['id'] ?? $product->id }}">
                        <td dir="ltr" data-target="id">{{ $index + 1 }}</td>
                        <td dir="ltr">{{ $product->supllier->name ?? '' }}</td>
                        <td data-target="numberofpice" class="pro-supp-balance">{{ round($product->blance, 2) }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                @php
                                    $actionUrl = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/print_delivery_to_anoter_supplier';
                                @endphp
                                <form action="{{ url($actionUrl) }}" method="POST" autocomplete="off" class="m-0">
                                    @csrf
                                    <input type="hidden" name="OrderNoprint" id="OrderNoprint" value="{{ $product->to_dlivery_id }}">

                                    <button type="submit" class="btn btn-sm pro-action-show">
                                        {{ __('home.show') }}
                                        <svg style="fill: currentColor;" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-start mt-3" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>
    </div>
@else
    <div class="alert alert-danger mt-3">
        {{ __('home.notfounddata') }}
    </div>
@endif