@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <form action="{{url('admin/teacher/showTeacherByType')}}" method="get">
                        <div class="input-group">

                            <div class="input-group-btn">
                                <button type="button" id="type-selected" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if($type == 1)显示全部@endif
                                    @if($type == 2)姓名@endif
                                    @if($type == 3)学校@endif
                                    @if($type == 4)邮箱@endif
                                    @if($type == 5)电话@endif
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)" onclick="changeType(1,this)">显示全部</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(2,this)">姓名</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(3,this)">学校</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(4,this)">邮箱</a></li>
                                    <li><a href="javascript:void(0)" onclick="changeType(5,this)">电话号码</a></li>
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
                                <th>教师姓名</th>
                                <th>学校</th>
                                <th>电话</th>
                                <th>邮箱</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{$teacher->name}}</td>
                                    <td>{{$teacher->school}}</td>
                                    <td>{{$teacher->phone}}</td>
                                    <td>{{$teacher->email}}</td>
                                    <td>
                                        <button class="btn-sm btn-danger" onclick="del(this,'{{$teacher->id}}')">删除</button>
                                        <button class="btn-sm btn-primary" onclick="alter(this,{{$teacher}})"  data-toggle="modal" data-target="#alterTeacher" data-whatever="@mdo">修改</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div>
                            <div class="pull-right">
                                {{$teachers->render()}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alterTeacher" tabindex="-1" role="dialog" aria-labelledby="alterTeacher">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">修改教师信息</h4>
                </div>
                <div class="modal-body">
                    {{--<form>--}}
                        <div class="form-group">
                            <label for="name" class="control-label">姓名</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">email</label>
                            <input class="form-control" id="email">
                        </div>
                        <div class="form-group">
                            <label for="school" class="control-label">学校</label>
                            <input class="form-control" id="school">
                        </div>
                        <div class="form-group">
                            <label for="phone" class="control-label">手机号码</label>
                            <input class="form-control" id="phone" value="">
                        </div>

                    {{--</form>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="alterTeacher()">修改</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        var curTeacherId = null;
        //添加课程弹窗
        $('#alterTeacher').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('修改教师信息 ')
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

        function del(t,teacherId){
            Ewin.confirm({ message: "确认删除，后果很严重？",btnok:'硬要删除',btncl:"取消" }).on(function (e) {//弹窗确认
                if (!e) {
                    return;
                }
                $.get("{{url('admin/teacher/delTeacherById')}}/"+teacherId,
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
        function alter(t,teacher) {
            $("#name").val(teacher.name);
            $("#email").val(teacher.email);
            $("#school").val(teacher.school);
            $("#phone").val(teacher.phone);
            curTeacherId = teacher.id;
        }

        /**
         *
         */
        function alterTeacher() {
            $.post('{{url("admin/teacher/alterTeacher")}}/'+curTeacherId,
                {
                    name:$("#name").val(),
                    email:$("#email").val(),
                    school:$("#school").val(),
                    phone:$("#phone").val(),
                },
            function (data) {
                console.log(data);
            }
            )
        }
    </script>

    <script src="{{asset("js/dialog.js")}}"></script>
@endsection
