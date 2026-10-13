@extends('layouts.master')

@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }

        .card-invoice {
            box-shadow: none !important;
            border: none !important;
        }

        tr {
            page-break-inside: avoid;
        }
    }

    .card-invoice {
        border: none;
        border-radius: 14px !important;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    }

    .invoice-title {
        display: inline-block;
        color: #2e3d50 !important;
        font-weight: 700;
        letter-spacing: .5px;
        border-bottom: 3px solid #419BB2;
        padding-bottom: 6px;
        margin-bottom: 18px;
    }

    .invoice-header {
        border-bottom: 1px solid #eef0f4;
        padding-bottom: 18px;
        margin-bottom: 10px;
    }

    .billed-from span {
        font-weight: 700;
        color: #2e3d50;
    }

    .billed-from p,
    .billed-from span {
        margin-bottom: 4px;
    }

    .info-bar th {
        background-color: #ecf0fa !important;
        border: 1px solid #dde3ef;
        font-size: 13px;
    }

    .info-bar label {
        margin-bottom: 0;
        font-size: 13px;
        color: #419BB2;
        font-weight: 700;
    }

    .budgetSheet-table th,
    .budgetSheet-table td {
        border: 1px solid #d9dee7 !important;
        font-size: 13px;
        vertical-align: middle;
    }

    .budgetSheet-table thead th {
        background-color: #ecf0fa;
        font-size: 12px;
        font-weight: 700;
        color: #2e3d50;
    }

    .budget-line {
        background-color: #f7f9fc;
        font-weight: 700;
    }

    .summary-table th {
        border: 1px solid #dde3ef;
        background-color: #ecf0fa;
        font-size: 13px;
    }

    .btn-print {
        background: linear-gradient(135deg, #e14c4c, #c0392b);
        border: none;
        border-radius: 30px;
        padding: 10px 34px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(192, 57, 43, 0.35);
    }
</style>
@endsection

@section('title')
    {{ __('report.budgetsheet') }}
@stop

@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h5 style="color: white" class="mt-1">معاينة طباعة الفاتورة</h5>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->
    </div>
@endsection

@section('content')

    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice p-3 pt-4">

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-danger btn-print text-white mt-3 mr-2" id="print_Button"
                            onclick="printDiv()">
                            {{ __('home.print') }}
                            <i class="mdi mdi-printer ml-1"></i>
                        </button>
                    </div>

                    <div class="card-body pt-3">

                        <div class="invoice-header" style="display:flex;justify-content:space-between;width:100%">

                            <div class="billed-from" style="width:33%;text-align:center;">
                                <br>
                                <span style="font-size:25px">{{ Nameen }}</span>
                                <br>
                                <p dir="ltr">{{ describtionen }}</p>
                                <span dir="ltr">{{ STen }}</span>
                                <p dir="ltr">{{ Taxen }}</p>
                            </div>

                            <div class="row align-items-center">
                                @php $logo = camplogo; @endphp
                                <a href="https://ebdeasoft.com/">
                                    <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1" alt="logo"
                                        style="width: 110px; height: 70px;">
                                </a>
                            </div>

                            <div class="billed-from" style="width:33%;text-align:center;">
                                <br>
                                <span style="font-size:25px">{{ Namear }}</span>
                                <br>
                                <p>{{ describtionar }}</p>
                                <p>{{ STar }}</p>
                                <p>{{ Taxar }}</p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->

                        <div class="row mg-t-12">
                            <br><br><br>
                        </div>

                        @php
                            $currentdata = \Carbon\Carbon::now()->addHours(3)->format('Y-m-d H:i:s');
                            $banktransfertotal = 0;
                        @endphp

                        <center>
                            <h6 class="invoice-title">{{ __('report.budgetsheet') }}</h6>
                        </center>

                        <div class="table-padding table-responsive">
                            <table class="table table-striped table-bordered text-center my-2 info-bar">
                                <thead>
                                    <tr>
                                        <th><label>{{ __('report.fromdate') }}:</label></th>
                                        <th><label>{{ $data['start_at'] }}</label></th>
                                        <th><label>{{ __('report.todate') }}</label></th>
                                        <th><label>{{ $data['end_at'] }}</label></th>
                                        <th><label>{{ __('home.branch') }}</label></th>
                                        <th><label>{{ $data['branch'] }}</label></th>
                                        <th><label>{{ __('home.exportTime') }}</label></th>
                                        <th><label>{{ $currentdata }}</label></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>

                        <div class="table-responsive">
                            @php
                                $totalrecivefrombranchcash = 0;
                                $totalrecivefrombranchshabka = 0;
                            @endphp

                            <div class="card mg-b-20">
                                <div class="card-body">
                                    <div style="border-radius: 5px !important" class="table-responsive py-2">

                                        <table class="table text-center table-bordered budgetSheet-table">
                                            <thead>
                                                <tr>
                                                    <th>__</th>
                                                    <th colspan="3">{{ __('home.sales') }}</th>
                                                    <th>{{ __('home.purchases') }}</th>
                                                    <th>{{ __('home.The amount paid') }}</th>
                                                    <th>{{ __('home.newexpense') }}</th>
                                                    <th>{{ __('home.receive money') }}</th>
                                                    <th>{{ __('home.cach_from_bank') }}</th>
                                                    <th>{{ __('home.convertboxtobank') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                {{-- Cash --}}
                                                <tr>
                                                    <td rowspan="3" style="background-color:#ecf0fa">{{ __('report.cash') }}</td>
                                                    <td colspan="3">{{ __('home.sales') }} : {{ $data['salescash'] }}</td>
                                                    <td>{{ __('home.purchases') }} : {{ $data['purchesecash'] }}</td>
                                                    <td>{{ round($data['transactiontosuplliers_cash']) }}</td>
                                                    <td>{{ round($data['expense_cash']) }}</td>
                                                    <td>{{ round($data['credittransaction_cash']) }}</td>
                                                    <td>{{ __('home.cach_bank') }} : {{ $data['bank_cash'] }}</td>
                                                    <td>__</td>
                                                </tr>
                                                @php $returnsalescach = $data['returnsalescash'] + $data['returnSalespartial']; @endphp
                                                <tr>
                                                    <td colspan="3">{{ __('home.return') }} : {{ $returnsalescach }}</td>
                                                    <td>{{ __('home.return') }} : {{ $data['returnpurchasecash'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                @php $totalsalescash = $data['salescash'] - $returnsalescach; @endphp
                                                <tr class="budget-line">
                                                    <td colspan="3">{{ __('home.total') }} : {{ $totalsalescash }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['purchesecash'] - $data['returnpurchasecash'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['transactiontosuplliers_cash'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['expense_cash'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['credittransaction_cash'] }}</td>
                                                    <td>{{ __('home.cach_bank') }} : {{ $data['bank_cash'] }}</td>
                                                    <td>__</td>
                                                </tr>

                                                {{-- Shabka --}}
                                                <tr>
                                                    <td rowspan="3" style="background-color:#ecf0fa">{{ __('report.shabka') }}</td>
                                                    <td colspan="3">{{ __('home.sales') }} : {{ $data['salesshabka'] }}</td>
                                                    <td>{{ __('home.purchases') }} : {{ $data['purcheseshabka'] }}</td>
                                                    <td>{{ $data['transactiontosuplliers_shabka'] }}</td>
                                                    <td>{{ $data['expense_shabka'] }}</td>
                                                    <td>{{ $data['credittransaction_shabka'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">{{ __('home.return') }} : {{ $data['returnsalesshabka'] }}</td>
                                                    <td>{{ __('home.return') }} : {{ $data['returnpurchaseshabka'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                @php $totalsaleshabka = $data['salesshabka']; @endphp
                                                <tr class="budget-line">
                                                    <td colspan="3">{{ __('home.total') }} : {{ $totalsaleshabka - $data['returnsalesshabka'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['purcheseshabka'] - $data['returnpurchaseshabka'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['transactiontosuplliers_shabka'] }}</td>
                                                    <td>{{ $data['expense_shabka'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['credittransaction_shabka'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>

                                                {{-- Bank transfer --}}
                                                <tr>
                                                    <td rowspan="3" style="background-color:#ecf0fa">{{ __('home.Bank_transfer') }}</td>
                                                    <td colspan="3">{{ __('home.sales') }} : {{ $data['salesBankTransfer'] }}</td>
                                                    <td>{{ __('home.purchases') }} : {{ $data['purchasebankTransfer'] }}</td>
                                                    <td>{{ $data['transactiontosuplliers_banktransfer'] }}</td>
                                                    <td>{{ $data['expense_banktransfer'] }}</td>
                                                    <td>{{ $data['credittransaction_banktransfer'] }}</td>
                                                    <td>{{ __('home.shabka_bank') }} : {{ $data['bank_shabka'] }}</td>
                                                    <td>{{ $data['convertcashboxToBankitemamount'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">{{ __('home.return') }} : {{ $data['returnSalesBankTransfer'] }}</td>
                                                    <td>{{ __('home.return') }} : {{ $data['returnpurchasebanktransfer'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                @php $totalsalepartial = $data['salesBankTransfer']; @endphp
                                                <tr class="budget-line">
                                                    <td colspan="3">{{ __('home.total') }} : {{ $totalsalepartial - $data['returnSalesBankTransfer'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['purchasebankTransfer'] - $data['returnpurchasebanktransfer'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['transactiontosuplliers_banktransfer'] }}</td>
                                                    <td>{{ $data['expense_banktransfer'] }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['credittransaction_banktransfer'] }}</td>
                                                    <td>{{ __('home.shabka_bank') }} : {{ $data['bank_shabka'] }}</td>
                                                    <td>{{ $data['convertcashboxToBankitemamount'] }}</td>
                                                </tr>

                                                {{-- Credit --}}
                                                <tr>
                                                    <td rowspan="3" style="background-color:#ecf0fa">{{ __('report.credit') }}</td>
                                                    <td colspan="3">{{ __('home.sales') }} : {{ $data['salescredit'] }}</td>
                                                    <td>{{ __('home.purchases') }} : {{ $data['purchesecredit'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">{{ __('home.return') }} : {{ $data['returnsalescredit'] }}</td>
                                                    <td>{{ __('home.return') }} : {{ $data['returnpurchasecredit'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                @php $totalsalecrdit = $data['salescredit'] - $data['returnsalescredit']; @endphp
                                                <tr class="budget-line">
                                                    <td colspan="3">{{ __('home.total') }} : {{ $totalsalecrdit }}</td>
                                                    <td>{{ __('home.total') }} : {{ $data['purchesecredit'] - $data['returnpurchasecredit'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>

                                                {{-- Grand total --}}
                                                <tr>
                                                    <td rowspan="3" style="background-color:#ecf0fa">{{ __('home.total') }}</td>
                                                    <td colspan="3">{{ __('home.sales') }} : {{ $data['salescredit'] + $data['salesshabka'] + $data['salescash'] + $data['salesBankTransfer'] }}</td>
                                                    <td>{{ __('home.purchases') }} : {{ $data['purchesecredit'] + $data['purchasebankTransfer'] + $data['purcheseshabka'] + $data['purchesecash'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>{{ $data['bank_shabka'] + $data['bank_cash'] }}</td>
                                                    <td>{{ $data['convertcashboxToBankitemamount'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">{{ __('home.return') }} : {{ $data['returnsalesshabka'] + $data['returnsalescash'] + $data['returnsalescredit'] + $data['returnSalespartial'] + $data['returnSalesBankTransfer'] }}</td>
                                                    <td>{{ __('home.return') }} : {{ $data['returnpurchaseshabka'] + $data['returnpurchasecash'] + $data['returnpurchasecredit'] + $data['returnpurchasebanktransfer'] }}</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                    <td>__</td>
                                                </tr>
                                                <tr class="budget-line">
                                                    <td colspan="3">{{ __('home.total') }} : {{ $data['salescredit'] + $data['salesshabka'] + $data['salescash'] + $data['salesBankTransfer'] - ($data['returnsalesshabka'] + $data['returnsalescash'] + $data['returnsalescredit'] + $data['returnSalespartial'] + $data['returnSalesBankTransfer']) }}</td>
                                                    <td>{{ __('home.total') }} : {{ round(($data['purchesecash'] + $data['purchesecredit'] + $data['purcheseshabka'] + $data['purchasebankTransfer'] - ($data['returnpurchasecash'] + $data['returnpurchasecredit'] + $data['returnpurchasebanktransfer'] + $data['returnpurchaseshabka'])), 2) }}</td>
                                                    <td rowspan="3">{{ __('home.total') }} : {{ round($data['transactiontosuplliers_shabka'] + $data['transactiontosuplliers_cash'] + $data['transactiontosuplliers_banktransfer'], 2) }}</td>
                                                    <td rowspan="3">{{ __('home.total') }} : {{ round($data['expense_cash'] + $data['expense_banktransfer'] + $data['expense_shabka'], 2) }}</td>
                                                    <td rowspan="3">{{ __('home.total') }} : {{ round($data['credittransaction_shabka'] + $data['credittransaction_cash'] + $data['credittransaction_banktransfer'], 2) }}</td>
                                                    <td>{{ $data['bank_shabka'] + $data['bank_cash'] }}</td>
                                                    <td>{{ $data['convertcashboxToBankitemamount'] }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="table-padding">
                                            <table class="table table-striped table-bordered text-center my-1 summary-table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('home.paymentmethod') }}</th>
                                                        <th>{{ __('home.receive money') }}</th>
                                                        <th>{{ __('home.The amount paid') }}</th>
                                                        <th>{{ __('home.Remainingamount') }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('report.cash') }}</th>
                                                        <th>{{ round(($data['bank_cash'] + $data['totaltransferlastdayCash'] + $data['credittransaction_cash'] + ($data['salescash'] - $returnsalescach) + $totalrecivefrombranchcash), 2) }}</th>
                                                        <th>{{ $data['expense_cash'] + $data['transactiontosuplliers_cash'] + $data['purchesecash'] - $data['returnpurchasecash'] + round($data['convertcashboxToBankitemamount'], 2) }}</th>
                                                        <th>{{ round((($data['bank_cash'] + $data['totaltransferlastdayCash'] + $data['credittransaction_cash'] + ($data['salescash'] - $returnsalescach) + $totalrecivefrombranchcash) - round($data['convertcashboxToBankitemamount'], 2)) - ($data['expense_cash'] + $data['transactiontosuplliers_cash'] + $data['purchesecash'] - $data['returnpurchasecash']), 2) }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('home.bank') }}</th>
                                                        <th>{{ $data['credittransaction_shabka'] + round($data['totaltransferlastdaybank'], 2) + $data['bank_shabka'] + $data['salesBankTransfer'] + $data['salesshabka'] + $banktransfertotal + $totalrecivefrombranchshabka + $data['credittransaction_banktransfer'] + round($data['convertcashboxToBankitemamount'], 2) }}</th>
                                                        <th>{{ $data['transactiontosuplliers_shabka'] + $data['transactiontosuplliers_banktransfer'] + $data['expense_banktransfer'] + $data['expense_shabka'] + $data['purcheseshabka'] + $data['purchasebankTransfer'] - ($data['returnpurchasebanktransfer'] + $data['returnpurchaseshabka']) }}</th>
                                                        <th>{{ round(($data['credittransaction_shabka'] + round($data['totaltransferlastdaybank'], 2) + $data['credittransaction_banktransfer'] + $data['bank_shabka'] + $banktransfertotal + $data['salesshabka'] + $data['salesBankTransfer'] + $totalrecivefrombranchshabka + round($data['convertcashboxToBankitemamount'], 2)) - ($data['transactiontosuplliers_shabka'] + $data['transactiontosuplliers_banktransfer'] + $data['expense_banktransfer'] + $data['expense_shabka'] + $data['purcheseshabka'] + $data['purchasebankTransfer'] - ($data['returnpurchasebanktransfer'] + $data['returnpurchaseshabka'])), 2) }}</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                        @can('Sales profit')
                                            <div class="table-padding">
                                                <table class="table table-striped table-bordered text-center my-2 summary-table">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('home.benfitcash') }}</th>
                                                            <th>{{ round($data['benfitcash'], 2) }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('home.benfitshabka') }}</th>
                                                            <th>{{ round($data['benfitshabka'], 2) }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('home.benfitcradit') }}</th>
                                                            <th>{{ round($data['benfitcradit'], 2) }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('home.benfitbankTransferSales') }}</th>
                                                            <th>{{ round($data['benfitBank_transfer'], 2) }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('home.total') }}</th>
                                                            <th>{{ round($data['benfitcradit'] + round($data['benfitBank_transfer'], 2) + round($data['benfitcash'], 2), 2) + round($data['benfitshabka'], 2) }}</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        @endcan

                                    </div>
                                </div>
                            </div>
                            <hr class="mg-b-40">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

@endsection

@section('js')
    <!-- Internal Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>

    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endsection