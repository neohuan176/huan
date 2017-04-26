@extends('layouts.student')
@section('content')


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <form method="post" action="{{url('/student/showMyInfo')}}">
                <div class="form-group">
                    <label for="">姓名(不能修改)</label>
                    <input type="text" readonly="readonly" class="form-control" name="name" placeholder="姓名" value="{{$student->name}}">
                </div>
                <div class="form-group">
                    <label for="">学号(不能修改)</label>
                    <input type="text" readonly="readonly" class="form-control" name="stuNo" placeholder="学号" value="{{$student->stuNo}}">
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="exampleInputEmail1">邮箱</label>
                    <input type="text" class="form-control" name="email" placeholder="邮箱地址" value="{{$student->email}}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">学校名称</label>
                    <input type="text" class="form-control"  name="school" placeholder="学校名称" value="{{$student->school}}">
                </div>
                <div class="form-group">
                    <label for="">学院</label>
                    <input type="text" class="form-control" name="institute" placeholder="学院" value="{{$student->institute}}">
                </div>
                <div class="form-group{{ $errors->has('major') ? ' has-error' : '' }}">
                    <label for="">专业</label>
                    <input type="text" class="form-control" name="major" placeholder="专业" value="{{$student->major}}">
                    @if ($errors->has('major'))
                        <span class="help-block">
                            <strong>{{ $errors->first('major') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">班级</label>
                    <input type="text" class="form-control" name="class" placeholder="班级" value="{{$student->class}}">
                </div>
                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="">电话号码</label>
                    <input type="text" class="form-control" name="phone" placeholder="电话号码" value="{{$student->phone}}">
                    @if ($errors->has('phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-default">保存</button>
            </form>
        </div>
    </div>

    <script type="text/javascript">


        $(function(){


        });

    </script>
@endsection
