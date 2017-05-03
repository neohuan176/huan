@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">课程管理</div>

                    <div class="panel-body">
                        <form action="{{url('admin/course/showCourseByType')}}" method="get">
                        <div class="input-group">

                            <div class="input-group-btn">
                                <button type="button" id="type-selected" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if($type == 1)显示全部@endif
                                    @if($type == 2)课程名称@endif
                                    @if($type == 3)课程编号@endif
                                    @if($type == 4)教师姓名@endif
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)" onclick="changeType(1,this)">显示全部</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(2,this)">课程名称</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(3,this)">课程编号</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(4,this)">教师姓名</a></li>
                                </ul>
                            </div><!-- /btn-group -->
                            <input type="hidden" value="{{$type}}" name="type" id="type">
                            {{--</div><!-- /btn-group -->--}}
                            <input type="text" name="searchInput" value="{{$searchInput}}" id="searchInput" class="form-control" placeholder="查找..." aria-label="...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">查找</button>
                            </span>
                        </div><!-- /input-group -->
                        </form>

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>编号</th>
                                <th>任课教师</th>
                                <th>上课地点</th>
                                <th>上课时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->Cname}}</td>
                                    <td>{{$course->Cno}}</td>
                                    <td>{{$course->TeacherName}}</td>
                                    <td>{{$course->Address}}</td>
                                    <td>{{$course->weekday}}</td>
                                    <td>
                                        <button class="btn-sm btn-danger" onclick="del(this,'{{$course->id}}')">删除</button>
                                        <button class="btn-sm btn-primary" onclick="alter(this,{{$course}})"  data-toggle="modal" data-target="#alterCourse" data-whatever="@mdo">修改</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div>
                            <div class="pull-right">
                                {{$courses->render()}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alterCourse" tabindex="-1" role="dialog" aria-labelledby="alterCourse">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">修改课程信息</h4>
                </div>
                <div class="modal-body">
                    {{--<form>--}}
                        <div class="form-group">
                            <label for="Cname" class="control-label">名称</label>
                            <input type="text" class="form-control" id="Cname">
                        </div>
                        <div class="form-group">
                            <label for="Cno" class="control-label">编号</label>
                            <input class="form-control" id="Cno">
                        </div>
                        <div class="form-group">
                            <label for="TeacherName" class="control-label">任课老师</label>
                            <input class="form-control" id="TeacherName">
                        </div>
                        <div class="form-group">
                            <label for="Address" class="control-label">地点</label>
                            <input class="form-control" id="Address" value="">
                        </div>
                    <div class="form-group">
                            <label for="weekday" class="control-label">时间</label>
                            <input class="form-control" id="weekday" value="">
                        </div>

                    {{--</form>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="alterCourse()">修改</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        var curCourseId = null;
        //添加课程弹窗
        $('#alterCourse').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('修改课程信息 ')
            modal.find('.modal-body input').val()
        });


        $(function() {
            $('[data-toggle="popover"]').popover();//确认弹窗
        });

        /**
         * 修改查找类型
         * @param type
         * @param t
         */
        function changeType(type,t){
            $('#type').val(type);
            $("#type-selected").html($(t).html()+"<span class='caret'></span>");
        }

        function del(t,courseId){
            Ewin.confirm({ message: "确认删除，后果很严重？",btnok:'硬要删除',btncl:"取消" }).on(function (e) {//弹窗确认
                if (!e) {
                    return;
                }
                $.get("{{url('admin/course/delCourseById')}}/"+courseId,
                    function (data) {
                    $(t).parent().parent().remove();
                        console.log(data);
                    });
            });
        }

        /**
         * 修改弹窗信息初始化
         *
         * */
        function alter(t,course) {
            $("#Cname").val(course.Cname);
            $("#Cno").val(course.Cno);
            $("#TeacherName").val(course.TeacherName);
            $("#Address").val(course.Address);
            $("#weekday").val(course.weekday);
            curCourseId = course.id;
        }

        /**
         *
         */
        function alterCourse() {
            $.post('{{url("admin/course/alterCourse")}}/'+curCourseId,
                {
                    Cname:$("#Cname").val(),
                    Cno:$("#Cno").val(),
                    TeacherName:$("#TeacherName").val(),
                    Address:$("#Address").val(),
                    weekday:$("#weekday").val(),
                },
            function (data) {
                console.log(data);
            }
            )
        }
    </script>

    <script src="{{asset("js/dialog.js")}}"></script>
@endsection
