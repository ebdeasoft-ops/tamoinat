@extends('layouts.master')
@section('title')
{{ __('home.catogeries') }}
@stop

@section('content')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">{{__('home.addnewCatgories')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('inv_itemcard_categories.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        {{__('home.categoriesname')}}
                        <input name="name" id="name" class="form-control" value="{{ old('name') }}" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label> {{__('home.stautes')}}</label>

                        <select name="active" id="active" class="form-control">
                            <option @if(old('active')==1) selected="selected" @endif value="1"> {{ __('users.active') }}</option>
                            <option @if(old('active')==0 and old('active')!="" ) selected="selected" @endif value="0"> {{ __('users.disactive') }}</option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm"> {{__('home.confirm')}}</button>
                        <a href="{{ route('inv_itemcard_categories.index') }}" class="btn btn-sm btn-danger">{{__('users.back')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection