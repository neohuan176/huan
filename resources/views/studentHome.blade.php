@extends('layouts.student')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">学生个人中心</div>

                    <div class="panel-body">
                        <pre>
                        Welcome!
                        已经登录学生个人中心！~
                        {{ Auth::guard('student')->user()->name }}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
