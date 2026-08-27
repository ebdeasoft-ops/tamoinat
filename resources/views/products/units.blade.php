@extends('layouts.master')
@section('title')
{{ __('home.unit') }}
@stop


@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('home.unit') }}</h3>
        <input type="hidden" id="token_search" value="{{csrf_token() }}">
        <a href="{{ route('unit.create') }}" class="btn btn-sm btn-success"> {{__('home.Add')}}</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('unit.ajax_search') }}">
            <div class="col-md-4">
                <label> {{__('home.searchbyname')}}</label>
                <input type="text" id="search_by_text" placeholder="{{__('home.searchbyname')}}" class="form-control"> <br>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> {{__('home.searchbyType')}}</label>
                    <select name="is_master_search" id="is_master_search" class="form-control">
                        <option value="all"> {{__('home.searchbyall')}}</option>
                        <option value="1"> {{__('home.parentunit')}}</option>
                        <option value="0"> {{__('home.partialunit')}}</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div id="ajax_responce_serarchDiv">
                    @if (@isset($data) && !@empty($data))
                    @php
                    $i=1;
                    @endphp
                    <table id="example2" class="table our-table border mb-0 table-responsive text-center" >
                    <col style="width:2%">
                                        <col style="width:18% ">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <col style="width:30%">
                        <thead class="custom_thead">
                            <th>#</th>
                            <th>{{__('home.unit')}} </th>
                            <th> {{__('home.unittype')}}</th>
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
                                <td>@if($info->is_master==1) {{__('home.parentunit')}} @else {{__('home.partialunit')}} @endif</td>
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
                                    {{ $info->user->name}}
                                </td>
                                <td>
                                    @if($info->added_by>0 and $info->added_by!=null )
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
                                    {{ $info->user->name }}
                                    @else
                                    {{__('home.noUpdate')}}
                                    @endif
                                </td>
                                <td>
                                <a href="{{ route('unit.go.update',$info->id) }}" class="btn btn-sm  btn-primary">{{__('home.update')}}</a>
                                <a href="{{ route('unit.delete',$info->id) }}" class="btn btn-sm  btn-danger">{{__('home.delete')}}</a>
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
<script src="{{ URL::asset('assets/js/inv_uoms.js') }}"></script>

@endsection