@extends('layouts.student')
@section('content')


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>课程</th>
                    <th>编号</th>
                    <th>地点</th>
                    <th>时间</th>
                    <th>状态</th>
                    {{--<th>操作</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{$course->Cname}}</td>
                        <td>{{$course->Cno}}</td>
                        <td>{{$course->Address}}</td>
                        <td>(周{{$course->weekday}}) {{$course->StartTime}} - {{$course->EndTime}}</td>
                        <td class="isOpenCall">@if($course->isOpenCall == 1)<p style="color:#c9302c;">正在点名中...</p>@else 未开启点名 @endif</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
