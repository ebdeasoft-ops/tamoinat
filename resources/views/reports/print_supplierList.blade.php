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
                            <br>
                            <br><!-- invoice-header -->

                            <div class="row row-sm">
                            <div class="col-lg-3" id="start_at">
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                    <?php
                                    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                    ?>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                                </div>
                                <div class="col-xl-12">
                                                <table class="table table-bordered table-striped text-center  table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-10p border-bottom-0">#</th>
                                                            <th class="wd-15p border-bottom-0"> {{ __('home.CampanyName') }}</th>
                                                            <th class="wd-20p border-bottom-0">{{ __('users.email') }} </th>
                                                            <th class="wd-15p border-bottom-0"> {{ __('home.phone') }}</th>
                                                            <th class="wd-15p border-bottom-0"> {{ __('home.Location') }}</th>
                                                            <th class="wd-15p border-bottom-0"> {{ __('home.creditpurchese') }}</th>
                        
                                                        </tr>
                                                    </thead>
                        
                                                    <tbody>
                        
                                                        <?php $i = 0; ?>
                                                        @foreach (App\Models\supllier::get() as $user)
                                                            <?php $i++; ?>
                        
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                @if ($user->In_debt == 0)
                                                                    <td>
                                                                        <h5 class="badge badge-success print-style">{{ $user->comp_name }}</h5>
                                                                    </td>
                                                               
                                                                @else 
                                                                    <td>
                                                                        <h5 style="background-color: #FF4F1F" class="badge badge-danger">{{ $user->comp_name }}</h5>
                                                                    </td>
                                                                @endif
                                                                <td>{{ $user->email }}</td>
                                                                <td>{{ $user->phone }}</td>
                                                                <td>{{ $user->location }}</td>
                                                                <td>{{ $user->In_debt }}</td>
                        
                        
                        
                        
                        
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            <br>
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button"
                                                onclick="printDiv()">
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
