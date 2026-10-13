@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
    /* شاشة التحميل (ZATCA) */
    #loading-screen {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        color: white;
    }

    #loading-animation {
        border: 6px solid #f3f3f3;
        border-radius: 50%;
        border-top: 6px solid #FF4F1F;
        width: 60px; height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* إطار قسم البحث المخصص */
    .search-box-container {
        border: 2px solid #e1e6f1;
        border-radius: 10px;
        padding: 25px;
        background-color: #f9fbff;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .parent-label {
        font-weight: 600;
        color: #1b2e4b;
        margin-bottom: 8px;
        display: block;
    }

    .our-table thead th {
        background-color: #f1f2f7;
        color: #FF4F1F;
        text-transform: uppercase;
        font-size: 12px;
    }
</style>
@endsection

@section('title')
{{ __('home.previousSalesInvoices') }}
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">{{ __('home.previousSalesInvoices') }}</h4>
    </div>
</div>
@endsection

@section('content')

<div id="loading-screen">
    <div id="loading-animation"></div>
    <h4 style="direction: rtl;">جارٍ معالجة الفاتورة مع هيئة الزكاة...</h4>
    <p>Please wait while communicating with ZATCA</p>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <!-- قسم البحث والفلترة -->
                <div class="search-box-container">
                    <div class="row">
                        <!-- 1. البحث برقم الفاتورة -->
                        <div class="col-md-3 mg-t-10">
                            <label class="parent-label">{{ __('home.enterinvoicenumber') }}</label>
                            <input class="form-control" id="invoiceid_search" placeholder="مثال: 1001" type="text" onkeyup="searchaboutinvoiceByIdfunction()">
                        </div>

                        <!-- 2. فلترة باسم العميل -->
                        <div class="col-md-3 mg-t-10">
                            <label class="parent-label">{{ __('home.chooseclient') }}</label>
                            <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                <option value="">{{ __('home.all_customers') }}</option>
                                @foreach (App\Models\customers::get() as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->id == 1 ? __('home.Cash Custome') : $customer->name }} - {{ $customer->tax_no }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. فلترة بطريقة الدفع (تم نقلها بجانب العميل) -->
                        <div class="col-md-3 mg-t-10">
                            <label class="parent-label">{{ __('home.paymentmethod') }}</label>
                            <select class="form-control select2" name="payment_method_search" id="payment_method_search">
                                <option value="">{{ __('home.paymentmethod') }} (الكل)</option>
                                <option value="Cash">{{ __('report.cash') }}</option>
                                <option value="Shabka">{{ __('report.shabka') }}</option>
                                <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                                <option value="Credit">{{ __('report.credit') }}</option>
                                <option value="Partition">{{ __('home.Partition of the amount') }}</option>
                            </select>
                        </div>

                        <!-- 4. البحث بالتاريخ -->
                        <div class="col-md-3 mg-t-10">
                            <label class="parent-label">{{ __('home.searchbydate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input class="form-control fc-datepicker" id="date_search" placeholder="YYYY-MM-DD" type="text" onchange="searchaboutproductfunction()">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" id="ajax_responce_allinvoicesDiv">
                    <table class="table text-md-nowrap text-center our-table" id="example1">
                        <thead>
                            <tr>
                                <th>{{ __('home.Invoice_no') }}</th>
                                <th>{{ __('home.sallerName') }}</th>
                                <th>{{ __('home.clietName') }}</th>
                                <th>{{ __('home.date') }}</th>
                                <th>{{ __('home.total') }}</th>
                                <th>{{ __('home.operations') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal uploadzatca -->
<div class="modal fade" id="uploadzatca" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.uploadzatca') }}</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="invoiceid_zatca_input">
                <i class="fas fa-cloud-upload-alt fa-3x text-warning mb-3"></i>
                <h5>{{ __('home.confirmzatcasent') }}</h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="btnSentToZatca">{{ __('home.confirm') }}</button>
                <button class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal paymentmethod -->
<div class="modal" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.paymentmethod') }}</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row mg-b-15">
                    <label style="font-size: 16px;"
                        class="control-label parent-label">&nbsp;&nbsp;{{ __('home.total') }}:&nbsp;</label>
                    <label style="font-size: 20px; font-weight: bold; color: #ff4f1f;" id="totalvalue">0</label>
                    <label style="font-size: 15px;"
                        class="control-label parent-label">&nbsp;{{ __('home.SAR') }}</label>
                </div>

                <div class="form-group">
                    <label for="paymodal">{{ __('home.paymentmethod') }}</label>
                    <select class="form-control" name="paymodal" id="paymodal" required>
                        <option value="Cash">{{ __('report.cash') }}</option>
                        <option value="Shabka">{{ __('report.shabka') }}</option>
                        <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                        <option value="Credit">{{ __('report.credit') }}</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="cashamount" class="control-label parent-label">{{ __('report.cash') }}</label>
                        <input type="text" class="form-control parent-input" name="cashamount" id="cashamount"
                            readonly value="0">
                    </div>
                    <div class="col">
                        <label for="bankamount" class="control-label parent-label">{{ __('report.shabka') }}</label>
                        <input type="text" class="form-control parent-input" name="bankamount" id="bankamount"
                            readonly value="0">
                    </div>
                    <div class="col">
                        <label for="Bank_transfer"
                            class="control-label parent-label">{{ __('home.Bank_transfer') }}</label>
                        <input type="text" class="form-control parent-input" name="Bank_transfer" id="Bank_transfer"
                            readonly value="0">
                    </div>
                    <div class="col">
                        <label for="creaditamount"
                            class="control-label parent-label">{{ __('report.credit') }}</label>
                        <input type="text" class="form-control parent-input" name="creaditamount" id="creaditamount"
                            readonly value="0">
                    </div>
                </div>
                <input type="hidden" id="invoiceid_payment">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button type="button" id="confirmpayment" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();

    $('#uploadzatca').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $(this).find('.modal-body #invoiceid_zatca').val(id);
    });

    $('#updateDate').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $(this).find('.modal-body #id').val(id);
    });

    $('#paymentmethod').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var invoice = button.data('totalinvoice');

        $('#invoiceid_payment').val(id);
        document.getElementById('totalvalue').innerHTML = invoice;
        $('#cashamount').val(invoice);
    });

    $("#confirmpayment").click(function (e) {
        e.preventDefault();

        if ($('#cashamount').val() == '') $('#cashamount').val(0);
        if ($('#bankamount').val() == '') $('#bankamount').val(0);
        if ($('#creaditamount').val() == '') $('#creaditamount').val(0);
        if ($('#Bank_transfer').val() == '') $('#Bank_transfer').val(0);

        let text = document.getElementById('totalvalue').innerText;
        let paymentMethodValue = $('#paymodal').val();
        let anotherBankValue = 5;

        let totalEntered = Number($('#cashamount').val()) + Number($('#Bank_transfer').val()) + Number($('#bankamount').val()) + Number($('#creaditamount').val());

        if (Number(text) == totalEntered) {
            $.ajax({
                url: "{{URL::to('updatepaymentconfirmpayment')}}/" + $('#invoiceid_payment').val() + '/' +
                    $('#cashamount').val() + '/' + $('#bankamount').val() + '/' +
                    $('#creaditamount').val() + "/" + $('#Bank_transfer').val() + '/' +
                    paymentMethodValue + '/' + anotherBankValue,
                type: "GET",
                dataType: "html",
                success: function (data) {
                    $("#ajax_responce_allinvoicesDiv").html(data);
                    $('#paymentmethod').modal('hide');
                    Swal.fire({
                        title: "تم التحديث بنجاح | Updated Successfully",
                        html: `<div style="font-size: 16px; line-height: 1.6;">تم تحديث طريقة الدفع وتعديل الحسابات بنجاح.</div>`,
                        icon: "success",
                        confirmButtonText: "موافق | OK",
                        confirmButtonColor: "#28a745"
                    });
                },
                error: function (response) {
                    alert("{{ __('home.sorryerror') }}");
                }
            });
        } else {
            $('#saveinvice').val(0);
            alert("{{ __('home.entermonycorrect') }}");
        }
    });

    $('select[name="paymodal"]').on('change', function () {
        var selectCustomer = $(this).val();
        var value = 0;

        if ($('#cashamount').val() != 0) {
            value = $('#cashamount').val();
        } else if ($('#bankamount').val() != 0) {
            value = $('#bankamount').val();
        } else if ($('#Bank_transfer').val() != 0) {
            value = $('#Bank_transfer').val();
        } else {
            value = $('#creaditamount').val();
        }

        if (selectCustomer == 'Cash') {
            $('#cashamount').val(value);
            $('#bankamount').val(0); $('#creaditamount').val(0); $('#Bank_transfer').val(0);
            $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
        } else if (selectCustomer == 'Shabka') {
            $('#cashamount').val(0); $('#bankamount').val(value); $('#creaditamount').val(0); $('#Bank_transfer').val(0);
            $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
        } else if (selectCustomer == 'Credit') {
            $('#cashamount').val(0); $('#bankamount').val(0); $('#Bank_transfer').val(0); $('#creaditamount').val(value);
            $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
        } else if (selectCustomer == 'Bank_transfer') {
            $('#cashamount').val(0); $('#bankamount').val(0); $('#creaditamount').val(0); $('#Bank_transfer').val(value);
            $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
        } else {
            $('#cashamount').val(value);
            $('#bankamount').val(0); $('#creaditamount').val(0); $('#Bank_transfer').val(0);
            $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', false);
        }
    });

    function searchaboutproductfunction() {
        var dateVal = $('#date_search').val();
        $.ajax({
            url: "{{URL::to('searchAllInvoicespaginatenew')}}/" + dateVal,
            type: "GET",
            dataType: "html",
            success: function (products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    }

    function searchaboutinvoiceByIdfunction() {
        var idVal = $('#invoiceid_search').val();
        var url = idVal != '' ? "{{URL::to('searchaboutinvoiceByIdfunction')}}/" + idVal : "{{URL::to('getAllinvicesajax')}}";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "html",
            success: function (products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    }

    $('select[name="clientnamesearch"]').on('change', function () {
        var selectCustomer = $(this).val();
        var url = selectCustomer != '' ? "{{URL::to('getinvoicesbycustomer')}}/" + selectCustomer : "{{URL::to('getAllinvicesajax')}}";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "html",
            success: function (products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    });

    // ─── فلترة الفواتير حسب طريقة الدفع المختارة ───
    $('select[name="payment_method_search"]').on('change', function () {
        var paymentMethod = $(this).val();
        // يمكنك تعديل المسار (Route) أدناه ليتطابق مع الـ Controller لديك في الباك اند
        var url = paymentMethod != '' ? "{{URL::to('getinvoicesbypaymentmethod')}}/" + paymentMethod : "{{URL::to('getAllinvicesajax')}}";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "html",
            success: function (products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    });

    function refreshAllInvoicesTable() {
        $.ajax({
            url: "{{URL::to('getAllinvicesajax')}}",
            type: "GET",
            dataType: "html",
            success: function (products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    }

    $(document).ready(function () {
        $('.select2').select2({
            width: '100%'
        });
        document.getElementById('loading-screen').style.display = 'none';
        refreshAllInvoicesTable();
    });
</script>
@endsection