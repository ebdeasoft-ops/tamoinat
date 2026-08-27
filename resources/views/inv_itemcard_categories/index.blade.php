@extends('layouts.master')
@section('title')
{{ __('home.catogeries') }}
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">{{ __('home.catogeries') }}</h3>
                <input type="hidden" id="token_search" value="{{csrf_token() }}">
                <a href="{{ route('inv_itemcard_categories.create') }}" class="btn btn-sm btn-success">اضافة جديد</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="ajax_responce_serarchDiv">
                    @if (@isset($data) && !@empty($data))
                    @php
                    $i=1;
                    @endphp
                    <table id="example2" class="table our-table border mb-0 table-responsive text-center" >
                    <col style="width:2%">
                                        <col style="width:28% ">
                                        <col style="width:15%">
                                        <col style="width:20%">
                                        <col style="width:20%">
                                        <col style="width:15%">
                        <thead class="custom_thead">
                            <th>#</th>
                            <th> {{__('home.categoriesname')}}</th>
                            <th> {{__('home.statusactive')}}</th>
                            <th> {{__('home.addedDate')}}</th>
                            <th> {{__('home.updateddate')}}</th>
                            <th>{{__('home.operations')}}</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $info )
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $info->name }}</td>
                                <td>@if($info->active==1)
                                    {{__('users.active') }}
                                    @else
                                    {{ __('users.disactive') }} @endif
                                </td>
                                <td>
                                    @php
                                    $dt=new DateTime($info->created_at);
                                    $date=$dt->format("Y-m-d");
                                    $time=$dt->format("h:i");
                                    $newDateTime=date("A",strtotime($time));
                                    $newDateTimeType= (($newDateTime=='AM')?__('home.Am') :__('home.PM'));
                                    @endphp
                                    {{ $date }} <br>
                                    {{ $time }}
                                    {{ $newDateTimeType }} <br>
                                    {{__('home.by')}}
                                    {{ $info->Add_user->name}} 
                                </td>
                                <td>
                                    @if($info->updated_by>0 and $info->updated_by!=null )
                                    @php
                                    $dt=new DateTime($info->updated_at);
                                    $date=$dt->format("Y-m-d");
                                    $time=$dt->format("h:i");
                                    $newDateTime=date("A",strtotime($time));
                                    $newDateTimeType= (($newDateTime=='AM')?__('home.Am') :__('home.PM'));
                                    @endphp
                                    {{ $date }} <br>
                                    {{ $time }}
                                    {{ $newDateTimeType }} <br>
                                    {{__('home.by')}}
                                    {{ $info->update_user->name}} 
                                    @else
                                    {{__('home.noUpdate')}}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('inv_itemcard_categories.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                                </td>
                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{ $data->links() }}
                    @else
                    <div class="alert alert-danger">
                        عفوا لاتوجد بيانات لعرضها !!
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/treasuries.js') }}"></script>
@endsection