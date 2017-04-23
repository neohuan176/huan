@extends('layouts.teacher')
@section('content')


    <div class="row" style="margin-top:20px">

        <div class="col-sm-2 col-md-2 sidebar" style="top:130px">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="{{url('/teacher')}}">教师引导页 <span class="sr-only">(current)</span></a></li>
                <li ><a href="{{url('teacher/course')}}">課程表</a></li>
                <li><a href="{{url('/teacher/myCourse')}}">我的课程</a></li>
                <li><a href="#"></a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">群发信息</a></li>
            </ul>
        </div>
        <div class="col-sm-10 col-sm-offset-2  col-md-10 col-md-offset-2 main">

        {{--<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">--}}

        欢迎登录教师后台！

        </div>
    </div>


@endsection
