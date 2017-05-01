<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>@yield('title')</title>

{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>--}}
{{--<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>--}}

    @yield('css')
<!-- Styles -->
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js "></script>

    <!-- JavaScripts -->
    <script src="{{asset("js/jquery.min.js")}}"></script>
    <script src="{{asset("js/bootstrap.js")}}"></script>
    {{--高德地图--}}
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=ed1fafa0307bb4991da41f54d8a88b46"></script>
    {{--浏览器定位--}}
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    {{--高德地图选择插件--}}
    <script src="{{asset("js/bootstrap.AMapPositionPicker.js")}}"></script>
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/teacher') }}">教师后台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            {{--<li><a href="{{ url('/teacher') }}">教师后台</a></li>--}}
            <li><a href="{{url('teacher/course')}}">課程表</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                {{--@if (Auth::guest('teacher'))--}}
                @if ( !Auth::guard('teacher')->user())
                    <li><a href="{{ url('teacher/login') }}">登录</a></li>
                    <li><a href="{{ url('teacher/register') }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::guard('teacher')->user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('teacher/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出登录</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="{{request()->getPathInfo() == '/teacher/course'?'active':''}}"><a href="{{url('teacher/course')}}">课程表 <span class="sr-only">(current)</span></a></li>
                <li class="{{request()->getPathInfo() == '/teacher/myCourse'?'active':''}}"><a href="{{url('/teacher/myCourse')}}">我的课程 <span class="sr-only">(current)</span></a></li>
            </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            @yield("content")
        </div>
    </div>
</div>


</body>
</html>
