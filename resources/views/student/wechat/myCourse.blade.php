@extends('layouts.student')
@section('content')


    <div class="row">
        <div class="course">
            @foreach($courses as $course)
                <div class="course-list-item wechat-full-width animated flipInX">
                    <div class="left">

                        <h2 class="course-title">{{$course->Cname}}</h2>

                        <p style="font-size: 14px;color:#666">{{$course->weekday}}<span class="float-right">{{$course->Address}}</span></p>

                        <p style="color:#999">
                            <span class="isOpenCall @if($course->isOpenCall == 1) red @endif"> @if($course->isOpenCall == 1)正在点名中...@else未开启点名@endif</span>
                            <span class="float-right callOver">共{{$course->callOver}}次点名</span>
                        </p>

                    </div>
                    <div class="right">
                        <a href="{{url('student/showCourseTeachFile/'.$course->id)}}" class="btn btn-danger">课件</a>
                    </div>
                </div>
            @endforeach
        </div>
        </div>




        {{--<div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">--}}
            {{--<table class="table table-striped">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th>课程</th>--}}
                    {{--<th>编号</th>--}}
                    {{--<th>地点</th>--}}
                    {{--<th>时间</th>--}}
                    {{--<th>状态</th>--}}
                    {{--<th>操作</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                {{--@foreach($courses as $course)--}}
                    {{--<tr>--}}
                        {{--<td>{{$course->Cname}}</td>--}}
                        {{--<td>{{$course->Cno}}</td>--}}
                        {{--<td>{{$course->Address}}</td>--}}
                        {{--<td>({{$course->weekday}})</td>--}}
                        {{--<td>(周{{$course->weekday}}) {{$course->StartTime}} - {{$course->EndTime}}</td>--}}
                        {{--<td class="isOpenCall">@if($course->isOpenCall == 1)<p style="color:#c9302c;">正在点名中...</p>@else 未开启点名 @endif</td>--}}
                        {{--<td><a href="{{url('student/showCourseTeachFile/'.$course->id)}}" class="btn btn-danger">课件</a></td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection
