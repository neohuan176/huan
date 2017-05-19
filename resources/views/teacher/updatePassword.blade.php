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
            <form method="post" action="{{url('/teacher/updatePassword')}}">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label>新密码</label>
                        <input type="password" class="form-control" name="password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label>确认密码</label>
                        <input type="password" class="form-control" name="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                        @endif
                </div>


                <button type="submit" class="btn btn-default">保存</button>

                @if(!empty($success)) <h3 style="color:green">修改成功</h3>@endif
            </form>
        </div>
    </div>

    <script type="text/javascript">


    $(function(){


    });

    </script>
@endsection
