@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
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
                <h5 style="color: white" class="mt-1">
                    معاينة طباعة الفاتورة</h5>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    <!-- row -->
    <div class="row row-sm">
        <div style="padding-left: 0;padding-right:0" class="col-md-12 col-xl-12">

            </h5>
            <div class="col-md-12 col-xl-12">
                <div class=" main-content-body-invoice" id="print">
                    <div class="card card-invoice p-3 pt-4">
                        <div class="card-body pt-3">
                      <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%" dir=rtl>




                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                            <span class="thick" style="font-size:18px">{{Namear}}</span>
                            <br>
                            <p class="tx-16 thick"> {{describtionar}}</p>
                            <p class="tx-16 thick">{{STar}}</p>
                            <p class="tx-16 thick">{{Taxar}}</p>

                        </div><!-- billed-from -->
                        <div >
                            <?php
                            $logo = camplogo;
                            ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 150px; height: 150px;"></a>

                        </div>

                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>
                            <span class="thick" style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p class="tx-16 thick" > {{describtionen}} </p>
                            <span class="tx-16 thick">{{STen}} </span>
                            <p class="tx-16 thick"> {{Taxen}} </p>

                        </div>

                    </div><!-- invoice-header -->
                            <div class="row mg-t-12">
                                <br>
                                <br>
                                <br>

                            </div>

                           

                            

                                    <?php
                                    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                    ?>



                                
                                <div class="table-padding">
                                    <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                            <col style="width:15%">
                            <col style="width:15%">
                            <col style="width:15%">
                            <col style="width:20%">
                            <col style="width:15%">
                            <col style="width:20%">
                                        <thead>
                                            <tr>
                                                <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                                </th>
                                                <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['start_at'] }}</label>
                                                </th>

                                                <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                                </th>
                                                <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $data['end_at'] }}</label>
                                                </th>


                                                <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.branch') }} </label>

                                                </th>
                                                <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['branch'] }}</label>
                                                </th> <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} </label>

</th>
<th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>
</th>
                                            </tr>


                                        </thead>
                                    </table>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <?php

                                    $totalrecivefrombranchcash = 0;
                                    $totalrecivefrombranchshabka = 0;

                                    $i = 0;
                                    ?>
                                    <div class="col-xl-12">
                                        <div class="card mg-b-20">
                                            <div class="card-header pb-0">
                                            </div>
                                            <div class="card-body">
                                                <div style="border-radius: 5px !important" class="table-responsive py-2">


                                                    <table class="table text-center table-bordered budgetSheet-table" style="border: 1px solid black;border-collapse: collapse !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>__</th>
                                                                <th colspan="3" style="background-color: #ecf0fa;font-size: 12px">{{ __('home.sales') }}</th>
                                                                <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.purchases') }}</th>
                                                                <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.cash expenses') }}</th>
                                                                <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.receive money') }}</th>
                                                                <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.other_expenses') }}</th>
                                                                <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.cach_from_bank') }}</th>
                                                                @if($data['reportforbranch']==1)
                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $beanch = App\Models\branchs::find($branch);
                                                                ?>
                                                                <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.trensferfrombudget')   }} {{$beanch->name}}</th>

                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                        <tbody>
                                                            <tr>
                                                                <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('report.cash') }}</td>
                                                                <td style="vertical-align: middle" colspan="3">{{ __('home.sales') }} : {{ $data['salescash'] }}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    {{__('home.purchases')}} : {{ $data['purchesecash'] }}
                                                                </td>
                                                                <td>
                                                                    {{ round($data['transactiontosuplliers_cash'] )}}
                                                                </td>
                                                                <td>
                                                                    {{ round($data['credittransaction_cash']) }}
                                                                </td>
                                                                <td>
                                                                    {{ $data['expenses_cash'] }}
                                                                </td>
                                                                <td>{{__('home.cach_bank')}} : {{$data['bank_cash']}} </td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $cashtransfer = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchCash'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $cashtransfer += $transferMoney_to_mainbranchCash->amount;
                                                                    $totalrecivefrombranchcash += $transferMoney_to_mainbranchCash->amount;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{$cashtransfer }}
                                                                </td>
                                                                @endforeach
                                                                @endif

                                                            <tr>
                                                                <?php
                                                                $returnsalescach = $data['returnsalescash'] + $data['returnSalespartial'] + $data['returnsalesshabka'] + $data['returnSalesBankTransfer']

                                                                ?>
                                                                <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;{{__('home.return')}} : {{ $returnsalescach }}</td>
                                                                <td colspan="">{{__('home.return')}} : {{ $data['returnpurchasecash'] }}</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            <?php
                                                            $totalsalescash = $data['salescash'] - $returnsalescach;
                                                            ?>
                                                            <tr class="budget-line">
                                                                <td colspan="3">{{__('home.total')}} : {{$totalsalescash }} </td>
                                                                <td>{{__('home.total') }}:{{$data['purchesecash']-$data['returnpurchasecash'] }}</td>
                                                                <td>{{__('home.total') }}: {{ $data['transactiontosuplliers_cash']}}</td>
                                                                <td> {{__('home.total')}} :{{ $data['credittransaction_cash']}} </td>
                                                                <td>{{__('home.total')}} : {{ $data['expenses_cash']}}</td>
                                                                <td>{{__('home.total')}} : {{ $data['bank_cash']}}</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $cashtransfer = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchCash'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $cashtransfer += $transferMoney_to_mainbranchCash->amount;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{$cashtransfer }}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            </tr>






                                                            <tr>
                                                                <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('report.shabka') }}</td>
                                                                <td colspan="3">{{__('home.sales')}}
                                                                    : {{$data['salesshabka']}}
                                                                </td>
                                                                <td>
                                                                    {{__('home.purchases')}} : {{ $data['purcheseshabka'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $data['transactiontosuplliers_shabka'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $data['credittransaction_shabka'] }}
                                                                </td>
                                                                <td>{{ $data['expenses_shabka'] }}</td>
                                                                <td>--</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $shabkatransfer = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchshabka'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $shabkatransfer += $transferMoney_to_mainbranchCash->Pay_Method_Name;
                                                                    $totalrecivefrombranchshabka += $transferMoney_to_mainbranchCash->Pay_Method_Name;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{$shabkatransfer }}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">{{ __('home.return') }} : 0</td>
                                                                <td>{{ __('home.return') }} : {{ $data['returnpurchaseshabka'] }}</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif


                                                            </tr>
                                                            <tr class="budget-line">
                                                                <?php
                                                                $totalsaleshabka = $data['salesshabka'];
                                                                ?>
                                                                <td colspan="3">{{ __('home.total') }} : {{ $totalsaleshabka }}</td>
                                                                <td>{{ __('home.total') }} : {{$data['purcheseshabka']- $data['returnpurchaseshabka'] }}</td>
                                                                <td>{{ __('home.total')}} :{{ $data['transactiontosuplliers_shabka']}}</td>
                                                                <td>{{ __('home.total') }}:{{ $data['credittransaction_shabka'] }}</td>
                                                                <td>{{__('home.total')}} : {{ $data['expenses_shabka']}}</td>
                                                                <td>--</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $shabkatransfer = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchshabka'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $shabkatransfer += $transferMoney_to_mainbranchCash->Pay_Method_Name;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{$shabkatransfer }}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            </tr>














                                                            <tr>
                                                                <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('home.Bank_transfer') }}</td>
                                                                <td colspan="3">


                                                                    {{__('home.sales')}}
                                                                    : {{$data['salesBankTransfer']}}
                                                                </td>
                                                                <td>{{__('home.purchases')}} : {{ $data['purchasebankTransfer'] }}</td>
                                                                <td>{{$data['transactiontosuplliers_banktransfer']}}</td>
                                                                <td>{{$data['credittransaction_banktransfer']}}</td>
                                                                <td>{{$data['expenses_banktransfer']}}</td>
                                                                <td>{{__('home.shabka_bank')}} : {{$data['bank_shabka'] }}</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $banktransfertomain = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchCash'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $banktransfertomain += $transferMoney_to_mainbranchCash->bank_transfer;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{ $banktransfertomain}}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">{{ __('home.return') }} : 0</td>
                                                                <td>{{ __('home.return') }} : {{$data['returnpurchasebanktransfer'] }} </td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif


                                                            </tr>
                                                            <tr class="budget-line">
                                                                <td colspan="3">

                                                                    <?php
                                                                    $totalsalepartial = $data['salesBankTransfer'];

                                                                    ?>
                                                                    {{ __('home.total') }} : {{ $totalsalepartial}}
                                                                </td>
                                                                <td> {{ __('home.total') }} : {{ $data['purchasebankTransfer']-$data['returnpurchasebanktransfer']}}</td>
                                                                <td>{{ __('home.total') }} : {{$data['transactiontosuplliers_banktransfer']}}</td>
                                                                <td>{{ __('home.total') }} : {{$data['credittransaction_banktransfer']}}</td>
                                                                <td>{{ __('home.total') }} : {{$data['expenses_banktransfer']}}</td>
                                                                <td>{{__('home.total') }}: {{$data['bank_shabka']}} </td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $banktransfertomain = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchCash'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $banktransfertomain += $transferMoney_to_mainbranchCash->bank_transfer;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{ $banktransfertomain}}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            </tr>














                                                            <tr>
                                                                <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('report.credit') }}</td>
                                                                <td style="vertical-align: middle" colspan="3">{{ __('home.sales') }} : {{$data['salescredit']}}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    {{__('home.purchases')}} : {{ $data['purchesecredit'] }}
                                                                </td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif
                                                            <tr>
                                                                <td colspan="3">{{__('home.return')}} : {{ $data['returnsalescredit'] }}</td>
                                                                <td>{{__('home.return')}} : {{$data['returnpurchasecredit']}}</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            <?php
                                                            $totalsalecrdit = $data['salescredit'] - $data['returnsalescredit'];
                                                            ?>
                                                            <tr class="budget-line">
                                                                <td colspan="3">{{ __('home.total') }} : {{ $totalsalecrdit }}</td>
                                                                <td>{{ __('home.total') }} : {{ $data['purchesecredit']-$data['returnpurchasecredit']}}</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            </tr>

























                                                            <tr>
                                                                <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('home.total') }}</td>
                                                                <td colspan="3">{{__('home.sales')}} : {{ $data['salescredit'] + $data['salesshabka'] + $data['salescash']+$data['salesBankTransfer'] }}
                                                                </td>
                                                                <td>
                                                                    {{__('home.purchases')}} : {{ $data['purchesecredit'] +$data['purchasebankTransfer']+ $data['purcheseshabka'] + $data['purchesecash']  }}
                                                                </td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $shabkatransfer = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchshabka'] as $transferMoney_to_mainbranchCash)
                                                                <?php
                                                                $shabkatransfer += $transferMoney_to_mainbranchCash->Pay_Method_Name;
                                                                ?>

                                                                @endforeach
                                                                <?php
                                                                $cashtransfer = 0;
                                                                $banktransfertotal = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchCash'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $cashtransfer += $transferMoney_to_mainbranchCash->amount;
                                                                    $banktransfertotal += $transferMoney_to_mainbranchCash->bank_transfer;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{$shabkatransfer+$cashtransfer+$banktransfertotal }}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            <tr>
                                                                <td colspan="3">{{__('home.return')}} : {{ $data['returnsalesshabka'] + $data['returnsalescash'] + $data['returnsalescredit'] +$data['returnSalespartial']+$data['returnSalesBankTransfer']}}</td>
                                                                <td>{{__('home.return')}} : {{ $data['returnpurchaseshabka'] + $data['returnpurchasecash'] + $data['returnpurchasecredit']  +$data['returnpurchasebanktransfer']}}</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                <td>__</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <td>__</td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">{{__('home.total')}} : {{ $totalsalescash+$totalsaleshabka+$totalsalecrdit+$totalsalepartial}} </td>
                                                                <td>{{__('home.total')}} : {{round( ( $data['purchesecash'] + $data['purchesecredit'] + $data['purcheseshabka'] + $data['purchasebankTransfer']-( $data['returnpurchasecash'] + $data['returnpurchasecredit']+$data['returnpurchasebanktransfer']+$data['returnpurchaseshabka'])) ,2) }}</td>
                                                                <td rowspan="3">{{ __('home.total') }} : {{ round($data['transactiontosuplliers_shabka'] + $data['transactiontosuplliers_cash']+$data['transactiontosuplliers_banktransfer'],2) }}</td>
                                                                <td rowspan="3">{{ __('home.total') }} : {{ round($data['credittransaction_shabka'] + $data['credittransaction_cash']+$data['credittransaction_banktransfer'],2) }}</td>
                                                                <td rowspan="3">{{ __('home.total') }} : {{ round( $data['expenses_cash'] + $data['expenses_shabka']+$data['expenses_banktransfer'],2) }}</td>
                                                                <td rowspan="3">{{ __('home.total') }} : {{ round( $data['bank_cash'] + $data['bank_shabka'],2) }}</td>
                                                                @if($data['reportforbranch']==1)

                                                                @foreach($data['transferMoney_to_mainbranchshabkafrombranchas'] as $branch)
                                                                <?php
                                                                $shabkatransfer = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchshabka'] as $transferMoney_to_mainbranchCash)
                                                                <?php
                                                                $shabkatransfer += $transferMoney_to_mainbranchCash->Pay_Method_Name;
                                                                ?>

                                                                @endforeach
                                                                <?php
                                                                $cashtransfer = 0;
                                                                $banktransfertotal = 0;
                                                                ?>
                                                                @foreach($data['transferMoney_to_mainbranchCash'] as $transferMoney_to_mainbranchCash)
                                                                <?php

                                                                if ($transferMoney_to_mainbranchCash->branchs_id == $branch) {
                                                                    $cashtransfer += $transferMoney_to_mainbranchCash->amount;
                                                                    $banktransfertotal += $transferMoney_to_mainbranchCash->bank_transfer;
                                                                }
                                                                ?>
                                                                @endforeach
                                                                <td>
                                                                    {{$shabkatransfer+$cashtransfer+$banktransfertotal }}
                                                                </td>
                                                                @endforeach
                                                                @endif
                                                            </tr>
                                                            </tr>

                                                        </tbody>
                                                        </thead>
                                                    </table>











                                                    <div class="table-padding">
                                                        <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.convertboxtobank') }}
                                                                    </th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ round( $data['convertcashboxToBankitemamount'] ,2) }}</th>
                                                                </tr>


                                                            </thead>
                                                        </table>
                                                    </div>
                                



                                                    <div class="table-padding">
                                                        <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-1">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.paymentmethod') }} </th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.receive money') }} </th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.The amount paid') }}</th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.Remainingamount') }}</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{ __('report.cash') }}</th>
                                                                    <th>{{round(( $data['bank_cash']+$data['Transfer_cash_from_the_last_day'] +$data['credittransaction_cash']+($data['salescash']-$returnsalescach)+ $totalrecivefrombranchcash)-round(  $data['convertcashboxToBankitemamount'] ,2),2) }}</th>
                                                                    <th>{{ $data['expenses_cash'] + $data['transactiontosuplliers_cash']+$data['purchesecash']- $data['returnpurchasecash']  }}</th>
                                                                    <th>{{round((( $data['bank_cash']+$data['Transfer_cash_from_the_last_day'] +$data['credittransaction_cash']+($data['salescash']-$returnsalescach)+ $totalrecivefrombranchcash)-round(  $data['convertcashboxToBankitemamount'] ,2))-($data['expenses_cash'] + $data['transactiontosuplliers_cash']+$data['purchesecash']- $data['returnpurchasecash']),2) }}</th>
                                                                </tr>
                                                                <tr>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.bank') }}</th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{$data['credittransaction_shabka']+$data['bank_shabka']+$data['salesBankTransfer']+$data['salesshabka']+ $totalrecivefrombranchshabka+$data['credittransaction_banktransfer'] +round( $data['convertcashboxToBankitemamount'] ,2)}}</th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ $data['transactiontosuplliers_shabka']+$data['transactiontosuplliers_banktransfer']+$data['expenses_banktransfer']+$data['expenses_shabka']+$data['purcheseshabka']+$data['purchasebankTransfer']-( $data['returnpurchasebanktransfer']+$data['returnpurchaseshabka'])}}</th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ ($data['credittransaction_shabka']+$data['credittransaction_banktransfer']+$data['bank_shabka']+$data['salesshabka']+$data['salesBankTransfer']+ $totalrecivefrombranchshabka +round( $data['convertcashboxToBankitemamount'] ,2)   )-( $data['transactiontosuplliers_shabka']+$data['transactiontosuplliers_banktransfer']+$data['expenses_banktransfer']+$data['expenses_shabka']+$data['purcheseshabka']+$data['purchasebankTransfer']-( $data['returnpurchasebanktransfer']+$data['returnpurchaseshabka']))}}</th>

                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>

                                                    <div class="table-padding">
                                                        <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.Transfer_cash_from_the_last_day') }}
                                                                    </th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"><span style="color:green;font-size: 18px;">{{ round( $data['Transfer_cash_from_the_last_day'] ,2) }}</span></th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.Transfer cash to the next day') }}
                                                                    </th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);"><span style="color:red;font-size: 18px;">{{ round( $data['Transfer_cash_to_the_next_day'] ,2) }}</span></th>

                                                                </tr>


                                                            </thead>
                                                        </table>
                                                    </div>

                                                  




                                                    <div class="table-padding">
                                                        <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                            <thead>

                                                                <tr>
                                                                    <th>{{ __('home.credit_supplier_amount') }}</th>
                                                                    <th>{{ round( $data['credit_supplier_amount'],2) }}</th>
                                                                </tr>
                                                                <tr>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.creadit_customer_amount') }}</th>
                                                                    <th style="background-color: rgba(236, 240, 250, 1);">{{ round($data['creadit_customer_amount'] ,2)   }}</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>





                                                </div>

                                            </div>
                                        </div>
                                        <hr class="mg-b-40">



                                        <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                            {{ __('home.print') }}
                                            <i class="mdi mdi-printer ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- COL-END -->
            </div>
            <!-- row closed -->
        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->
</div>
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
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