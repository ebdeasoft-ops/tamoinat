@extends('layouts.master')

@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
    <style>
        .role-check-box {
            border: 1px solid #e4e6ef;
            border-radius: 10px;
            padding: 16px 18px;
        }

        .role-check-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-inline-end: 22px;
            margin-bottom: 10px;
        }

        .role-check-item input {
            width: 18px;
            height: 18px;
        }
    </style>
@stop

@section('title')
    {{ __('users.updateuser') }}
@stop

@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('users.updateuser') }}</h4>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->
    </div>
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>خطأ</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body p-5">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a style="background-color: #FF4F1F" class="btn btn-primary btn-sm"
                                href="{{ route('users.index') }}">{{ __('users.back') }}</a>
                        </div>
                    </div>
                    <br>

                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}

                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6" id="fnWrapper">
                            <label class="parent-label">{{ __('users.username') }}<span class="tx-danger">*</span></label>
                            {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label class="parent-label">{{ __('users.email') }} <span class="tx-danger">*</span></label>
                            {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>

                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label class="parent-label"> {{ __('users.password') }} <span class="tx-danger">*</span></label>
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label class="parent-label"> {{ __('users.confirmـpassword') }} <span class="tx-danger">*</span></label>
                            {!! Form::password('confirm-password', ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="row row-sm mg-b-20">
                        <div class="col-lg-6">
                            <label class="form-label parent-label"> {{ __('users.Userـstatus') }}</label>
                            <select name="active" id="active" class="form-control parent-input">
                                <option value="1" {{ $user->Status == 1 ? 'selected' : '' }}>{{ __('users.active') }}</option>
                                <option value="0" {{ $user->Status == 0 ? 'selected' : '' }}>{{ __('users.disactive') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label parent-label">{{ __('users.branch') }} </label>
                            <select name="branchs_id" id="branchs_id" class="form-control parent-input">
                                @foreach (App\Models\branchs::get() as $section)
                                    <option value="{{ $section->id }}"
                                        {{ $user->branchs_id == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mg-b-20">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label class="parent-label d-block mb-2">{{ __('users.User_roles') }}</label>

                            <div class="role-check-box d-flex flex-wrap">
                                @foreach ($roles as $role)
                                    <div class="role-check-item">
                                        <input type="checkbox" name="roles_name[]" id="role_{{ $role }}"
                                            value="{{ $role }}"
                                            {{ in_array($role, $userRole) ? 'checked' : '' }}>
                                        <label for="role_{{ $role }}" class="mb-0">{{ $role }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mg-t-30">
                        <button class="btn btn-main-primary pd-x-20 print-style" type="submit">
                            {{ __('roles.update') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none"
                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <!-- Internal Nice-select js-->
    <script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>

    <!-- Internal Parsley.min js -->
    <script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
    <!-- Internal Form-validation js -->
    <script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>

    <script>
        $(document).ready(function() {
            var timeout = 4000; // in milliseconds
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection