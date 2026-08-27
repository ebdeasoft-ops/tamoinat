@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    {{ __('supprocesses.addproduct') }}@stop

@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('supprocesses.addproduct') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('addProduct'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <br>

            <strong>{{ session()->get('addProduct') }}</strong>
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

        <div class="col-lg-12 col-md-12">
            <div style="border-end-end-radius: 10px;border-end-start-radius:10px" class="card pt-5">
                <div class="card-body">
                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'addnewProduct')) }}"
                        method="post" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        {{-- 1 --}}


                        <div class="row mb-2">
                            <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_name_ar') }}</label>
                                <input type="text" class="form-control parent-input" id="product_name_ar" name="product_name_ar"
                                    title="{{ __('supprocesses.product_name_ar') }}" required>
                            </div>


                            <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.product_code') }}</label>
                                <input type="text" class="form-control parent-input" id="product_code" name="product_code"
                                    type="text" dir="ltr" onkeyup="convertToNumber()"
                                    title="{{ __('supprocesses.product_code') }}" required>
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row mb-2">
                            <div class="col-lg-4 mb-2">
                                <label for="inputName"
                                    class="control-label parent-label">{{ __('supprocesses.product_branch') }}</label>
                                <select name="Section" class="form-control parent-input" onclick="console.log($(this).val())"
                                    onchange="console.log('change is firing')">
                                    <!--placeholder-->
                                    @foreach (App\Models\branchs::get() as $section)
                                        <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.unit') }}</label>
                                <select name="unit" class="form-control parent-input">
                                    <!--placeholder-->
                                    <div class="row">

                                        <option value="piece"> {{ __('home.unitـpiece') }}</option>
                                        <option value="box">{{ __('home.unit_box') }}</option>
                                </select>
                            </div>


                            <div class="col-lg-4 mb-2" style="direction: ltr !important;">

                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_location') }}</label>
                                <input dir="ltr" style="direction:LTR !important ;text-align:start!important;"
                                    type="text" class="form-control parent-input" id="product_location" name="product_location"
                                    title="{{ __('supprocesses.product_location') }}" required>
                            </div>



                        </div>


                        {{-- 3 --}}





                        {{-- 5 --}}
                        <div class="row mb-2">

                            <div class="col-lg-4 mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.minmum_quantity_stock_alart') }}</label>
                                <input type="text" class="form-control parent-input" id="minmum_quantity_stock_alart"
                                    name="minmum_quantity_stock_alart" onkeyup="minmum_quantity_stock_alartConvert()"
                                    title="{{ __('supprocesses.minmum_quantity_stock_alart') }}" value=2 required>
                            </div>







                            <div class="col-lg-8 mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_notes') }}</label>
                                <input type="text" class="form-control parent-input" id="product_notes" name="product_notes"
                                    title="{{ __('supprocesses.product_notes') }}">
                            </div>

                            <div class="col-lg-4 mb-2">
                                <input type="text" class="form-control parent-input" id="product_name_en" name="product_name_en"
                                    title=" {{ __('supprocesses.product_name_en') }}" hidden>
                            </div>

                        </div><br>



                        <div class="d-flex justify-content-center">
                            <button style="background-color: #419BB2" type="submit" class="btn btn-primary">
                                {{ __('supprocesses.save_data') }}
                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </button>
                        </div>


                    </form>
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
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>
    <script>
        function minmum_quantity_stock_alartConvert() {
            var input = document.getElementById("minmum_quantity_stock_alart");
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

           

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
     
            $('select[name="Section"]').on('change', function() {
                var SectionId = $(this).val();
                if (SectionId) {
                    $.ajax({
                        url: "{{ URL::to('section') }}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="product"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="product"]').append('<option value="' +
                                    value + '">' + value + '</option>');
                            });
                        },
                    });

                } else {
                    console.log('AJAX load did not work');
                }
            });

        });
    </script>



    <script>
        function convertToNumber() {
            var input = document.getElementById("product_code");
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
        function myFunction() {

            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
            var Discount = parseFloat(document.getElementById("Discount").value);
            var Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value);
            var Value_VAT = parseFloat(document.getElementById("Value_VAT").value);

            var Amount_Commission2 = Amount_Commission - Discount;


            if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {

                alert('يرجي ادخال مبلغ العمولة ');

            } else {
                var intResults = Amount_Commission2 * Rate_VAT / 100;

                var intResults2 = parseFloat(intResults + Amount_Commission2);

                sumq = parseFloat(intResults).toFixed(2);

                sumt = parseFloat(intResults2).toFixed(2);

                document.getElementById("Value_VAT").value = sumq;

                document.getElementById("Total").value = sumt;

            }

        }
    </script>


@endsection
