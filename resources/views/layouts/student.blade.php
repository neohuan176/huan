<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1，user-scalable=no">

    <title>Laravel</title>

    <!-- Fonts -->
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>--}}

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/course.css') }}" rel="stylesheet">
    <script src="{{asset("js/jquery.min.js")}}"></script>
    <script src="{{asset("js/bootstrap.js")}}"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js "></script>
    {{--高德地图插件--}}
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=ed1fafa0307bb4991da41f54d8a88b46"></script>
    {{--浏览器定位--}}
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>

<style>
    body {
        font-family: 'Lato';
    }

    .fa-btn {
        margin-right: 6px;
    }
</style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" style="background:#47ebff;color:#fff" href="{{ url('/student') }}">
                学生首页
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/student') }}">学生个人中心</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ url('student/myAttendRecord') }}">考勤记录</a></li>
                <li><a href="{{ url('student/showMyInfo') }}">个人信息</a></li>
                {{--<!-- Authentication Links -->--}}
                {{--@if (Auth::guest('teacher'))--}}
                {{--@if ( !Auth::guard('student')->user())--}}
                    {{--<li><a href="{{ url('student/login') }}">登录</a></li>--}}
                    {{--<li><a href="{{ url('student/register') }}">注册</a></li>--}}
                {{--@else--}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                            {{--{{ Auth::guard('student')->user()->name }} <span class="caret"></span>--}}
                        {{--</a>--}}

                        {{--<ul class="dropdown-menu" role="menu">--}}
                            {{--<li><a href="{{ url('student/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出登录</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--@endif--}}
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<!-- JavaScripts -->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--}}
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}





</body>
</html>
