@extends('layouts.teacher')
@section('content')
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li><a href="{{url('/teacher')}}">教师引导页 <span class="sr-only">(current)</span></a></li>
                <li class="active"><a href="{{url('teacher/course')}}">課程表</a></li>
                <li><a href="#">考勤统计</a></li>
                <li><a href="#"></a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">群发信息</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            {{--<button type="button" class="btn btn-lg btn-danger">添加课程</button>--}}

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCourse" data-whatever="@mdo">添加课程</button>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>课程名称</th>
                    <th>课程编号</th>
                    <th>上课地点</th>
                    <th>上课时间</th>
                    <th>上课坐标</th>
                    <th>点名次数</th>
                    <th>开启点名</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{$course->id}}</td>
                    <td>{{$course->Cname}}</td>
                    <td>{{$course->Cno}}</td>
                    <td>{{$course->Address}}</td>
                    <td>{{$course->StartTime}} - {{$course->EndTime}}</td>
                    <td>{{$course->Longitude}},{{$course->Latitude}}</td>
                    <td>{{$course->callOver}}</td>
                    <td class="isOpenCall">@if($course->isOpenCall == 1)<p style="color:#c9302c;">正在点名中...</p>@else 未开启 @endif</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="callOver(this)" id="{{$course->id}}">
                            @if($course->isOpenCall == 1)关闭點名@else 开启点名 @endif
                        </button>
                        <button type="button" class="btn btn-success">導出課表</button>
                        <button type="button" class="btn btn-info">重新定位</button>
                        <button type="button" class="btn btn-danger">删除</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#joinCourse" data-whatever="@mdo" onclick="getJoinCourseUrl({{$course->id}})">扫码组班</button>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="modal fade" id="addCourse" tabindex="-1" role="dialog" aria-labelledby="addCourse">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">新建课程</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="Cno" class="control-label">课程编号</label>
                                    <input type="text" class="form-control" id="Cno">
                                </div>
                                <div class="form-group">
                                    <label for="Cname" class="control-label">课程名称</label>
                                    <input class="form-control" id="Cname">
                                </div>
                                <div class="form-group">
                                    <label for="StartTime" class="control-label">上课时间</label>
                                    <input class="form-control" id="StartTime">
                                </div>
                                <div class="form-group">
                                    <label for="EndTime" class="control-label">下课时间</label>
                                    <input class="form-control" id="EndTime">
                                </div>
                                <div class="form-group">
                                    <label for="Address" class="control-label">上课地点</label>
                                    <input class="form-control" id="Address">
                                </div>
                                <div class="form-group">
                                    <label for="Address" class="control-label">點擊獲取坐標</label>
                                    <input type="btn" class="form-control btn btn-success" value="获取坐标" id="Address" onclick="getCoordinate()">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="addCourse()">添加</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal fade" id="joinCourse" tabindex="-1" role="dialog" aria-labelledby="joinCourse">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">新建课程</h4>
                        </div>
                        <div class="modal-body" style="text-align: center">
                            <div id="code">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var Longitude = 0;//经度
        var Latitude  = 0;//纬度

        //添加课程弹窗
        $('#addCourse').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('添加课程 ' + recipient)
            modal.find('.modal-body input').val()
        });

        //扫码组班二维码弹窗
        $('#joinCourse').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('扫码加入课程 ' + recipient)
            modal.find('.modal-body input').val()
        });



        $(function(){

        });

        /**
        *添加课程
        **/
        function addCourse(){
            $.post("{{url('teacher/addCourse')}}",
                {
                    Cno:        $("#Cno").val(),
                    Cname:      $("#Cname").val(),
                    StartTime:  $("#StartTime").val(),
                    EndTime:    $("#EndTime").val(),
                    Address:    $("#Address").val(),
                    Longitude:  Longitude,
                    Latitude:   Latitude,
                },
                function(data){
                    console.log(data);
                        if(data.status == 200){
                            console.log("添加课程成功！");
                        }
                        else{
                            console.log(data.errMsg);
                        }
            })
        }

        /**
         * 获取上课地点坐标
         */
        function getCoordinate(){
            //获取课室坐标
             Longitude = 1.00;
             Latitude = 2.00
        }

        /**
         * 开启点名
         */
        function callOver(t){
            var courseId = $(t).attr('id');
            var course = null;//课程信息
            var addNewCallOver = 0;
            $.get("getCourse/"+courseId,
                function(data){
                    course = data;

                    var now = Date.parse(new Date());
                    var lastCallOverTime = Date.parse(course.openCallOverTime);
                    console.log(new Date(),course.openCallOverTime);
                    if(now - lastCallOverTime > 270000 && !course.isOpenCall){//如果点名时间距离上一次点名时间大于45分钟就开启新的一次点名
                        //弹出弹框,先不谈，直接是新增一次点名
                        addNewCallOver = 1;
                        console.log("开启新的一次点名！");
                    }
                    $.get("changeCallOverStatus/"+courseId+"/"+addNewCallOver,
                        function(data){
                            if(data.status == 200){
                                var isOpenCall = data.isOpenCall?'<p style="color:#c9302c">正在点名中...</p>':'未开启';
                                var changOpenCallBtnText = data.isOpenCall?'关闭点名':'开启点名'
                                $(t).parent().parent().find('.isOpenCall').html(isOpenCall);
                                $(t).html(changOpenCallBtnText);
                            }
                            else{
                                console.log(data.errMsg);
                            }
                        })

                });
        }
        /**
         * 获取加入课程链接（并声称二维码）
         */
        function getJoinCourseUrl(courseId){
            $("#code").html("");
            $("#code").qrcode({
                render: "table", //table方式 
                width: 400, //宽度 
                height:400, //高度 
                text: "http://zy595312011.vicp.io/huan/public/student/joinCourse/"+courseId //任意内容 
            });
        }
    </script>
    <script src="{{asset("js/jquery.qrcode.min.js")}}"></script>
@endsection
