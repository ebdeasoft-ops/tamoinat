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

    <style>
        .report-card {
            border: none;
            border-radius: 14px !important;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .report-card .card-header {
            background: linear-gradient(135deg, #2e3d50 0%, #3f5872 100%);
            border-radius: 0 !important;
            padding: 22px 26px 26px;
        }

        .report-card .card-header h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .report-card .card-header small {
            color: rgba(255, 255, 255, 0.7);
        }

        .report-card .card-body {
            padding: 28px 26px;
            background: #fff;
        }

        .filter-label {
            font-weight: 600;
            font-size: 13px;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .filter-group .input-group-text {
            background: #f1f3f6;
            border-right: none;
            color: #6c7a89;
        }

        .filter-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0 !important;
        }

        .filter-group .input-group-text {
            border-radius: 8px 0 0 8px !important;
        }

        select.form-control {
            border-radius: 8px !important;
        }

        .btn-search {
            background: linear-gradient(135deg, #28a745, #1e8a37);
            border: none;
            border-radius: 30px;
            padding: 10px 38px;
            font-weight: 600;
            letter-spacing: .3px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.35);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .btn-search:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.45);
        }
    </style>
@stop

@section('title')
    {{ __('report.transction_day') }}
@endsection



@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<br>
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card report-card mg-b-20">

                <div class="card-header">
                    <h5><i class="fas fa-file-invoice-dollar mr-2"></i>{{ __('report.transction_day') }}</h5>
                    <small>{{ __('home.transction_day') }}</small>
                </div>

                <div class="card-body">
                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . 'budgetsheet') }}"
                        method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-lg-4 col-md-6 mb-3" id="start_at">
                                <label class="filter-label" for="start_at_input">{{ __('report.fromdate') }}</label>
                                <div class="input-group filter-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input id="start_at_input" class="form-control fc-datepicker"
                                        value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD"
                                        type="text" required>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 mb-3" id="end_at">
                                <label class="filter-label" for="end_at_input">{{ __('report.todate') }}</label>
                                <div class="input-group filter-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input id="end_at_input" class="form-control fc-datepicker" name="end_at"
                                        value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 mb-3" id="type">
                                <label class="filter-label">{{ __('users.branch') }}</label>
                                <select class="form-control" name="branch" required>
                                    @if (Auth()->user()->branchs_id == 1)
                                        <option value="-" selected>{{ __('users.allbranchs') }}</option>
                                    @endif
                                    @foreach (App\Models\branchs::get() as $branch)
                                        @if (Auth()->user()->branchs_id == 1 || Auth()->user()->branchs_id == $branch->id)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-success btn-search text-white" type="submit">
                                {{ __('home.search') }}
                                <i class="las la-search ml-1"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Internal Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!-- Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!-- Internal spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Ion.rangeSlider.min js -->
    <script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!-- Internal jquery-simple-datetimepicker js -->
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!-- Internal pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    </script>

    <script>
        $(document).ready(function() {

            $('.alert').delay(4000).fadeOut(500);

            $('select[name="clientnamesearch"]').on('change', function() {
                var selectclientid = $(this).val();
                if (selectclientid) {
                    $.ajax({
                        url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#clientName').val(data['name']);
                            $('#address').val(data['address']);
                            $('#phonenumber').val(data['phone']);
                            $('#notes').val(data['notes']);
                        },
                    });
                }
            });

            $('select[name="searchproductNo"]').on('change', function() {
                var selectproductid = $(this).val();
                if (selectproductid) {
                    $.ajax({
                        url: "{{ URL::to('getproduct') }}/" + selectproductid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#quentity').val(data['numberofpice']);
                        },
                    });
                }
            });

            $('#invoice_number').hide();

            $('input[type="radio"]').click(function() {
                if ($(this).attr('id') == 'type_div') {
                    $('#invoice_number').hide();
                    $('#type').show();
                    $('#start_at').show();
                    $('#end_at').show();
                } else {
                    $('#invoice_number').show();
                    $('#type').hide();
                    $('#start_at').hide();
                    $('#end_at').hide();
                }
            });

        });
    </script>
@endsection