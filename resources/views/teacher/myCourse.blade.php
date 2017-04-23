@extends('layouts.teacher')
@section('content')


    <div class="row">
        <div class="col-sm-2 col-md-2 sidebar" style="top:130px">
            <ul class="nav nav-sidebar">
                <li><a href="{{url('/teacher')}}">教师引导页 <span class="sr-only">(current)</span></a></li>
                <li ><a href="{{url('teacher/course')}}">課程表</a></li>
                <li class="active"><a href="{{url('/teacher/myCourse')}}">我的课程</a></li>
                <li><a href="#"></a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">群发信息</a></li>
            </ul>
        </div>
        <div class="col-sm-10 col-sm-offset-2  col-md-10 col-md-offset-2 main">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#id</th>
                    <th>课程名称</th>
                    <th>课程编号</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{$course->id}}</td>
                    <td>{{$course->Cname}}</td>
                    <td>{{$course->Cno}}</td>
                    <td>
                        <a href="{{url('/teacher/showCourseStudents/'.$course->id)}}" class="btn btn-primary">学生</a>
                        <button type="button" class="btn btn-danger">群发信息</button>
                        <a href="{{url('/teacher/exportCourseExcel/'.$course->id)}}" target="_blank" class="btn btn-success">导出考勤记录</a>
                        @if($course->isEnd==1)<button type="button" class="btn btn-danger" onclick="changeEndCourse({{$course->id}})">开课</button>@endif
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

        /**
         * 修改课程结课状态
         * @param courseId
         */
        function changeEndCourse(courseId){
            $.get("{{url('')}}"+"/teacher/changeEndCourse/"+courseId,function(data){
                console.log(data);
                location.reload();
            })
        }
    </script>
@endsection
