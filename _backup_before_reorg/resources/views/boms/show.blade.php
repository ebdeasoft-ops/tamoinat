@extends('layouts.master')
@section('css')
@section('title') {{ __('home.boms') }} - {{ $bom->code }} @stop
@endsection

@section('content')
@php $isAr = app()->getLocale() == 'ar'; @endphp

<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('boms.index') }}">{{ __('home.boms') }}</a> / {{ $bom->code }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content d-print-none">
        <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-info btn-sm ml-2">
            <i class="fa fa-edit"></i> {{ __('home.edit') }}
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm ml-2">
            <i class="fa fa-print"></i> {{ $isAr ? 'طباعة' : 'Print' }}
        </button>
        <a href="{{ route('boms.index') }}" class="btn btn-secondary btn-sm ml-2">
            <i class="fa fa-arrow-right"></i> {{ __('home.cancel') }}
        </a>
    </div>
</div>

<!-- بيانات الشجرة -->
<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ $bom->name }}</h4>
                @if($bom->is_active)
                    <span class="badge badge-success tx-14">{{ __('home.active') }}</span>
                @else
                    <span class="badge badge-danger tx-14">{{ __('home.disabled') }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>{{ __('home.bom_code') }}:</strong> {{ $bom->code }}</p>
                        <p><strong>{{ __('home.bom_name') }}:</strong> {{ $bom->name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.finished_product') }}:</strong> {{ $bom->finishedProduct->product_name ?? '---' }}</p>
                        <p><strong>{{ __('home.output_qty') }}:</strong> <span class="badge badge-primary tx-14">{{ $bom->output_quantity }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.item_notes') }}:</strong> {{ $bom->notes ?: '---' }}</p>
                        <p><strong>{{ $isAr ? 'عدد الخامات' : 'Materials count' }}:</strong> {{ $bom->items->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- المواد الخام -->
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.raw_materials_required') }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-md-nowrap text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('home.raw_material') }}</th>
                                <th>{{ __('home.quantity') }}</th>
                                <th>{{ __('home.unit') }}</th>
                                <th>{{ $isAr ? 'نسبة الهالك %' : 'Scrap %' }}</th>
                                <th>{{ $isAr ? 'الكمية شاملة الهالك' : 'Qty incl. scrap' }}</th>
                                <th>{{ __('home.item_notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bom->items as $i => $item)
                                @php
                                    $scrap = (float) ($item->scrap_percentage ?? 0);
                                    $grossQty = $item->quantity * (1 + $scrap / 100);
                                    $unitName = $item->unit ? ($isAr ? $item->unit->name_ar : ($item->unit->name_en ?: $item->unit->name_ar)) : '---';
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->rawMaterial->product_name ?? '---' }}</td>
                                    <td>{{ rtrim(rtrim(number_format($item->quantity, 4, '.', ''), '0'), '.') }}</td>
                                    <td>{{ $unitName }}</td>
                                    <td>{{ $scrap }}</td>
                                    <td class="font-weight-bold">{{ rtrim(rtrim(number_format($grossQty, 4, '.', ''), '0'), '.') }}</td>
                                    <td>{{ $item->notes ?: '---' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">{{ $isAr ? 'لا توجد خامات مضافة لهذه الشجرة' : 'No materials added to this BOM' }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
