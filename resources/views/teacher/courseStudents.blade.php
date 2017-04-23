@extends('layouts.teacher')
@section('content')


    <div class="row">
        {{--<div class="col-sm-2 col-md-2 sidebar" style="top:130px">--}}
            {{--<ul class="nav nav-sidebar">--}}
                {{--<li><a href="{{url('/teacher')}}">教师引导页 <span class="sr-only">(current)</span></a></li>--}}
                {{--<li><a href="{{url('teacher/course')}}">課程表</a></li>--}}
                {{--<li><a href="{{url('/teacher/myCourse')}}">我的课程</a></li>--}}
                {{--<li><a href="#"></a></li>--}}
            {{--</ul>--}}
            {{--<ul class="nav nav-sidebar">--}}
                {{--<li><a href="">群发信息</a></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#id</th>
                    <th>姓名</th>
                    <th>学号</th>
                    <th>专业</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{$student->id}}</td>
                    <td>{{$student->name}}</td>
                    <td>{{$student->stuNo}}</td>
                    <td>{{$student->major}}</td>
                    <td>
                        <button type="button" class="btn btn-danger">发消息</button>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">


        $(function(){


        });

    </script>
@endsection
