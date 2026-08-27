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

@section('title')
{{ __('home.voucher') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.voucher') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    @if (session()->has('nodataprint'))
    <div class="alert alert-warning  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ __('home.nodataprint') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form name="form-name" action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'Credittransactions')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}


                        <input type="hidden" id="token_search" value="{{ csrf_token() }}">


                        <div class="row">
                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10">{{__('home.searchbyclientname')}} </p>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch" required>
                                    <option value="-" selected>
                                        {{ $type ?? __('home.enterclienname') }}
                                    </option>

                                    @foreach ($data['allcustomers'] as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->




                            <div class="col-lg-3">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.phone') }}</label>
                                <input type="text" class="parent-input form-control" id="phonenumber" name="phonenumber" title="يرجي ادخال رقم الجوال " required>
                            </div>











                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.paymentmethod') }} </p>
                                <select class="form-control parent-input " name="pay" id="pay" required>
                                    <option value="Cash"> {{ __('report.cash') }}</option>
                                    <option value="Shabka "> {{ __('report.shabka') }} </option>
                                    <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>


                                </select>
                            </div>









                            <div class="col-lg-3">
                                <label for="inputName" class="control-label parent-label"> {{ __('accountes.cashreceived') }}
                                </label>
                                <input type="text" class="form-control parent-input" id="cashreceived" name="cashreceived" value=0 title="يرجي ادخال الكمية  " required onkeyup="moneyconvertToNumber()">
                            </div>


                        </div>
                        <div class="row">

                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.creditlimit') }} </label>
                                <input type="number" class="parent-input form-control" id="creditlimit" name="creditlimit" title="يرجي ادخال الكمية  " value="{{ $data['customer']->Limit_credit ?? '0' }}" readonly>
                            </div>
                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.current balance') }} </label>
                                <input type="number" class="parent-input form-control" id="balance" name="balance" title="يرجي ادخال الكمية  " value="{{ $data['customer']->Balance ?? '0' }}" readonly>
                            </div>

                            <div class="col-lg-8">
                                <label for="inputName" class="control-label parent-label">{{ __('home.notesClient') }} </label>
                                <input type="text" class="parent-input form-control" id="notes" name="notes" title="يرجي ادخال ملاحظات  ">
                            </div>



                            <br>

                        </div>
                        <br>
                    </form>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success print-style p-1" id="button_1">
                            {{ __('home.savedecoument') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                        <br>

                    </div>
                    <br>

                </div>








            </div>

        </div>
        <?php
        ?>
        <div class="col-xl-12">
            <div style="border-radius: 10px" class="card mg-b-20">
                <div class="card-header pb-0 p-4">
                    <div class="d-flex justify-content-between">
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table text-md-nowrap text-center our-table" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>

                                    <th class="border-bottom-0"> {{ __('home.clientname') }}</th>
                                    <th class="border-bottom-0">{{ __('accountes.limitCredit') }}</th>
                                    <th class="border-bottom-0">{{ __('accountes.cashreceived') }}</th>
                                    <th class="border-bottom-0">{{ __('accountes.Remainingamount') }}</th>
                                    <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                    <th class="border-bottom-0">{{ __('home.operations') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                            </tbody>
                        </table>
                        <br>

                        <div class="d-flex justify-content-center">


                            <div class="row  d-flex justify-content-end mt-3">
                                <form action="{{ '/' . ($page = 'print_reciept_ducoument') }}" method="POST" role="search" autocomplete="off">
                                    {{ csrf_field() }}



                                    <div class='col ' id="printdiv">
                                        <input type="number" class="form-control " name="id" id="id" title=" رقم الفاتورة " readonly required hidden>


                                        <button style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px" type="submit" class="btn btn-success p-1 px-2 fw-bolder">
                                            {{ __('home.print') }}
                                            <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                            </svg>
                                        </button>





                                    </div>


                                </form>
                            </div>
                            <br>
                        </div>
                        <br />
                    </div>
                </div>
            </div>

        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
</div>

<!-- edit -->
<div class="modal fade" id="increaseProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div style="margin: 5% !important;" class="modal-dialog modal-special" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.updatedecoument') }}</h5>
                <button type="button" class="close choose-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="transactionId" id="transactionId" value="">

                    </div>



            <div class="row">


                <div class="col" id="type">
                    <p class="mg-b-10 parent-label"> {{ __('home.paymentmethod') }} </p>
                    <select class="form-control parent-input " name="payupdate" id="payupdate" required>
                        <option value="Cash"> {{ __('report.cash') }}</option>
                        <option value="Shabka "> {{ __('report.shabka') }} </option>
                        <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>


                    </select>
                </div>

                <div class="col">
                    <label for="inputName" class="control-label parent-label"> {{ __('accountes.cashreceived') }}
                    </label>
                    <input type="text" class="form-control parent-input" id="cashreceivedupdate" name="cashreceivedupdate" value=0 title="يرجي ادخال الكمية  " required onkeyup="moneyconvertToNumber()">
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
            <button id="updatedecoument" name="updatedecoument" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
        </div>

        </form>
    </div>
</div>
</div>
<div class="modal" id="modaldemo9">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.Retrieveall') }} </h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'EditInvoices')) }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>{{ __('home.Are_you_sure') }}</p><br>
                    <input type="hidden" name="id_delete" id="id_delete">
                    <input type="hidden" name="return_quentity_delete" id="return_quentity_delete">
                    <input class="form-control parent-input" name="product_name" id="product_name" type="text" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button id="deleteproduct" name="deleteproduct" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>
        </div>
        </form>
    </div>
</div>

@endsection
@section('js')
<!-- Internal Data tables -->

<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

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
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();


    $('#increaseProduct').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)

        var id = button.data('id')
        var amount = button.data('amount')
        var modal = $(this)



        modal.find('.modal-body #transactionId').val(id);
        modal.find('.modal-body #cashreceivedupdate').val(amount);

    })
</script>



<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

        $("#updatedecoument").click(function(e) {
            console.log($('#notes').val())
            console.log($('#clientnamesearch').val())
            console.log($('#phonenumber').val())
            console.log($('#pay').val())
            console.log($('#cashreceived').val())
            console.log($('#balance').val())
            var url = " {{ URL::to('updateVoncher') }}";
            var token_search = $("#token_search").val();
          if ($('#cashreceivedupdate').val() == 0) {
                alert("{{__('home.should')}}")

            } else {
                cashreceivedupdate=$('#cashreceivedupdate').val()
            $('#cashreceivedupdate').val(0)
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        transactionId: $('#transactionId').val(),
                        payupdate: $('#payupdate').val(),
                        cashreceivedupdate: cashreceivedupdate,
                       


                    },


                    success: function(data) {
                        let table = document.getElementById("example");
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        let row = table.insertRow(-1); // We are adding at the end
                        update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                        update = update.concat(data['id'], '  ', ' data-amount=', data['recive_amount'], '  ',
                            '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        c1.innerText = data['id']
                        c2.innerText = data['name']
                        c3.innerHTML = ' <span dir=ltr style="color:red">' + data['Limit_credit'] + '</span>'
                        c4.innerText = data['recive_amount']
                        c5.innerText = data['Balance']
                        c6.innerText = data['method_pay']
                        c7.innerHTML = update
                        $('#id').val(data['id'])


                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                })




            }



        })

        $("#button_1").click(function(e) {
            console.log($('#notes').val())
            console.log($('#clientnamesearch').val())
            console.log($('#phonenumber').val())
            console.log($('#pay').val())
            console.log($('#cashreceived').val())
            console.log($('#balance').val())
            var url = " {{ URL::to('Credittransactions') }}";
            var token_search = $("#token_search").val();
            if ($('#clientnamesearch').val() == '-') {
                alert("{{__('home.enterclienname')}}")
            } else if ($('#cashreceived').val() == 0) {
                alert("{{__('home.should')}}")

            } else {
                cashreceived=$('#cashreceived').val()
            $('#cashreceived').val(0)

                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        balance: $('#balance').val(),
                        cashreceived: cashreceived,
                        pay: $('#pay').val(),
                        phonenumber: $('#phonenumber').val(),
                        clientnamesearch: $('#clientnamesearch').val(),
                        notes: $('#notes').val() ?? '-',


                    },


                    success: function(data) {
                        let table = document.getElementById("example");
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        let row = table.insertRow(-1); // We are adding at the end
                        update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                        update = update.concat(data['id'], '  ', ' data-amount=', data['recive_amount'], '  ',
                            '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        c1.innerText = data['id']
                        c2.innerText = data['name']
                        c3.innerHTML = ' <span dir=ltr style="color:red">' + data['Limit_credit'] + '</span>'
                        c4.innerText = data['recive_amount']
                        c5.innerText = data['Balance']
                        c6.innerText = data['method_pay']
                        c7.innerHTML = update
                        $('#id').val(data['id'])


                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                })




            }



        })

        $('select[name="clientnamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);

                        $('#clientNosearch').val('0');

                        $('#clientName').val(data['name']);
                        $('#address').val(data['address']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['notes']);

                        $('#creditlimit').val(data['Limit_credit']);
                        $('#balance').val(data['Balance']);

                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });



    $('select[name="clientNosearch"]').on('change', function() {
        console.log('AJAX load   work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');
            console.log(selectclientid);

            $.ajax({
                url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success");
                    console.log(data['name']);
                    $('#clientName').val(data['name']);
                    $('#clientnamesearch').val('');
                    $('#address').val(data['address']);
                    $('#phonenumber').val(data['phone']);
                    $('#notes').val(data['notes']);

                    $('#creditlimit').val(data['Limit_credit']);
                    $('#balance').val(data['Balance']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }

    });
</script>

<script>
    function moneyconvertToNumber() {
        var input = document.getElementById("cashreceived");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>


<script>
    $(document).ready(function() {

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