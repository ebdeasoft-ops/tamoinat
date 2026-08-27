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
<br>
<div class="col-md-12" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>
@else
<div class="alert alert-danger">
    عفوا لاتوجد بيانات لعرضها !!
</div>
@endif