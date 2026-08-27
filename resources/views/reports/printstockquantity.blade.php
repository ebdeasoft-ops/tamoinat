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
{{__('home.print')}}
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
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                            {{__('home.print')}}
                            <i class="mdi mdi-printer ml-1"></i>
                        </button>
                    </div>


                        @if(isset($products))
                        <div class="card-body">
                                <?php
                                $userId = 0;
                                $count = 0;
                                ?>
                                <?php
                                $userId = 0;
                                $startat = '';
                                $endat = '';
                                $totalprice = 0;
                                $totaladdedvalue = 0;
                                $i = 0;

                                ?>
                                <div class="table-responsive hoverable-table px-1">
                                    <table class="table table-striped table-bordered text-center table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0"> {{__('home.productNo')}}</th>

                                                <th class="border-bottom-0"> {{__('home.productname')}}</th>
                                                <th class="border-bottom-0">{{__('users.branch')}}</th>

                                                <th class="border-bottom-0"> {{__('home.productlocation')}}</th>

                                                <th class="border-bottom-0"> {{__('home.saleprice')}}</th>
                                                <th class="border-bottom-0"> {{__('home.stock')}}</th>
                                                <th class="border-bottom-0"> {{__('home.total')}}</th>
                                            </tr>
                                        </thead>
                                        @foreach ($products as $product)
                                        <?php
                                        $totalprice += $product->cost_price * $product->All_QUENTITY;

                                        ?>







                                        <?php
                                        $i++;

                                        $date = explode(" ", $product->created_at);

                                        ?>


                                        <tbody>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td dir='ltr'>{{ $product->barcode }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->branch->name }}</td>
                                                <td >{{$product->Product_Location}}</td>

                                                <td>{{ $product->cost_price }}
                                                </td>
                                                <td>{{ $product->All_QUENTITY }}</td>
                                                <td>{{ $product->cost_price * $product->All_QUENTITY }}
                                                </td> </tr>

                                        </tbody>

                                        @endforeach
                                    </table>


                                    



                                    <br>
                                    @endif
                                </div>


                                <div class="tabl my-1">
                                    <table style=" width:50% " class="table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>{{__('report.totalprice')}}</th>
                                                <th>{{ __('home.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{__('report.totalallprice')}}</td>
                                                <td>{{(($totalprice) )}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <hr class="mg-b-40">


                                
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