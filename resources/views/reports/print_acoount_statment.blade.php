@extends('layouts.master')
@section('css')
<style>
    :root {
        --primary-color: #1a5276;
        --accent-color: #2874a6;
        --border-color: #d5dbdb;
        --text-dark: #1c2833;
        --text-muted: #7f8c8d;
        --bg-light: #f4f6f7;
        --success-color: #1e8449;
        --danger-color: #c0392b;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        color: var(--text-dark);
        background: #eef1f2;
    }

    .statement-wrapper {
        max-width: 1100px;
        margin: 30px auto;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border-radius: 6px;
        overflow: hidden;
    }

    /* ===== Toolbar ===== */
    .statement-toolbar {
        display: flex;
        justify-content: flex-end;
        padding: 14px 24px;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }

    .btn-print {
        background: var(--primary-color);
        color: #fff;
        border: none;
        padding: 9px 22px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-print:hover { background: var(--accent-color); color:#fff; }

    /* ===== Header ===== */
    .statement-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 28px 40px 20px;
        border-bottom: 3px solid var(--primary-color);
    }

    .company-block { width: 34%; text-align: center; }
    .company-block .company-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
        margin-bottom: 6px;
    }
    .company-block p {
        margin: 2px 0;
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .logo-wrap { width: 22%; text-align: center; }
    .logo-wrap img {
        width: 100px;
        height: auto;
        max-height: 80px;
        object-fit: contain;
    }

    /* ===== Statement Title ===== */
    .statement-title {
        text-align: center;
        padding: 22px 0 10px;
    }
    .statement-title span {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 6px;
        letter-spacing: .5px;
    }

    /* ===== Info Bar ===== */
    .info-bar {
        margin: 20px 40px;
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 14px 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 12px;
    }
    .info-item { text-align: center; min-width: 140px; }
    .info-item .label {
        display: block;
        font-size: 11.5px;
        color: var(--text-muted);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .info-item .value {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* ===== Table ===== */
    .statement-table-wrap {
        padding: 0 40px 30px;
    }
    table.statement-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    table.statement-table thead th {
        background: var(--primary-color);
        color: #fff;
        font-weight: 600;
        font-size: 12.5px;
        padding: 12px 8px;
        text-align: center;
        border: 1px solid var(--primary-color);
        white-space: nowrap;
    }
    table.statement-table tbody td {
        padding: 10px 8px;
        text-align: center;
        border: 1px solid var(--border-color);
        vertical-align: middle;
    }
    table.statement-table tbody tr:nth-child(even) {
        background: #fafbfc;
    }
    table.statement-table tbody tr:hover {
        background: #eef6fb;
    }

    .row-opening { background: #fdf2e3 !important; font-weight: 600; }
    .row-total {
        background: #eafaf1 !important;
        font-weight: 700;
        color: var(--success-color);
        border-top: 2px solid var(--success-color) !important;
    }
    .row-period {
        background: #fdecea !important;
        font-weight: 700;
        color: var(--danger-color);
    }

    .status-balanced { color: var(--success-color); font-weight: 700; }
    .status-debit    { color: var(--primary-color); font-weight: 700; }
    .status-credit   { color: var(--danger-color); font-weight: 700; }

    /* ===== Footer ===== */
    .statement-footer {
        padding: 18px 40px 30px;
        text-align: center;
        font-size: 11.5px;
        color: var(--text-muted);
        border-top: 1px dashed var(--border-color);
    }

    /* ===== Print ===== */
    @media print {
        body {
            background: #fff;
        }

        /* اخفاء كل حاجة في الصفحة (الـ layout الرئيسي: navbar, sidebar...) */
        body * {
            visibility: hidden;
        }

        /* اظهار كشف الحساب فقط */
        #print, #print * {
            visibility: visible;
        }

        #print {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .statement-toolbar,
        #print_Button {
            display: none !important;
        }

        .statement-wrapper {
            box-shadow: none;
            margin: 0;
            max-width: 100%;
        }

        table.statement-table thead th,
        .row-total, .row-period, .row-opening {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="statement-wrapper" id="print">

            {{-- Toolbar --}}
            <div class="statement-toolbar" id="print_Button">
                <button class="btn-print" onclick="printDiv()">
                    <i class="mdi mdi-printer"></i>
                    {{ __('home.print') }}
                </button>
            </div>

            {{-- Header --}}
            <div class="statement-header">
                <div class="company-block" dir="ltr">
                    <span class="company-name">{{Nameen}}</span>
                    <p>{{describtionen}}</p>
                    <p dir="ltr">{{STen}}</p>
                    <p>{{Taxen}}</p>
                </div>

                <div class="logo-wrap">
                    <?php $logo = camplogo; ?>
                    <a href="https://ebdeasoft.com/">
                        <img src="{{ asset('assets/img/brand').'/'.$logo }}" alt="logo">
                    </a>
                </div>

                <div class="company-block">
                    <span class="company-name">{{Namear}}</span>
                    <p>{{describtionar}}</p>
                    <p>{{STar}}</p>
                    <p>{{Taxar}}</p>
                </div>
            </div>

            {{-- Title --}}
            <div class="statement-title">
                <span>{{ __('home.account_statement') }}</span>
            </div>

            <?php $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s"); ?>

            {{-- Info bar --}}
            <div class="info-bar">
                <div class="info-item">
                    <span class="label">{{ __('home.acount_name') ?? __('home.account_statement') }}</span>
                    <span class="value">{{ $account_name }} - {{ $branch_name }}</span>
                </div>
                <div class="info-item">
                    <span class="label">{{ __('report.from') }}</span>
                    <span class="value">{{ $start_at }}</span>
                </div>
                <div class="info-item">
                    <span class="label">{{ __('report.to') }}</span>
                    <span class="value">{{ $end_at }}</span>
                </div>
                <div class="info-item">
                    <span class="label">{{ __('home.exportTime') }}</span>
                    <span class="value">{{ $currentdata }}</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="statement-table-wrap">
                <?php
                    $i = 1;
                    $total_credit = $credit;
                    $total_debit  = $debit;
                    $end_blance   = 0;
                ?>

                <table class="statement-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('home.decoumentNo') }}</th>
                            <th>{{ __('home.exportTime') }}</th>
                            <th>{{ __('report.date') }}</th>
                            <th>{{ __('home.employee') }}</th>
                            <th>{{ __('accountes.Theamountpaid') }}</th>
                            <th>{{ __('home.debit') }}</th>
                            <th>{{ __('home.credit') }}</th>
                            <th>{{ __('home.current balance') }}</th>
                            <th>{{ __('home.notesClient') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- Opening balance row --}}
                        <tr class="row-opening">
                            <td>{{ $i }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ round($debit, 2) }}</td>
                            <td>{{ round($credit, 2) }}</td>
                            @if($total_debit - $total_credit == 0)
                                <td class="status-balanced">{{ __('home.Balanced') }}</td>
                            @elseif($total_debit - $total_credit > 0)
                                <td class="status-debit">{{ __('home.debit') }} ( {{ round($total_debit - $total_credit, 2) }} ) {{ __('home.SAR') }}</td>
                            @else
                                <td class="status-credit">{{ __('home.credit') }} ( {{ round(($total_debit - $total_credit) * -1, 2) }} ) {{ __('home.SAR') }}</td>
                            @endif
                            <td>{{ __('home.oping') }}</td>
                        </tr>

                        {{-- Transaction rows --}}
                        @foreach ($data as $invoice)
                            <?php
                                $total_credit += $invoice['credit'];
                                $total_debit  += $invoice['depit'];
                                $end_blance    = round($invoice['current_blance'], 2);
                                $i++;
                            ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $invoice['id'] }}</td>
                                <td>{{ $invoice['date'] }}</td>
                                <td>{{ $invoice['date_export'] }}</td>
                                <td>{{ $invoice['user'] }}</td>
                                <td>{{ $invoice['recive_amount'] }}</td>
                                <td>{{ round($invoice['depit'], 2) }}</td>
                                <td>{{ round($invoice['credit'], 2) }}</td>
                                @if($total_debit - $total_credit == 0)
                                    <td class="status-balanced">{{ __('home.Balanced') }}</td>
                                @elseif($total_debit - $total_credit > 0)
                                    <td class="status-debit">{{ __('home.debit') }} ( {{ round($total_debit - $total_credit, 2) }} ) {{ __('home.SAR') }}</td>
                                @else
                                    <td class="status-credit">{{ __('home.credit') }} ( {{ round(($total_debit - $total_credit) * -1, 2) }} ) {{ __('home.SAR') }}</td>
                                @endif
                                <td>{{ $invoice['note'] }}</td>
                            </tr>
                        @endforeach

                        {{-- Grand total row --}}
                        <tr class="row-total">
                            <td>*</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ round($total_debit, 2) }}</td>
                            <td>{{ round($total_credit, 2) }}</td>
                            <td>{{ __('home.current balance') }}</td>
                            @if($total_debit - $total_credit == 0)
                                <td>{{ __('home.Balanced') }}</td>
                            @elseif($total_debit - $total_credit > 0)
                                <td>{{ __('home.debit') }} ( {{ round($total_debit - $total_credit, 2) }} ) {{ __('home.SAR') }}</td>
                            @else
                                <td>{{ __('home.credit') }} ( {{ round(($total_debit - $total_credit) * -1, 2) }} ) {{ __('home.SAR') }}</td>
                            @endif
                        </tr>

                        {{-- Period balance row --}}
                        <tr class="row-period">
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ round($total_debit - $debit, 2) }}</td>
                            <td>{{ round($total_credit - $credit, 2) }}</td>
                            <td>{{ __('home.balance_period') }}</td>
                            @if(($total_debit - $total_credit) - ($debit - $credit) == 0)
                                <td>{{ __('home.Balanced') }}</td>
                            @elseif(($total_debit - $total_credit) - ($debit - $credit) > 0)
                                <td>{{ __('home.debit') }} ( {{ round(($total_debit - $total_credit) - ($debit - $credit), 2) }} ) {{ __('home.SAR') }}</td>
                            @else
                                <td>{{ __('home.credit') }} ( {{ round((($total_debit - $total_credit) - ($debit - $credit)) * -1, 2) }} ) {{ __('home.SAR') }}</td>
                            @endif
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="statement-footer">
                {{ __('home.exportTime') }}: {{ $currentdata }} &nbsp;|&nbsp; {{ config('app.name') }}
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script type="text/javascript">
    function printDiv() {
        window.print();
    }
</script>
@endsection