@extends('layouts.teacher')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">教师后台</div>

                    <div class="panel-body">
                        <pre>
                        Welcome Teacher!
                        已经登录教师后台！~
                        {{ Auth::guard('teacher')->user()->name }}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
