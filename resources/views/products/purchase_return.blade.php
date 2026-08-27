@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
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

    <style>
        :root {
            --pw-navy: #1b3358;
            --pw-navy-light: #23395D;
            --pw-border: #e3e7ee;
            --pw-radius: 10px;
            --pw-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* ---------- هيدر الصفحة ---------- */
        .main-parent .breadcrumb-header.parent-heading {
            background: linear-gradient(135deg, var(--pw-navy) 0%, var(--pw-navy-light) 100%) !important;
            border: none !important;
            border-radius: 14px !important;
            padding: 24px 26px !important;
            min-height: 90px !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: 0 8px 20px -8px rgba(27, 51, 88, .45) !important;
            margin-bottom: 18px;
        }

        .main-parent .content-title {
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800 !important;
            font-size: 20px !important;
        }

        .main-parent .content-title i {
            font-size: 20px;
        }

        /* ---------- كارت البحث برقم الفاتورة ---------- */
        .pw-search-card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: var(--pw-shadow) !important;
        }

        .pw-search-card .parent-label {
            color: var(--pw-navy) !important;
            font-weight: 700 !important;
        }

        .pw-search-card .form-control {
            border-radius: 8px !important;
            border: 1px solid var(--pw-border) !important;
            height: 42px;
        }

        .pw-search-card .form-control:focus {
            border-color: var(--pw-navy-light) !important;
            box-shadow: 0 0 0 2px rgba(35, 57, 93, 0.25);
        }

        .pw-search-card .btn-success {
            background: linear-gradient(135deg, #23a35e 0%, #198a4c 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 700 !important;
        }

        /* ---------- بيانات الفاتورة والمورد كبطاقات معلومات ---------- */
        .pw-info-tile {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f8fafc;
            border: 1px solid var(--pw-border);
            border-radius: var(--pw-radius);
            padding: 14px 16px;
            height: 100%;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .pw-info-tile:hover {
            transform: translateY(-2px);
            box-shadow: var(--pw-shadow);
        }

        .pw-info-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--pw-navy) 0%, var(--pw-navy-light) 100%);
            color: #fff;
            font-size: 17px;
        }

        .pw-info-text {
            min-width: 0;
        }

        .pw-info-label {
            display: block;
            font-size: 12.5px;
            color: #8792a2;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .pw-info-value {
            display: block;
            font-size: 15px;
            color: var(--pw-navy);
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ---------- جداول المنتجات والإجمالي ---------- */
        table.invoice-table thead th,
        #tableTotalPrice thead th {
            background: linear-gradient(135deg, var(--pw-navy) 0%, var(--pw-navy-light) 100%) !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-color: var(--pw-navy) !important;
        }

        #tableTotalPrice tbody td {
            font-weight: 800 !important;
            color: #198a4c;
        }

        .card-title.mg-b-0 {
            color: var(--pw-navy) !important;
            font-weight: 800 !important;
        }

        /* ---------- الأزرار ---------- */
        .print-style.btn-success {
            background: linear-gradient(135deg, #23a35e 0%, #198a4c 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 700 !important;
            box-shadow: var(--pw-shadow);
        }

        a.btn-danger {
            background: linear-gradient(135deg, #e0396d 0%, #c22a58 100%) !important;
            border: none !important;
            border-radius: 24px !important;
            font-weight: 700 !important;
            box-shadow: var(--pw-shadow);
        }

        /* ---------- المودالات ---------- */
        .modal-content-demo .modal-header,
        #exampleModal2 .modal-header {
            background: linear-gradient(135deg, var(--pw-navy) 0%, var(--pw-navy-light) 100%) !important;
            border-bottom: none !important;
        }

        .modal-content-demo .modal-title,
        #exampleModal2 .modal-title {
            color: #fff !important;
            font-weight: 700 !important;
        }

        #exampleModal2 .close {
            color: #fff;
            opacity: .85;
            text-shadow: none;
        }
    </style>
@section('title')
    {{ __('home.purchase_return') }}@stop
@endsection
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto"><i class="fas fa-undo-alt"></i> {{ __('home.purchase_return') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0">
                    </span>
                </div>
            </div>
        </div>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                proErrorAlert(`{!! implode('<br>', $errors->all()) !!}`);
            });
        </script>
    @endif
    @if (session()->has('delete'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                proErrorAlert(@json(session()->get('delete')));
            });
        </script>
    @endif
    @if (session()->has('notfountreturnpuracheseproduct'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                proWarningAlert(@json(session()->get('notfountreturnpuracheseproduct')));
            });
        </script>
    @endif
    @if (session()->has('editpurchase'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                proSuccessAlert(@json(session()->get('editpurchase')));
            });
        </script>
    @endif
<!-- breadcrumb -->
@endsection
@section('content')
@if (count($errors) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            proErrorAlert(`{!! implode('<br>', $errors->all()) !!}`);
        });
    </script>
@endif
<!-- row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20 pw-search-card">
            <div class="card-header pb-0">

                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">
  <form
                    action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'Purchase_returns_Data')) }}"
                    method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row mb-3">
                        <div class="col-lg-5">
                            <label for="inputName" class="control-label parent-label"><i class="fas fa-file-invoice ml-1"></i> {{ __('home.enterinvoicenumber') }}</label>
                            <input type="number" class="form-control parent-input" id="clientName" name="clientName" required>
                        </div>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col">

                            <button type="submit" class="btn btn-success p-1 print-style">{{ __('home.search') }}
                                <svg style="width: 22px !important;fill:white !important;height:22px !important" class="svg-icon m-0" viewBox="0 0 20 20">
                                    <path fill="white" style="fill: white !important" d="M12.323,2.398c-0.741-0.312-1.523-0.472-2.319-0.472c-2.394,0-4.544,1.423-5.476,3.625C3.907,7.013,3.896,8.629,4.49,10.102c0.528,1.304,1.494,2.333,2.72,2.99L5.467,17.33c-0.113,0.273,0.018,0.59,0.292,0.703c0.068,0.027,0.137,0.041,0.206,0.041c0.211,0,0.412-0.127,0.498-0.334l1.74-4.23c0.583,0.186,1.18,0.309,1.795,0.309c2.394,0,4.544-1.424,5.478-3.629C16.755,7.173,15.342,3.68,12.323,2.398z M14.488,9.77c-0.769,1.807-2.529,2.975-4.49,2.975c-0.651,0-1.291-0.131-1.897-0.387c-0.002-0.004-0.002-0.004-0.002-0.004c-0.003,0-0.003,0-0.003,0s0,0,0,0c-1.195-0.508-2.121-1.452-2.607-2.656c-0.489-1.205-0.477-2.53,0.03-3.727c0.764-1.805,2.525-2.969,4.487-2.969c0.651,0,1.292,0.129,1.898,0.386C14.374,4.438,15.533,7.3,14.488,9.77z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    @if (isset($data['product']))
        <?php $i = 0; ?>
        <div class="col-xl-12">
            <div style="border-radius: 10px" class="card mg-b-20">
                <div class="card-header pb-0">
                    <br />
                    <div class="row">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="pw-info-tile">
                                <div class="pw-info-icon"><i class="fas fa-truck"></i></div>
                                <div class="pw-info-text">
                                    <span class="pw-info-label">{{ __('home.suppliername') }}</span>
                                    <span class="pw-info-value">{{ $data['supllier']->supllier->comp_name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="pw-info-tile">
                                <div class="pw-info-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="pw-info-text">
                                    <span class="pw-info-label">{{ __('home.phone') }}</span>
                                    <span class="pw-info-value">{{ $data['supllier']->supllier->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="pw-info-tile">
                                <div class="pw-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="pw-info-text">
                                    <span class="pw-info-label">{{ __('home.Location') }}</span>
                                    <span class="pw-info-value">{{ $data['supllier']->supllier->location ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                            <input  name="returnAllpurchase_id" id="returnAllpurchase_id"  value="{{$data['resource_purchases']->orderId}}" hidden>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="pw-info-tile">
                                <div class="pw-info-icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="pw-info-text">
                                    <span class="pw-info-label">{{ __('home.purchasedate') }}</span>
                                    <span class="pw-info-value">{{ $data['supllier']->created_at ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <?php

                             $pay='';
                             if($data['supllier']->Limit_credit=="Cash"){
                                 $pay= __('report.cash') ;
                             }
                             elseif($data['supllier']->Limit_credit=="Shabka"){
                                $pay=__('report.shabka');
                            } elseif($data['supllier']->Limit_credit=="Bank_transfer"){
                                $pay=__('home.Bank_transfer');
                            }else{
                                 $pay=__('report.credit');
                             }
                                ?>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="pw-info-tile">
                                <div class="pw-info-icon"><i class="fas fa-wallet"></i></div>
                                <div class="pw-info-text">
                                    <span class="pw-info-label">{{ __('home.paymentmethod') }}</span>
                                    <span class="pw-info-value">{{ $pay ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h4 class="card-title mg-b-0">{{ __('home.purchases') }}</h4>
                </div>
                <div id="response_div">
                        <div class="table-responsive mg-t-40 " style="width:95%,margin:20px">
                    <table style="border:2px solid rgba(0,0,0,.3)" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                         <thead>
                                <tr>
                                    <th style="vertical-align: middle" class="border-bottom-0"># </th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.productNo') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.product') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('users.branch') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.quantity') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.purchase') }} </th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.total') }} </th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.RETURNSPURCHAE') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $totalprice = 0;
                                $totalAddedvalue = 0;
                                $orderId=0; ?>
                                @foreach ($data['product'] as $product)
                                    <?php $i++;
                                     if($i==1){
                                        $orderId=$product->order_owner;
                                    }
                                    $totalprice += $product->purchasingـprice * $product->numberofpice;
                                    $totalAddedvalue += $product->Added_Value * $product->numberofpice; ?>
                                    <tr>
                                        @if($product->numberofpice!=0)
                                        <td style="vertical-align: middle">{{ $i }}</td>
                                        <td style="vertical-align: middle" dir=ltr>{{ $product->productData->Product_Code }}</td>
                                        <td style="vertical-align: middle">{{ $product->product_name }}</td>
                                        <td style="vertical-align: middle">{{ $data['branch'] }}</td>
                                        <td style="vertical-align: middle">{{ $product->numberofpice }}</td>
                                        <td style="vertical-align: middle">{{ $product->purchasingـprice }}</td>
                                        <td style="vertical-align: middle">{{ $product->Added_Value }}</td>
                                        <td style="vertical-align: middle">{{ ($product->Added_Value + $product->purchasingـprice) * $product->numberofpice }}
                                        </td>
                                        <td style="vertical-align: middle">{{ $product->returns_purchase }}</td>
                                        <td style="vertical-align: middle">
                                            <a style="background-color: #1b3358" class="modal-effect btn btn-sm btn-info mb-1" data-effect="effect-scale"
                                                data-id="{{ $product->productData->id }}"
                                                data-section_name="{{ $product->product_name }}"
                                                data-ordernumber="{{ $data['supllier']->id }}"
                                                data-description="{{ $product->numberofpice }}" data-toggle="modal"
                                                href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>

                                        </td>
                                        @endif
                                    <tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="table-responsive mg-t-30 table-padding">
                            <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped" id="tableTotalPrice"
                                name="tableTotalPrice"width="50%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:20%">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                                                                <th class="border-bottom-0">{{ __('home.discount') }}</th>
                                        <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                        <th class="border-bottom-0">{{ __('home.total') }} </th>
                                    </tr>
                                </thead>
                                <body>
                                    <tr>
                                        <td> {{ $totalprice }}</td>
                                        <td>{{$data['resource_purchases']->discount}}</td>
<?php
$totalAddedvalue=$data['resource_purchases']->In_debt-($totalprice-$data['resource_purchases']->discount);
?>
                                        <td>{{ $totalAddedvalue }}</td>
                                        <td>{{ $data['resource_purchases']->In_debt}}</td>
                                    </tr>
                                </body>
                            </table>
                            </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-3 flex-wrap">
                                    <a class="btn btn-success print-style p-1 mx-1" href="{{ url('/' . ($page = 'printReturnpurchases').'/'.$orderId) }}">
                                        {{__('home.print')}}
                                        <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
                                    </a>
                                    <a class="btn btn-danger p-1 mx-1" data-effect="effect-scale"
                                       data-toggle="modal"
                                       href="#paymentmethod111" title="تعديل"><i class="fas fa-undo-alt ml-1"></i>{{ __('home.returninvoiceItem') }}</a>
                            </div>
                            </form>
                            <br>
                        </div>
                </div>
            </div>
            <br />
        </div>
        <div class="row">
    @endif
    </table>
</div>
</div>
</div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<div class="modal" id="paymentmethod111">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.alert') }} </h6>
            </div>
                <div class="modal-body">
                    <input type="number" class="form-control " name="recentretrn" id="recentretrn" value='0' required=true hidden>
                    <div class="col">
                        <span style="color:red">{{__('home.Are_you_sure')}}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    <button id="returnAll" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>
        </div>
        </div>
    </div>
</div>
<!-- edit -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.RETURNSPURCHASEpart') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                    method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="ordernumber" id="ordernumber" value="">
                        <label for="recipient-name" class="col-form-label"> {{ __('home.product') }} </label>
                        <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text"
                            class="col-form-label">{{ __('home.numberofpicereturens') }}</label>
                        <input class="form-control" id="return_quentity" name="return_quentity" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button  class="btn btn-primary" id="button_1" data-dismiss="modal">{{ __('home.confirm') }}</button>
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- main-content closed -->
    </div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--Internal  Datepicker js -->
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
    <script>
        function proSuccessAlert(msg) {
            Swal.fire({ icon: 'success', title: msg, confirmButtonColor: '#1b3358' });
        }
        function proErrorAlert(msg) {
            Swal.fire({ icon: 'error', title: msg, confirmButtonColor: '#1b3358' });
        }
        function proWarningAlert(msg) {
            Swal.fire({ icon: 'warning', title: msg, confirmButtonColor: '#1b3358' });
        }

        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>
    <script>


        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var ordernumber = button.data('ordernumber')
            var section_name = button.data('section_name')
            var description = button.data('description')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #ordernumber').val(ordernumber);
            modal.find('.modal-body #product_name').val(section_name);
            modal.find('.modal-body #return_quentity').val(description);
        })
    </script>
    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var ordernumber = button.data('ordernumber')
            var description = button.data('description')
            var section_name = button.data('section_name')
            var modal = $(this)
            modal.find('.modal-body #ordernumber').val(ordernumber);
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #description').val(description);
            modal.find('.modal-body #product_name').val(section_name);
        })
    </script>
    <script>
        $(document).ready(function() {
            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
            $('select[name="clientNosearch"]').on('change', function() {
                console.log('AJAX load   work 0000');
                var selectclientid = $(this).val();
                if (selectclientid) {
                    console.log('AJAX load   work');
                    $.ajax({
                        url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                            console.log(data['name']);
                            $('#clientName').val(data['name']);
                            $('#address').val(data['location']);
                            $('#phonenumber').val(data['phone']);
                            $('#notes').val(data['comp_name']);
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
            $('select[name="clientnamesearch"]').on('change', function() {
                console.log('AJAX load   work 0000');
                var selectclientid = $(this).val();
                if (selectclientid) {
                    console.log('AJAX load   work');
                    $.ajax({
                        url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                            console.log(data['name']);
                            $('#clientName').val(data['name']);
                            $('#address').val(data['location']);
                            $('#phonenumber').val(data['phone']);
                            $('#notes').val(data['comp_name']);
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });
        $('select[name="productNo"]').on('change', function() {
            console.log('AJAX load   work 0000');
            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');
                $.ajax({
                    url: "{{ URL::to('getproduct') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success123");
                        console.log(data);
                        console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                        $('#productnameshow').val(data['product_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
        $('select[name="productname"]').on('change', function() {
            console.log('AJAX load   work 0000');
            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');
                $.ajax({
                    url: "{{ URL::to('getproduct') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success123");
                        console.log(data);
                        console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                        $('#productnameshow').val(data['product_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    </script>
    <script>

       // 1. حدث تعديل كمية المنتج الفردي
$("#button_1").click(function(e) {
    e.preventDefault(); // إصلاح الخطأ باستخدام المتغير ممرر المعامل e
    var $btn = $(this);
    var url = "{{ URL::to('purchaseproduct_update') }}";
    var token_search = $("#token_search").val();
    // تعطيل الزر مؤقتاً لحماية قاعدة البيانات من النقرات المتكررة
    $btn.prop('disabled', true);
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        cache: false,
        data: {
            _token: token_search,
            return_quentity: $('#return_quentity').val(),
            ordernumber: $('#ordernumber').val(),
            id: $('#id').val()
        },
        success: function(data) {
            $("#response_div").html(data);
            // تنبيه نجاح احترافي بـ SweetAlert
            Swal.fire({
                title: 'تم التعديل بنجاح',
                text: 'Has been modified successfully',
                icon: 'success',
                confirmButtonText: 'موافق',
                confirmButtonColor: '#3085d6'
            });
        },
        error: function(response) {
            console.log(response);

            Swal.fire({
                title: 'خطأ / Error',
                text: "{{ __('home.sorryerror') }}",
                icon: 'error',
                confirmButtonText: 'إغلاق',
                confirmButtonColor: '#d33'
            });
        },
        complete: function() {
            // إعادة تفعيل الزر بعد انتهاء الطلب
            $btn.prop('disabled', false);
        }
    });
});
// 2. حدث إرجاع الفاتورة بالكامل
$("#returnAll").click(function(e) {
    e.preventDefault(); // إصلاح منع تحديث الصفحة المباشر هنا أيضاً
    var $btnAll = $(this);
    var url = "{{ URL::to('returnAllpurchase') }}";
    var token_search = $('#token_search').val();
    // التحقق من شرطك المخصص لمنع التكرار المالي
    if ($('#recentretrn').val() == 0) {
        $('#recentretrn').val(1);

        // إخفاء الزر لتجنب نقره مجدداً أثناء المعالجة
        $btnAll.css('visibility', 'hidden');
        $.ajax({
            url: url,
            type: 'post',
            cache: false,
            data: {
                _token: token_search,
                ordernumber: $('#returnAllpurchase_id').val()
            },
            success: function(data) {
                $("#response_div").html(data);
                Swal.fire({
                    title: 'تم إرجاع الكل بنجاح',
                    text: 'All items have been returned successfully',
                    icon: 'success',
                    confirmButtonText: 'موافق',
                    confirmButtonColor: '#28a745'
                });
            },
            error: function(response) {
                console.log(response);
                // إعادة الزر للظهور وتصفير الشرط في حال الفشل لإعطاء المستخدم فرصة أخرى
                $btnAll.css('visibility', 'visible');
                $('#recentretrn').val(0);
                Swal.fire({
                    title: 'خطأ / Error',
                    text: "{{ __('home.sorryerror') }}",
                    icon: 'error',
                    confirmButtonText: 'إغلاق',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }
});
    </script>
@endsection