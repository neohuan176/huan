@extends('layouts.teacher')
@section('content')


    <div class="row">
        {{--<div class="col-sm-3 col-md-2 sidebar">--}}
            {{--<ul class="nav nav-sidebar">--}}
                {{--<li><a href="{{url('/teacher')}}">教师引导页 <span class="sr-only">(current)</span></a></li>--}}
                {{--<li class="active"><a href="{{url('teacher/course')}}">課程表</a></li>--}}
                {{--<li><a href="#">考勤统计</a></li>--}}
                {{--<li><a href="#"></a></li>--}}
            {{--</ul>--}}
            {{--<ul class="nav nav-sidebar">--}}
                {{--<li><a href="">群发信息</a></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCourse" data-whatever="@mdo">添加课程</button>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#id</th>
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
                        <button type="button" class="btn btn-success" onclick="exportCourseExcel(this,'{{$course->id}}')">導出課表</button>
                        <button type="button" class="btn btn-info" id="update-position" data-toggle="modal" data-target="#coursePositionPanel" data-whatever="@mdo" data-value="{{$course->id}}" onclick="openPositionPanel(this)">重新定位</button>
                        <button type="button" class="btn btn-danger" onclick="deleteCourse({{$course->id}})">删除</button>
                        <button type="button" class="btn btn-danger" onclick="courseLocateInWechat({{$course->id}})">微信定位</button>
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
                                    <label for="Address" class="control-label">星期几(格式:1,3,5)</label>
                                    <input class="form-control" id="weekday" value="1">
                                </div>

                                <div class="form-group">
                                <label for="id_address_input">选择位置</label>
                                <div class="input-group" id="id_address_input">
                                    <input type="text" class="form-control">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                </div>
                                <button id="id_get_data" type="button" class="btn btn-default">确认上课地点</button>
                                <p id="id_data_display"></p>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addCourse()">添加</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="joinCourse" tabindex="-1" role="dialog" aria-labelledby="joinCourse">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">扫码组班</h4>
                        </div>
                        <div class="modal-body">
                            <div id="code">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="coursePositionPanel" tabindex="-1" role="dialog" aria-labelledby="coursePositionPanel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">重新选择上课地点</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="choosePosition">选择位置</label>
                                <div class="input-group" id="choosePosition">
                                    <input type="text" class="form-control">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                </div>
                                {{--<button id="" type="button" class="btn btn-default" onclick="">确认更新地点</button>--}}
                                {{--<p id="id_data_display"></p>--}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="updatePosition" class="btn btn-primary" data-dismiss="modal">确认更新上课地点</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script type="text/javascript">
        wx.config(<?php echo $js->config(array('getLocation'), false)?>);
        var Longitude = 0;//经度
        var Latitude  = 0;//纬度
        var cur_courseId;//当前操作的课程id

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

        //重新选择上课地点
        $('#coursePositionPanel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('重新选择上课地点 ' + recipient)
            modal.find('.modal-body input').val()
        });



        $(function(){
            //添加课程时获取课程坐标；
            var p = $("#id_address_input").AMapPositionPicker();
            $("#id_get_data").on('click', function () {
                var locationInfo =p.AMapPositionPicker('position');
                console.log(locationInfo);
                $("#id_data_display").html(JSON.stringify(p.AMapPositionPicker('position')));
                Longitude = locationInfo.longitude;
                Latitude = locationInfo.latitude;
            });

            var position = $("#choosePosition").AMapPositionPicker();
            $("#updatePosition").on('click', function () {
                var locationInfo =position.AMapPositionPicker('position');
                Longitude = locationInfo.longitude;
                Latitude = locationInfo.latitude;
                console.log(locationInfo);
                $.post("updateCoursePosition/"+cur_courseId,
                    {
                        Longitude:  Longitude,
                        Latitude:   Latitude,
                    },
                    function(data){
                        alert(data);
                    })
            });


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
                    weekday:    $("#weekday").val(),
                    Longitude:  Longitude,
                    Latitude:   Latitude,
                },
                function(data){
                    console.log(data);
                        if(data.status == 200){
                            location.reload();
                        }
                        else{
                            console.log(data.errMsg);
                        }
            })
        }

        /**
         * 打开更新上课地点时，获取当前操作课程的id
         * */
        function openPositionPanel(t){
            cur_courseId = $(t).attr('data-value');
            console.log(cur_courseId);
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
         * 获取加入课程链接（并生成二维码）
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

        /**
         * 导出课程考勤记录
         * @param t
         * @param courseId
         */
        function exportCourseExcel(t,courseId){
            window.open("exportCourseExcel/"+courseId);
        }


        /**
         * 在微信端点名
         * @param courseId
         */
        function courseLocateInWechat(courseId){
            wx.ready(function() {
                wx.getLocation({
                    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                    success: function (res) {
                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                        var speed = res.speed; // 速度，以米/每秒计
                        var accuracy = res.accuracy; // 位置精度
                        $.post("updateCoursePosition/"+courseId,
                            {
                                Longitude:  longitude,
                                Latitude:   latitude,
                            },
                            function(data){
                                alert(data);
                            })
                    }
                })
            })
        }

        /**
         * 删除课程
         * @param courseId
         */
        function deleteCourse(courseId){
            $.get("deleteCourse/"+courseId,function(data){
                if(data.status == 200){
                    location.reload();
                }else{
                    alert("删除失败！");
                }
            })
        }
    </script>
    <script src="{{asset("js/jquery.qrcode.min.js")}}"></script>
@endsection
