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
    معاينه طباعة للموارد
@stop
@section('page-header')
    <!-- breadcrumb -->
   
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12 mt-5">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                    <div class="invoice-header">

<div class="billed-from">
    {{__('home.VATinvoice')}}
    <p>{{__('home.TaxNumber')}}</p>
    <p>{{__('home.S.T')}}</p>

</div>
<div>
    <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logoprintpage.png') }}" class="logo-1" alt="logo"></a>

</div>


<div class="billed-from">
    <br>
    <p>{{__('home.cam_name_owner')}}</p>
    <p>{{__('home.address')}}</p>
   
</div><!-- billed-from -->
</div><!-- invoice-header -->

                        <div class="row mg-t-12">
                            <div class="col-md">
                            <div class="col-md">



                                <div class="table-responsive mg-t-30 table-padding">
                                    <table class="table text-center table-invoice border text-md-nowrap mb-0 table-bordered table-striped" id="tableTotalPrice"
                                        name="tableTotalPrice"width="50%">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0"><span>رقم الفاتورة</span></th>
                                                <th class="border-bottom-0"><span>{{$data['productsdata'][0]->supllier->id}}</span></th>
                                            </tr>
                                        </thead>
        
                                        <body>
                                      

                                            <tr>
                                                <td><span>تاريخ الاصدار</span></td>
                                                <td><span><?php echo date("Y-m-d h:i") ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><span>اسم الموارد  </span></td>
                                                <td><span>{{$data['supllierdata']->name}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>العنوان</span></td>
                                                <td><span>{{$data['supllierdata']->location}}</span></td>
                                            </tr>

                                            <tr>
                                                <td><span>رقم الجوال </span></td>
                                                <td><span>{{$data['supllierdata']->phone}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span> اسم الشركة </span></td>
                                                <td><span>  {{$data['supllierdata']->comp_name}}</span></td>
                                            </tr>
        
                                        </body>
        
                                    </table>

                                </div>    


                                
                                {{-- <p class="invoice-info-row"><span>رقم الفاتورة</span>
                                    <span>{{$data['productsdata'][0]->supllier->id}}</span></p> 
                                    @if($order==0)

                                    <p class="invoice-info-row"><span> طريقة الدفع</span>
                                    <?php
                            
                            //  $paymethod='';
                            //  if($data['pay']== "Cash"){
                            //      $paymethod= __('report.cash');
                            //  }
                            //  elseif($data['pay']== "Shabka"){
                            //      $paymethod=__('report.shabka');

                            //  }
                            //  else{
                            //      $paymethod= __('report.credit');

                            //  }
                            //  ?>
                                    <span>{{ $paymethod}}</span></p>
                                    @endif
                                <p class="invoice-info-row"><span>تاريخ الاصدار</span>
                                    <span><?php echo date("Y-m-d h:i") ?></span></p>
                                <p class="invoice-info-row"><span>اسم الموارد  </span>
                                    <span>{{$data['supllierdata']->name}}</span></p>
                                    <p class="invoice-info-row"><span>العنوان</span>
                                    <span>{{$data['supllierdata']->location}}</span></p> 
                                     <p class="invoice-info-row"><span>رقم الجوال </span>
                                    <span>{{$data['supllierdata']->phone}}</span></p>
                                  
                                    <p class="invoice-info-row"><span> اسم الشركة </span>
                                    <span>  {{$data['supllierdata']->comp_name}}</span></p> --}}
                            </div>
                            </div>
                           
                        </div>
                        <div class="table-responsive mg-t-40">
                            <table class="table table-bordered table-striped text-center table-invoice border text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th class="wd-20p">#</th>
                                        <th class="wd-40p"> {{ __('home.productNo') }}</th>
                                        <th class="wd-40p">{{ __('home.product') }}</th>
                                        <th class="tx-center"> {{__('home.quantity')}} </th>
                                        <th class="tx-center"> {{__('home.price')}} </th>
                                        <th class="tx-center"> {{__('home.addedValue')}}    </th>
                                        <th class="tx-center"> {{__('home.total')}}    </th>
                                        <th class="tx-center">  </th>
                                       
                                       

                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;
                                $totalprice=0;
                                $totalAddedvalue=0; ?>

                                @foreach ($data['productsdata'] as $product)
                                <?php $i++;
                                $totalprice+=$product->purchasingـprice  *$product->numberofpice;
                                $totalAddedvalue+=$product->Added_Value*$product->numberofpice;
                                ?>
                                @if($product->numberofpice!=0)

                                    <tr>
                                    <td>{{ $i }}</td>

                                        <td dir=ltr>{{$product->productData->barcode}}</td>
                                        <td class="tx-12">{{$product->product_name}}</td>
                                        <td class="tx-center">{{ $product->numberofpice}}</td>
                                        <td class="tx-center">{{ $product->purchasingـprice}}</td>
                                        <td class="tx-center">{{ $product->Added_Value}}</td>
                                        <td class="tx-center">{{ ($product->Added_Value*$product->numberofpice)+($product->purchasingـprice  *$product->numberofpice)}}</td>
                                        <td class="tx-center"></td>
                                       
                                    </tr>
                                    @endif
                                    @endforeach

                              
                                   
                                </tbody>
                            </table>
                        </div>

                        
                        <br>
                        <div class="table-responsive mg-t-30 table-padding">
                            <table class="table table-bordered table-striped text-center table-invoice border text-md-nowrap mb-0" id="tableTotalPrice" name="tableTotalPrice"width="50%">
	<col style="width:15%">
	<col style="width:15%">
	<col style="width:15%">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                    <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                    <th class="border-bottom-0">{{ __('home.total') }} </th>

                                </tr>
                            </thead>
                            <body>
<tr>
<td>  {{$totalprice}}</td>
<td>{{$totalAddedvalue}}</td>
<td>{{$totalAddedvalue+ $totalprice}}</td>
</tr>
                            
                                </body>

                            </table>
                        <br>
                        <br>
                        <br>
                        

                        
                        
                        <hr class="mg-b-40">



                        <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i
                                class="mdi mdi-printer ml-1"></i>طباعة</button>
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
