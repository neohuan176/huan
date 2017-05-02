@extends('layouts.teacher')
@section('title')我的课程@endsection

@section('content')
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
                        {{--<button type="button" class="btn btn-danger">群发信息</button>--}}
                        <a href="{{url('/teacher/exportCourseExcel/'.$course->id)}}" target="_blank" class="btn btn-success">导出考勤记录</a>
                        <button class="btn btn-danger" onclick="deleteCourse({{$course->id}})">删除</button>
                        @if($course->isEnd==1)<button type="button" class="btn btn-danger" onclick="changeEndCourse({{$course->id}})">开课</button>@endif
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
    <script type="text/javascript">
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

        /**
         * 删除课程
         * @param courseId
         */
        function deleteCourse(courseId){
            Ewin.confirm({ message: "是否删除课程？" ,btnok:"确认",btncl:"取消"}).on(function (e) {//弹窗确认
                if (!e) {
                    return;
                }
                addNewCallOver = 1;
                $.get("{{url('teacher')}}/deleteCourse/"+courseId,function(data){
                    if(data.status == 200){
                        location.reload();
                    }else{
                        alert("删除失败！");
                    }
                })
            });
        }
    </script>
            <script src="{{asset("js/dialog.js")}}"></script>
@endsection
