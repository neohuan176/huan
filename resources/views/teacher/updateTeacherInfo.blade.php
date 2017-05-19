<!--
/**
 * Created by PhpStorm.
 * User: a8042
 * Date: 2017/5/19
 * Time: 10:07
 */-->

@extends('layouts.teacher')
@section('content')

<div class="row">
        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <form method="post" action="{{url('/teacher/updateTeacherInfo')}}">
                <div class="form-group">
                    <label for="">姓名</label>
                    <input type="text" class="form-control" name="name" placeholder="姓名" value="{{$teacher->name}}">
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="exampleInputEmail1">邮箱(修改请联系管理员)</label>
                    <input type="text" class="form-control" readonly="readonly" name="email" placeholder="邮箱地址" value="{{$teacher->email}}">
                </div>

                <div class="form-group">
                    <label for="">学校名称</label>
                    <input type="text" class="form-control"  name="school" placeholder="学校名称" value="{{$teacher->school}}">
                </div>

                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="">电话号码</label>
                    <input type="text" class="form-control" name="phone" placeholder="电话号码" value="{{$teacher->phone}}">
                    @if ($errors->has('phone'))
                        <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
                     @endif
                </div>

                <button type="submit" class="btn btn-default">保存</button>

                @if(!empty($success)) <h3 style="color:green">保存成功</h3>@endif
            </form>
        </div>
    </div>

    <script type="text/javascript">


    $(function(){


    });

    </script>
@endsection
