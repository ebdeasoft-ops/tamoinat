@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
@section('title')
    {{ __('roles.Permission_update') }}
@stop
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto" style="color: white">{{ __('roles.Permission_update') }}</h4>
        </div>
    </div>
@endsection

@section('content')
    @php $isAr = app()->getLocale() == 'ar'; @endphp

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id]]) !!}
    <div class="card mg-b-20" style="border-radius:12px;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="font-weight-bold">{{ $isAr ? 'اسم الدور' : 'Role name' }} <span class="text-danger">*</span></label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => $isAr ? 'مثال: كاشير فرع' : 'e.g. Branch cashier']) !!}
                </div>
            </div>

            @include('admin.roles.partials.permissions-picker', ['permission' => $permission, 'checked' => array_values($rolePermissions)])

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-main-primary px-5">
                    <i class="fa fa-save ml-1 mr-1"></i> {{ $isAr ? 'حفظ' : 'Save' }}
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-light px-4">{{ $isAr ? 'رجوع' : 'Back' }}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
