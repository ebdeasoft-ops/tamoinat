@extends('layouts.master')
@section('css')
<!--- Internal Select2 css-->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    .addproduct-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(35, 57, 93, 0.08);
        overflow: hidden;
    }

    .addproduct-section {
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 12px;
        margin-bottom: 18px;
        overflow: hidden;
    }

    .addproduct-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #23395D;
        color: #fff;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 15px;
    }

    .addproduct-section-title .icon-badge {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #419BB2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .addproduct-section-body {
        padding: 18px 16px 6px;
    }

    .addproduct-field label.control-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #23395D;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .addproduct-field label.control-label .field-icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: rgba(65, 155, 178, 0.12);
        color: #23395D;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }

    .addproduct-field .form-control,
    .addproduct-field .select2-container .select2-selection--single {
        border-radius: 8px;
        border: 1px solid #dfe3ea;
        min-height: 42px;
    }

    .addproduct-field .select2-container .select2-selection--single {
        display: flex;
        align-items: center;
        padding: 0 8px;
    }

    .addproduct-field .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        padding-inline-start: 4px;
    }

    .addproduct-field .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }

    .addproduct-field .form-control:focus {
        border-color: #419BB2;
        box-shadow: 0 0 0 3px rgba(65, 155, 178, 0.15);
    }

    .addproduct-hint {
        display: block;
        color: #8992a3;
        font-size: 12px;
        margin-top: 4px;
    }

    .required-star {
        color: #e35d5d;
    }

    #division_unit_count_row {
        transition: all .15s ease-in-out;
    }

    .addproduct-photo-box {
        border: 1px dashed #c9d1e0;
        border-radius: 10px;
        padding: 10px;
        background: #f8fafc;
    }

    .addproduct-save-btn {
        background: linear-gradient(90deg, #23395D, #419BB2);
        border: none;
        border-radius: 10px;
        padding: 10px 34px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .addproduct-create-group-btn {
        background-color: #23395D !important;
        border-radius: 8px !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endsection
@section('title')
{{ __('supprocesses.addproduct') }}@stop

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><i class="fa fa-box-open"></i> {{ __('supprocesses.addproduct') }}</h4>
            </div>
        </div>

                        <div class="choose-product">

                        <button type="button" class="modal-effect btn btn-sm text-white p-2 m-1 addproduct-create-group-btn" data-effect="effect-scale" data-toggle="modal" href="#creategroup" title="تحديد">
                            <i class="fa fa-plus"></i> {{ __('home.create_group') }}
                        </button>



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
            <div class="card addproduct-card pt-4">
                <div class="card-body">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'addnewProduct')) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}

                        {{-- القسم الأول: البيانات الأساسية --}}
                        <div class="addproduct-section">
                            <div class="addproduct-section-title">
                                <span class="icon-badge"><i class="fa fa-info-circle"></i></span>
                                {{ __('home.basic_product_data') }}
                            </div>
                            <div class="addproduct-section-body">
                                <div class="row mb-2">
                                    <div class="col-lg-4 mb-3 addproduct-field">
                                        <label for="product_name_ar" class="control-label">
                                            <span class="field-icon"><i class="fa fa-font"></i></span>
                                            {{ __('supprocesses.product_name_ar') }} <span class="required-star">*</span></label>
                                        <input type="text" class="form-control" id="product_name_ar" name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}" onkeyup="translateNameToEnglish()" required>
                                    </div>
                                    <div class="col-lg-4 mb-3 addproduct-field">
                                        <label for="product_name_en" class="control-label">
                                            <span class="field-icon"><i class="fa fa-globe"></i></span>
                                            {{ __('supprocesses.product_name_en') }} <span class="required-star">*</span></label>
                                        <input type="text" class="form-control" id="product_name_en" name="product_name_en" onkeyup="translateNameToArbic()" required>
                                    </div>
                                    <div class="col-lg-4 mb-3 addproduct-field">
                                        <label for="product_code" class="control-label">
                                            <span class="field-icon"><i class="fa fa-barcode"></i></span>
                                            {{ __('supprocesses.product_code') }}</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code" dir="ltr" onkeyup="convertToNumber()" title="{{ __('supprocesses.product_code') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- القسم الثاني: التصنيف والربط --}}
                        <div class="addproduct-section">
                            <div class="addproduct-section-title">
                                <span class="icon-badge"><i class="fa fa-sitemap"></i></span>
                                {{ __('home.prices_and_category') }}
                            </div>
                            <div class="addproduct-section-body">
                                <div class="row mb-2">
                                    <div class="col-lg-2 mb-3 addproduct-field">
                                        <label for="Section" class="control-label">
                                            <span class="field-icon"><i class="fa fa-code-branch"></i></span>
                                            {{ __('supprocesses.product_branch') }} <span class="required-star">*</span></label>
                                        <select name="Section" id="Section" class="form-control">
                                            <!--placeholder-->
                                    @foreach (App\Models\branchs::where('id', Auth()->user()->branchs_id)->get() as $section)
                                            <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 mb-3 addproduct-field">
                                        <label for="product_group" class="control-label">
                                            <span class="field-icon"><i class="fa fa-tags"></i></span>
                                            {{ __('home.groups') }}</label>
                                        <select name="product_group" id="product_group" class="form-control select2">
                                            <!--placeholder-->
                                           @foreach (App\Models\products_group::get() as $section)
                                            <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 mb-3 addproduct-field">
                                        <label for="MAINproduct" class="control-label">
                                            <span class="field-icon"><i class="fa fa-boxes"></i></span>
                                            {{ __('home.parent_product') }}</label>
                                        <select name="MAINproduct" id="MAINproduct" class="form-control select2">
                                            <!--placeholder-->
                                            <option value=0> {{ __('home.no_parent_product') }}</option>


                                        </select>
                                        <small class="addproduct-hint">{{ __('home.parent_product_hint') }}</small>
                                    </div>
                                    <div class="col-lg-2 mb-3 addproduct-field">
                                        <label for="unit" class="control-label">
                                            <span class="field-icon"><i class="fa fa-cube"></i></span>
                                            {{ __('home.unit') }} <span class="required-star">*</span></label>
                                        <select name="unit" id="unit" class="form-control select2" required>
                                            <option value="">{{ __('home.select_unit') }}</option>
                                            <option value="carton">{{ __('home.unit_carton') }}</option>
                                            <option value="bag">{{ __('home.unit_bag') }}</option>
                                            <option value="sack">{{ __('home.unit_sack') }}</option>
                                            <option value="piece">{{ __('home.unit_piece') }}</option>
                                            <option value="box">{{ __('home.unit_box') }}</option>
                                            <option value="case">{{ __('home.unit_case') }}</option>
                                            <option value="dozen">{{ __('home.unit_dozen') }}</option>
                                            <option value="kg">{{ __('home.unit_kg') }}</option>
                                            <option value="gram">{{ __('home.unit_gram') }}</option>
                                            <option value="liter">{{ __('home.unit_liter') }}</option>
                                            <option value="ml">{{ __('home.unit_ml') }}</option>
                                            <option value="bottle">{{ __('home.unit_bottle') }}</option>
                                            <option value="jar">{{ __('home.unit_jar') }}</option>
                                            <option value="roll">{{ __('home.unit_roll') }}</option>
                                            <option value="tray">{{ __('home.unit_tray') }}</option>
                                            <option value="gallon">{{ __('home.unit_gallon') }}</option>
                                            <option value="meter">{{ __('home.unit_meter') }}</option>
                                            <option value="unit">{{ __('home.unit_generic') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 mb-3 addproduct-field">
                                        <label for="refnumber" class="control-label">
                                            <span class="field-icon"><i class="fa fa-hashtag"></i></span>
                                            {{ __('home.refnumber') }}</label>
                                        <input type="text" class="form-control" id="refnumber" name="refnumber">
                                    </div>
                                </div>

                                {{-- تظهر بس لو المنتج ده مرتبط بمنتج أب (كرتون) من قائمة MAINproduct --}}
                                <div class="row mb-2" id="division_unit_count_row" style="display:none;">
                                    <div class="col-lg-3 mb-3 addproduct-field">
                                        <label for="division_unit_count" class="control-label">
                                            <span class="field-icon"><i class="fa fa-layer-group"></i></span>
                                            {{ __('home.division_unit_count') }}</label>
                                        <input type="number" min="1" step="1" class="form-control" id="division_unit_count" name="division_unit_count" value="1" title="{{ __('home.division_unit_count') }}">
                                        <small class="addproduct-hint">{{ __('home.division_unit_count_hint') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- القسم الثالث: المخزون والموقع --}}
                        <div class="addproduct-section">
                            <div class="addproduct-section-title">
                                <span class="icon-badge"><i class="fa fa-warehouse"></i></span>
                                {{ __('home.storage_and_notes') }}
                            </div>
                            <div class="addproduct-section-body">
                                <div class="row mb-2">
                                    <div class="col-lg-3 mb-3 addproduct-field" style="direction: ltr !important;">
                                        <label for="product_location" class="control-label" style="direction: rtl;">
                                            <span class="field-icon"><i class="fa fa-map-marker-alt"></i></span>
                                            {{ __('supprocesses.product_location') }} <span class="required-star">*</span></label>
                                        <input dir="ltr" style="direction:LTR !important ;text-align:start!important;" type="text" class="form-control" id="product_location" name="product_location" title="{{ __('supprocesses.product_location') }}" required>
                                    </div>
                                    <div class="col-lg-3 mb-3 addproduct-field">
                                        <label for="minmum_quantity_stock_alart" class="control-label">
                                            <span class="field-icon"><i class="fa fa-exclamation-triangle"></i></span>
                                            {{ __('supprocesses.minmum_quantity_stock_alart') }} <span class="required-star">*</span></label>
                                        <input type="text" class="form-control" id="minmum_quantity_stock_alart" name="minmum_quantity_stock_alart" onkeyup="minmum_quantity_stock_alartConvert()" title="{{ __('supprocesses.minmum_quantity_stock_alart') }}" value="2" required>
                                    </div>

                                    <div class="col-lg-3 mb-3 addproduct-field">
                                        <label for="Item_img" class="control-label">
                                            <span class="field-icon"><i class="fa fa-camera"></i></span>
                                            {{ __('home.photo') }}</label>
                                        <div class="addproduct-photo-box">
                                            <input autocomplete="off" onchange="readURL(this)" type="file" id="Item_img" name="Item_img" class="form-control-file">
                                        </div>
                                        @error('active')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-3 mb-3 addproduct-field">
                                        <label for="product_notes" class="control-label">
                                            <span class="field-icon"><i class="fa fa-sticky-note"></i></span>
                                            {{ __('supprocesses.product_notes') }}</label>
                                        <input type="text" class="form-control" id="product_notes" name="product_notes" title="{{ __('supprocesses.product_notes') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2">
                            <button type="submit" class="btn text-white addproduct-save-btn">
                                <i class="fa fa-check-circle"></i>
                                {{ __('supprocesses.save_data') }}
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



<div class="modal p-3" id="creategroup">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa fa-tags"></i> {{ __('home.create_group') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-2">
                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.groub_ar') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input" id="groub_ar" name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}" onkeyup="groub_translateNameToEnglish()"  required>
                    </div>


                     <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.group_en') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input" id="groub_en" name="product_name_en" title="{{ __('supprocesses.product_name_en') }}" onkeyup="groub_translateNameToArbic()"  required>
                            </div>



                </div>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" onclick="createnewgroupajax()">
                        {{ __('supprocesses.save_data') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg>
                    </button>
                </div>
        </div>

    </div>
</div>

                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">



 <div class="modal fade product-selection" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="massagesave" name="massagesave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
                <div class="modal-content">

                    <div class="modal-body" style="justify-content: center;">


 <center><img style="width:250px;height:250px;" class="custom_img" src="{{ asset('assets/admin/uploads/done.png') }}" >

</center>




                        </div>


                    </div>


                </div>
            </div>

        </div>





@endsection
@section('js')
<!-- Internal Select2 js-->
{{-- jQuery (مرة واحدة فقط – غالبًا موجود في layout، لو موجود هناك احذف ده) --}}

{{-- Select2 --}}
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

{{-- ملفات المشروع --}}


<script>
    $('#MAINproduct').select2({
        placeholder: 'ابحث عن المنتج الأب (الكرتون)',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: "{{ route('itemcards.search') }}",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    q: params.term,
                    branchs_id: $('#Section').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.product_name
                    }))
                };
            }
        }
    });

    // لو الفرع اتغيّر، نفضّي اختيار المنتج الأب الحالي عشان منسيبش منتج من فرع تاني متسجل بالغلط
    $('#Section').on('change', function() {
        $('#MAINproduct').val(null).trigger('change');
    });

    // إظهار/إخفاء حقل "عدد الوحدات في الكرتون" حسب اختيار المنتج الأب
    $('#MAINproduct').on('change', function() {
        var mainProductId = $(this).val();
        if (mainProductId && mainProductId != '0') {
            $('#division_unit_count_row').slideDown(150);
        } else {
            $('#division_unit_count_row').slideUp(150);
            $('#division_unit_count').val(1);
        }
    });

    $('#unit').select2({
        placeholder: '{{ __('home.select_unit') }}',
        allowClear: true,
        dir: 'rtl',
        width: '100%'
    });

    $('#product_group').select2({
        placeholder: '{{ __('home.groups') }}',
        allowClear: true,
        dir: 'rtl',
        width: '100%'
    });

function groub_translateNameToArbic(){
   var wordEnglish = $('#groub_en').val();

         jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" + wordEnglish,
            type: 'get',
            cache: false,

            success: function(request_result) {
                $('#groub_ar').val(request_result[0][0][0])
            },
            error: function() {

            }
        });


}

function groub_translateNameToEnglish(){
   var wordarbic = $('#groub_ar').val();

         jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=ar&tl=en&q=" + wordarbic,
            type: 'get',
            cache: false,

            success: function(request_result) {
                $('#groub_en').val(request_result[0][0][0])
            },
            error: function() {

            }
        });


}
function translateNameToArbic(){}


 function createnewgroupajax() {
        console.log('+++++++++++++++++++++++++++++++++create_products_group ++++++++++++++++++++++++++++++++');
        var url = " {{ URL::to('create_products_group') }}";

        var token_search = $("#token_search").val();
        if ($('#groub_ar').val() == '') {
            alert("{{ __('home.groub_ar') }}")
        } else if ($('#groub_en').val() == '') {
            alert("{{ __('home.groub_en') }}")
        } else {
console.log($('#groub_ar').val())
console.log($('#groub_en').val())
console.log(token_search)

            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                data: {
                    _token: token_search,
                    groub_ar: $('#groub_ar').val(),
                    groub_en: $('#groub_en').val(),
                },


                    success: function(data) {
                        console.log(data)
                        $('#creategroup').modal('hide');
                        $('#groub_ar').val('');
                        $('#groub_en').val('');

          $('#massagesave').modal().show();
 setTimeout(() => {
         $('#massagesave').modal('hide');

        }, 1000);

                    },
                      error: function(response) {
console.log(response)
                }

            });







        }


    }




function translateNameToEnglish(){}

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
                      error: function(response) {
                console.log(response)

                }
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