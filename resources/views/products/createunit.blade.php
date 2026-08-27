@extends('layouts.master')
@section('title')
{{ __('home.unit') }}
@stop


@section('content')
@if ($errors!=null)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center"> {{__('home.Add')}}</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <form action="{{ route('unit.store') }}" method="post" >
               @csrf
               <div class="form-group">
                  <label>{{__('home.unitname')}}</label>
                  <input name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="ادخل اسم الوحدة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}"  >
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group">
                  <label>    {{__('home.unittype')}}</label>
                  <select name="is_master" id="is_master" class="form-control">
                     <option   @if(old('is_master')==1) selected="selected"  @endif value="1">{{__('home.parentunit')}}</option>
                     <option @if(old('is_master')==0 and old('is_master')!="" ) selected="selected"   @endif value="0">  {{__('home.partialunit')}}</option>
                  </select>
                  @error('is_master')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group">
                  <label> {{__('home.stautes')}}</label>
                  <select name="active" id="active" class="form-control">
                     <option   @if(old('active')==1) selected="selected"  @endif value="1">  {{ __('users.active') }}</option>
                     <option @if(old('active')==0 and old('active')!="" ) selected="selected"   @endif value="0"> {{ __('users.disactive') }}</option>
                  </select>
                  @error('active')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-sm"> {{__('home.confirm')}}</button>
                  <a href="{{ route('units') }}" class="btn btn-sm btn-danger">{{__('users.back')}}</a>    
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
</div>
@endsection