@extends('layouts.master')
@section('css')
    <style>
        @media print {
            #print_Button {
                display: none;
            }
        }

        body {
            font: 13pt Georgia, "Times New Roman", Times, serif;
            line-height: 1.5;
            border-style: solid;

        }
    </style>
@endsection
@section('title')
    {{ __('home.print') }}
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                    <div class="invoice-header">

<div class="billed-from">
    <br>
    &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; <span style="font-size:25px">{{Nameen}}</span>
    <br>
    <p dir=ltr> {{describtionen}} &nbsp;&nbsp;&nbsp;&nbsp;</p>
    <span dir=ltr>{{STen}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <p dir=ltr> {{Taxen}} </p>

</div>
<div class="row">
<?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

</div>


<div class="billed-from">
    <br>

    &nbsp; &nbsp; &nbsp; <span style="font-size:25px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class=" mg-t-12">
                            <br>
                            <br>
                            <br>
                        <br>
                        <br>
                        <div class="row d-flex justify-content-center">

                            
                            <div class="col-lg-3" id="start_at">
                                <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                <?php
                                $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                ?>
                                <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                            </div>

                        </div>
                        <br>
                            <div class="card-body">
                                <div class="table-responsive">
                                    @if (isset($Invoices))
                                        <div style="border-radius: 10px" class="card m-3 p-3">
                                            <div class="">
                                                <div class=" hoverable-table px-1">
                                                    <table
                                                        class="table table-hover table-bordered table-striped text-center ">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-bottom-0">#</th>
                                                                <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                                <th class="border-bottom-0"> {{ __('accountes.user') }}</th>
                                                                <th class="border-bottom-0">
                                                                    {{ __('accountes.Theamountpaid') }}</th>
                                                                <th class="border-bottom-0">
                                                                    {{ __('accountes.Reasonforspendingmoney') }}</th>
                                                                <th class="border-bottom-0"> {{ __('home.paymentmethod') }}
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        $i = 0;
                                                        ?>
                                                        <?php
                                                        $count = 0;
                                                        ?>
                                                        <?php
                                                        $startat = '';
                                                        $endat = '';
                                                        $totalprice = 0;
                                                        $totaladdedvalue = 0;
                                                        ?>
                                                        @foreach ($Invoices as $invoice)
                                                            <?php
                                                            $totalprice += $invoice->Theـamountـpaid;
                                                            if ($count == 0) {
                                                                $userId = $invoice->user_id;
                                                                $startat = $invoice->created_at;
                                                            }
                                                            $endat = $invoice->created_at;
                                                            $count++;
                                                            $i++;
                                                            $date = explode(' ', $invoice->created_at);
                                                            ?>
                                                            <tbody>
                                                                <tr>
                                                                    <td>{{ $i }}</td>
                                                                    <td>{{ $date[0] }}</td>

                                                                    <td>{{ $invoice->user->name }}</td>
                                                                    <td>{{ $invoice->Theـamountـpaid }}</td>
                                                                    <td>{{ $invoice->Reasonforspendingmoney }}</td>

                                                                    <td>

                                                                        @if ($invoice->Pay_Method_Name == 'Cash')
                                                                            <span
                                                                                class="text-success">{{ __('report.cash') }}</span>
                                                                                @elseif($invoice->Pay_Method_Name == 'Credit')
                                                                            <span
                                                                                class="text-danger">{{ __('report.credit') }}</span>
                                                                                @elseif($invoice->Pay_Method_Name == 'Bank_transfer')
                                                                            <span
                                                                                class="text-danger">{{ __('home.Bank_transfer') }}</span>
                                                                        @else
                                                                            <span
                                                                                class="text-warning">{{ __('report.shabka') }}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                        @endforeach

                                                        </tbody>

                                                    </table>

                                                    <div class="table-padding">
                                                        <table
                                                            class="table table-bordered table-hover text-center table-striped mt-5">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col"></th>
                                                                    <th scope="col">{{ __('report.totalprice') }}</th>
                                                                    <th>{{ __('home.the amount') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">1</th>
                                                                    <td>{{ __('home.total') }}</td>
                                                                    <td>{{ $totalprice }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>


                                                    <br>
                                                    @if (count($Invoices) > 0)
                                                        <br>
                                                    @endif
                                    @endif

                                

                                </div>
                                <hr class="mg-b-40">



                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                                        {{ __('home.print') }}
                                        <svg style="width: 20px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
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
