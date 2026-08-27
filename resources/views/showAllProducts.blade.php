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
{{ __('home.stock') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->

    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">&nbsp;&nbsp;{{ __('home.stock') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>

    <!-- breadcrumb -->
    @endsection
    @section('content')

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


                    {{ csrf_field() }}



                    <?php $i = 0; ?>
                    <div class="col-xl-12">
                        <div style="border-radius: 10px" class="card mg-b-20">
                            <div class="card-body p-5">


                                <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                                    <label for="inputName" style="font-weight: bold" class="control-label parent-label"> {{__('home.searchaboutproduct')}} </label>
                                    <input dir="ltr" type="text" class="form-control parent-input" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                                </div>
                                <br>
                                <div id="ajax_responce_serarchDiv">
                                    <table class="table text-md-nowrap text-center our-table" id="example2" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                                        <col style="width:5%">
                                        <col style="width:15%">
                                        <col style="width:20%">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:15%">
                                        <col style="width:15%">


                                        <thead>
                                            <tr>
                                                <th style="font-size: 15px" class="border-bottom-0">#</th>
                                                <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                                                <th style="font-size: 15px" class="border-bottom-0">{{__('home.productlocation')}}</th>
                                                <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                                <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                                <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>



                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <?php $i = 0;
                                            ?>

                                            <?php $i++ ?>

                                            <tr>
                                                <td id="tableData"  dir=ltr>-</td>
                                                <td id="tableData"  dir=ltr>-</td>
                                                <td id="tableData"  data-target="product_name">-</td>
                                                <td id="tableData"  data-target="product_name">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                    
                                    <div>

                                    </div>



                                </div>

                            </div>
                        </div>
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
@endsection
@section('js')
<!-- Internal Data tables -->

<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
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
</script>



{{-- Update ( 24/4/2023 ) --}}

<script>
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        $.ajax({
            url: " {{URL::to('searchAllproductpaginatenew')}}" + "/" + searchtext,
            type: "GET",
            dataType: "html",
            success: function(products) {
                $("#ajax_responce_serarchDiv").html(products);


            },
        });
    }
   
    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
        var search_by_text = $("#search_by_text").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function(data) {
                console.log(data)
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });

    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
        var search_by_text = $("#search_by_text").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function(data) {
                console.log(data)
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });
</script>

{{-- End Update ( 24/4/2023 ) --}}







<script>
    $(document).ready(function() {



        // Update ( 24/4/2023 )

        $.ajax({
            url: " {{URL::to('Allproductpaginatenew')}}",
            type: "GET",
            dataType: "html",
            success: function(products) {
                $("#ajax_responce_serarchDiv").html(products);


            },
        });


        // $("#nextPage").click(function(e) {
        //     url = $('#nextPageValue').val().split('page=')[1];
        //     $.ajax({
        //         url: " {{URL::to('showAllproductpaginate')}}" + "?page=" + url,
        //         type: "GET",
        //         dataType: "json",
        //         success: function(data) {


        //             $('#previousPagevalue').val(data['prev_page_url'])
        //             $('#nextPageValue').val(data['next_page_url'])
        //             $('#currentpage').val(data['current_page'])
        //             let table = document.getElementById("SearchProductTable");
        //             var tableHeaderRowCount = 1;

        //             var rowCount = table.rows.length;

        //             for (var i = tableHeaderRowCount; i < rowCount; i++) {
        //                 table.deleteRow(tableHeaderRowCount);
        //             }
        //             data['data']['otherdata'].forEach(async (product) => {
        //                 Product_id = product['id'],
        //                     Product_Code = product['Product_Code'],
        //                     id = product['id'],
        //                     product_name = product['product_name'],
        //                     purchasingـprice = product['purchasingـprice']
        //                 sale_price = product['sale_price']
        //                 numberofpice = product['numberofpice']
        //                 Product_Location = product['Product_Location']








        //                 let row = table.insertRow(-1); // We are adding at the end
        //                 let c1 = row.insertCell(0);
        //                 let c2 = row.insertCell(1);
        //                 let c3 = row.insertCell(2);
        //                 let c4 = row.insertCell(3);
        //                 let c5 = row.insertCell(4);
        //                 let c6 = row.insertCell(5);
        //                 let c7 = row.insertCell(6);
        //                 let c8 = row.insertCell(7);

        //                 c1.innerText = Product_id

        //                 c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
        //                 c3.innerHTML = product_name
        //                 c4.innerHTML = product['branch']
        //                 c5.innerText = Product_Location
        //                 c6.innerText = numberofpice
        //                 c7.innerHTML = purchasingـprice
        //                 c8.innerHTML = sale_price







        //             });

        //         },
        //     });
        // });

        // $("#previousPage").click(function(e) {

        //     url = $('#previousPagevalue').val().split('page=')[1];

        //     if (url != '') {
        //         $.ajax({
        //             url: " {{URL::to('showAllproductpaginate')}}" + "?page=" + url,
        //             type: "GET",
        //             dataType: "json",
        //             success: function(data) {

        //                 $('#previousPagevalue').val(data['prev_page_url'])
        //                 $('#nextPageValue').val(data['next_page_url'])
        //                 $('#currentpage').val(data['current_page'])
        //                 let table = document.getElementById("SearchProductTable");
        //                 var tableHeaderRowCount = 1;

        //                 var rowCount = table.rows.length;

        //                 for (var i = tableHeaderRowCount; i < rowCount; i++) {
        //                     table.deleteRow(tableHeaderRowCount);
        //                 }
        //                 data['data']['otherdata'].forEach(async (product) => {
        //                     Product_id = product['id'],
        //                         Product_Code = product['Product_Code'],
        //                         id = product['id'],
        //                         product_name = product['product_name'],
        //                         purchasingـprice = product['purchasingـprice']
        //                     sale_price = product['sale_price']
        //                     numberofpice = product['numberofpice']
        //                     Product_Location = product['Product_Location']








        //                     let row = table.insertRow(-1); // We are adding at the end
        //                     let c1 = row.insertCell(0);
        //                     let c2 = row.insertCell(1);
        //                     let c3 = row.insertCell(2);
        //                     let c4 = row.insertCell(3);
        //                     let c5 = row.insertCell(4);
        //                     let c6 = row.insertCell(5);
        //                     let c7 = row.insertCell(6);
        //                     let c8 = row.insertCell(7);

        //                     c1.innerText = Product_id

        //                     c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
        //                     c3.innerHTML = product_name
        //                     c4.innerHTML = product['branch']
        //                     c5.innerText = Product_Location
        //                     c6.innerText = numberofpice
        //                     c7.innerHTML = purchasingـprice
        //                     c8.innerHTML = sale_price









        //                 });

        //             },
        //         });
        //     } else {
        //         alert('url null not fount pervoius')
        //     }
        // });

        // End Update ( 24/4/2023 )



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
                        $('#clientName').val(data['name']);
                        $('#address').val(data['address']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['notes']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

    $('select[name="searchproductNo"]').on('change', function() {
        console.log('AJAX load   work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');

            $.ajax({
                url: "{{ URL::to('getproduct') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success");
                    console.log(data['name']);
                    $('#quentity').val(data['numberofpice']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
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