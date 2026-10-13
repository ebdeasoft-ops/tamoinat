{{--
    جزء مشترك لتقارير المبيعات حسب طريقة الدفع (نقدي / شبكة / آجل)
    المتغيرات: $reportTitle (عنوان التقرير)، $Invoices (نتائج البحث - اختياري)
--}}
@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

@section('title')
    {{ $reportTitle }}
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ $reportTitle }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ app()->getLocale() == 'ar' ? 'التقارير' : 'Reports' }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @php
        $isAr = app()->getLocale() == 'ar';
        $searched = request()->filled('start_at') && request()->filled('end_at');
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">&times;</span></button>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm mg-b-20" style="border-radius: 12px; border: none;">
                <div class="card-header bg-transparent pb-3 pt-4 border-bottom-0">

                    <!-- نموذج البحث -->
                    <form action="{{ url()->current() }}" method="GET" role="search" autocomplete="off">
                        <div class="row align-items-end">
                            <div class="col-lg-5 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="start_at" value="{{ request('start_at') }}"
                                           placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>
                            <div class="col-lg-5 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ request('end_at') }}"
                                           placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>
                            <div class="col-lg-2 mg-t-10">
                                <button type="submit" class="btn btn-primary btn-block shadow-sm" style="border-radius: 8px;">
                                    <i class="las la-search ml-1"></i> {{ $isAr ? 'بحث' : 'Search' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($searched && isset($Invoices))
                    @php
                        $avt = App\Models\Avt::find(1);
                        $saleavt = $avt ? $avt->AVT : 0;
                        $totalprice = 0;
                        $totaldiscount = 0;
                        $totaltax = 0;
                    @endphp

                    <div class="card-body pt-0">
                        <div class="table-responsive hoverable-table mt-3">
                            <table class="table text-md-nowrap table-hover border-top-0" id="example1" data-page-length='50' style="text-align: center;">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.Invoice_no') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.sallerName') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.clietName') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ $isAr ? 'الخصم' : 'Discount' }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.total') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Invoices as $invoice)
                                        @php
                                            $net = $invoice->Price - $invoice->discount;
                                            $tax = $net * $saleavt;
                                            $totalprice += $net;
                                            $totaldiscount += $invoice->discount;
                                            $totaltax += $tax;
                                        @endphp
                                        <tr>
                                            <td>{{ $invoice->id }}</td>
                                            <td>{{ $invoice->user->name ?? '-' }}</td>
                                            <td>{{ $invoice->customer->name ?? '-' }}</td>
                                            <td>{{ $invoice->created_at }}</td>
                                            <td>{{ $invoice->branch->name ?? '-' }}</td>
                                            <td class="text-danger">{{ round($invoice->discount, 2) }}</td>
                                            <td class="font-weight-bold text-success">{{ round($net + $tax, 2) }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ url('showInvoiceRecent/' . $invoice->id) }}">
                                                    <i class="fas fa-eye ml-1"></i> {{ $isAr ? 'عرض' : 'Show' }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- الإجماليات -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped text-center bg-light">
                                    <tbody>
                                        <tr>
                                            <td>{{ $isAr ? 'عدد الفواتير' : 'Invoices count' }}</td>
                                            <td class="font-weight-bold">{{ count($Invoices) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $isAr ? 'إجمالي الخصم' : 'Total discount' }}</td>
                                            <td class="text-danger font-weight-bold">{{ round($totaldiscount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $isAr ? 'الإجمالي بدون ضريبة' : 'Total before tax' }}</td>
                                            <td>{{ round($totalprice, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('report.totaltax') }}</td>
                                            <td>{{ round($totaltax, 2) }}</td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <td>{{ $isAr ? 'الإجمالي شامل الضريبة' : 'Grand total (incl. tax)' }}</td>
                                            <td class="text-success" style="font-size: 16px;">{{ round($totalprice + $totaltax, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-2 mb-3 d-print-none">
                            <button onclick="window.print()" class="btn btn-info px-4 py-2 shadow-sm" style="background-color: #419BB2; border-radius: 8px;">
                                <i class="fas fa-print ml-2"></i> {{ $isAr ? 'طباعة' : 'Print' }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script>
        $(function () {
            $('.fc-datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
        });
    </script>
@endsection
