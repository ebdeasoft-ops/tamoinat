@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        .perm-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:16px; }
        @media (max-width: 991px) { .perm-grid { grid-template-columns:1fr; } }
        .perm-card { border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px 16px; }
        .perm-title { font-weight:700; font-size:14px; color:#0f172a; display:flex; align-items:center; gap:8px; margin-bottom:8px; }
        .perm-title i { color:#64748b; font-size:13px; }
        .perm-title small { color:#94a3b8; font-weight:500; }
        .perm-row { display:flex; align-items:center; gap:8px; padding:3px 0; font-size:13px; color:#334155; }
        .perm-row i { color:#16a34a; font-size:12px; }
        .perm-row.is-main { font-weight:700; color:#0f172a; }
        .role-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
        .role-head h4 { margin:0; font-weight:700; }
        .role-head small { color:#64748b; }
    </style>
@section('title')
    {{ __('roles.Viewـpermissions') }}
@stop
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto" style="color: white">{{ __('roles.Viewـpermissions') }}</h4>
        </div>
    </div>
@endsection

@section('content')
@php
    $isAr   = app()->getLocale() == 'ar';
    $groups = \App\Support\PermissionCatalog::groups($rolePermissions);
@endphp
<div class="card mg-b-20" style="border-radius:12px;">
    <div class="card-body">
        <div class="role-head">
            <div>
                <small>{{ $isAr ? 'الدور' : 'Role' }}</small>
                <h4>{{ $role->name }} <small>({{ $rolePermissions->count() }} {{ $isAr ? 'صلاحية' : 'permissions' }})</small></h4>
            </div>
            <div>
                <a class="btn btn-sm btn-info" href="{{ route('roles.edit', $role->id) }}"><i class="fa fa-edit"></i> {{ __('roles.update') }}</a>
                <a class="btn btn-sm btn-light" href="{{ route('roles.index') }}">{{ $isAr ? 'رجوع' : 'Back' }}</a>
            </div>
        </div>

        @if (count($groups))
            <div class="perm-grid">
                @foreach ($groups as $group)
                    <div class="perm-card">
                        <div class="perm-title"><i class="fa {{ $group['icon'] }}"></i> {{ $group['title'] }} <small>({{ count($group['items']) }})</small></div>
                        @foreach ($group['items'] as $item)
                            <div class="perm-row {{ $item['main'] ? 'is-main' : '' }}"><i class="fa fa-check-circle"></i> {{ $item['label'] }}</div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center mb-0">{{ $isAr ? 'لا توجد صلاحيات مسندة لهذا الدور بعد.' : 'No permissions assigned to this role yet.' }}</div>
        @endif
    </div>
</div>
@endsection
