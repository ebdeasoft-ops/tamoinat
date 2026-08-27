@extends('layouts.master')
@section('title')
{{ __('home.updatecategories') }}
@stop


@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">{{ __('home.updatecategories') }}
</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            @if (@isset($data) && !@empty($data))
            <form action="{{ route('inv_itemcard_categories.update',$data['id']) }}" method="post" >
               @method('PUT')
               @csrf
               <div class="form-group">
                  <label>   {{__('home.categoriesname')}}</label>
                  <input name="name" id="name" class="form-control" value="{{ old('name',$data['name']) }}"   >
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group">
                  <label>   {{__('home.stautes')}}</label>
                  <select name="active" id="active" class="form-control">
                     <option {{  old('active',$data['active'])==1 ? 'selected' : ''}}   value="1">  {{ __('users.active') }}</option>
                     <option {{ old('active',$data['active'])==0 ? 'selected' : ''}}  value="0"> {{ __('users.disactive') }}</option>
                  </select>
                  @error('is_master')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-sm">  {{__('home.confirm')}}</button>
                  <a href="{{ route('inv_itemcard_categories.index') }}" class="btn btn-sm btn-danger">{{__('users.back')}}</a>    
               </div>
            </form>
            @else
            <div class="alert alert-danger">
               عفوا لاتوجد بيانات لعرضها !!
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection