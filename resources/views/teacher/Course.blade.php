@extends('layouts.teacher')
@section('title')课程管理@endsection
@section('css')
    <link href="{{ asset('css/course.css') }}" rel="stylesheet">
@endsection

@section('content')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCourse" data-whatever="@mdo">添加课程</button>
<div class="clear"></div>
<div class="course">
    @foreach($courses as $course)
    <div class="course-list-item animated flipInX">
        <div class="left">

            <h2 class="course-title"><a href="{{url('/teacher/showCurCourse/'.$course->id)}}">{{$course->Cname}}</a>
                <span class="btn-sm btn-success" style="cursor:pointer;"  onclick="location.href='{{url('/teacher/toUpdateCourse/'.$course->id)}}'">修改</span>
                <button type="button" class="btn btn-danger" onclick="callOver(this)" id="{{$course->id}}">
                    @if($course->isOpenCall == 1)关闭點名@else 开启点名 @endif
                </button>
            </h2>

            <p style="font-size: 20px;color:#666">{{$course->weekday}}<span class="float-right">{{$course->Address}}</span></p>

            <p style="color:#999">
                <span class="isOpenCall @if($course->isOpenCall == 1) red @endif"> @if($course->isOpenCall == 1)正在点名中...@else未开启点名@endif</span>
                <span class="float-right callOver">第{{$course->callOver}}次点名</span>
            </p>

        </div>
        <div class="right">
            <button type="button" class="btn-sm btn-primary" id="update-position" data-toggle="modal" data-target="#coursePositionPanel" data-whatever="@mdo" data-value="{{$course->id}}" onclick="openPositionPanel(this)">重新定位</button>
            <button class="btn-sm btn-primary margin-btn" onclick="exportCourseExcel('{{$course->id}}')">导出考勤</button>
            <button class="btn-sm btn-primary margin-btn" data-toggle="modal" data-target="#joinCourse" data-whatever="@mdo" onclick="getJoinCourseUrl({{$course->id}})">扫码组班</button>
            <button class="btn-sm btn-primary margin-btn" data-toggle="modal" data-target="#uploadTeachFile" data-whatever="@mdo"  onclick="$('#course-id').val({{$course->id}});">上传课件</button>
            <a class="btn-sm btn-primary margin-btn" href="{{url('/teacher/showCourseTeachFile/'.$course->id)}}" target="_blank">查看课件</a>
            <button class="btn-sm btn-primary margin-btn" onclick="changeEndCourse({{$course->id}})">结束课程</button>
        </div>
    </div>
    @endforeach
</div>

<div class="clear"></div>

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
                                    <label for="Address" class="control-label">上课地点</label>
                                    <input class="form-control" id="Address">
                                </div>
                                <div class="form-group">
                                    <label for="Address" class="control-label">上课时间(推荐格式：周三 1-2节，周五 8-9节)</label>
                                    <input class="form-control" id="weekday" value="周三 1-2节，周五 8-9节">
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="updatePosition" class="btn btn-primary" data-dismiss="modal">确认更新上课地点</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="uploadTeachFile" tabindex="-1" role="dialog" aria-labelledby="uploadTeachFile">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">上传课件</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{url('')}}/teacher/uploadTeachFile" method="post" enctype="multipart/form-data">
                                请选择您要上传的课件：
                                <input type="file" name="myFile"/><br/>
                                <input type="hidden" id="course-id" name="courseId" /><br/>
                                <button class="btn btn-primary" type="submit">确认上传</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>


    <script type="text/javascript">
        wx.config(<?php echo $js->config(array('getLocation'), false)?>);
        var Longitude = 0;//经度
        var Latitude  = 0;//纬度
        var cur_courseId;//当前操作的课程id
        var addNewCallOver = 0;//是否开启新的一次点名；1:是 0:否

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


        //上传课件弹窗
        $('#uploadTeachFile').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('上传课件 ' + recipient)
            modal.find('.modal-body input').val()
        });


        $(function(){
            $('[data-toggle="popover"]').popover();//确认弹窗

            //添加课程时获取课程坐标；
            var p = $("#id_address_input").AMapPositionPicker();
            $("#id_get_data").on('click', function () {
                var locationInfo =p.AMapPositionPicker('position');
                console.log(locationInfo);
                $("#id_data_display").html(JSON.stringify(p.AMapPositionPicker('position')));
                Longitude = locationInfo.longitude;
                Latitude = locationInfo.latitude;
            });

//            修改课程时的位置选择
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
//                    StartTime:  $("#StartTime").val(),
//                    EndTime:    $("#EndTime").val(),
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
             addNewCallOver = 0;
            $.get("{{url('teacher')}}/getCourse/"+courseId,
                function(data){
                    course = data;
                    var now = Date.parse(new Date());
                    var lastCallOverTimeChange = course.openCallOverTime.replace("-", "/").replace("-", "/");
                    var lastCallOverTime = Date.parse(lastCallOverTimeChange);
                    if(course.isOpenCall == 0){
                        console.log(now-lastCallOverTime);
                    }
                    if(now - lastCallOverTime < 30000 && course.isOpenCall == 0){//距离上次点名小于4分钟，就继续上次点名
                        console.log("距离上次点名小于5分钟，就继续上次点名");
                        changeCallover(t,courseId,addNewCallOver);
                    }
                    else if(now - lastCallOverTime > 60000 && course.isOpenCall == 0){//如果大于90分钟，就直接开始新的一次点名
                        console.log("如果大于10分钟，就直接开始新的一次点名");
                        //弹出弹框,先不谈，直接是新增一次点名,  当次点名时间距离上次点名时间大于90分钟直接开启新的一次点名，  如果大于45分钟并且小于90分钟就提醒要不要开启新的一次点名  如果小于45分钟 就继续上一次点名
                            addNewCallOver = 1;
                            changeCallover(t,courseId,addNewCallOver)
                    }
                    else if( (now - lastCallOverTime) > 30000 && (now - lastCallOverTime) < 60000 && course.isOpenCall == 0){
                        console.log("是否开启新的一次点名(大于5，小于10分钟)");
                        Ewin.confirm({ message: "是否开启新的一次点名？" }).on(function (e) {//弹窗确认
                            if (!e) {
                                addNewCallOver = 0;
                                changeCallover(t,courseId,addNewCallOver);//取消就是继续上一次点名
                                return;
                            }
                            addNewCallOver = 1;
                            changeCallover(t,courseId,addNewCallOver);
                        });
                    }
                    else if(course.isOpenCall == 1){
                        console.log("关闭点名");
                        changeCallover(t,courseId,addNewCallOver);
                    }
                });
        }

        /**
         * 确认修改课程点名态，
         */
        function changeCallover(t,courseId,addNewCallOver){
            $.get("{{url('teacher')}}/changeCallOverStatus/"+courseId+"/"+addNewCallOver,
                function(data){
                    if(data.status == 200){
                        var isOpenCall = data.isOpenCall?'正在点名中...':'未开启点名';
                        var changOpenCallBtnText = data.isOpenCall?'关闭点名':'开启点名';
                        if(data.isOpenCall){
                            $(t).parent().parent().find('.isOpenCall').addClass('red');
                        }else{
                            $(t).parent().parent().find('.isOpenCall').removeClass('red');
                        }
                        $(t).parent().parent().find('.isOpenCall').html(isOpenCall);
                        $(t).html(changOpenCallBtnText);
                        $(t).parent().parent().find('.callOver').html("第"+data.callOver+"次点名");

                    }
                    else{
                        console.log(data.errMsg);
                    }
                })
        }


        /**
         * 获取加入课程链接（并生成二维码）
         */
        function getJoinCourseUrl(courseId){
            $("#code").html("");
            $("#code").qrcode({
                render: "table", //table方式 
                width: 570, //宽度 
                height:570, //高度 
                text: "http://zy595312011.vicp.io/huan/public/student/joinCourse/"+courseId //任意内容 
            });
        }

        /**
         * 导出课程考勤记录
         * @param t
         * @param courseId
         */
        function exportCourseExcel(courseId){
            window.open("exportCourseExcel/"+courseId);
        }


        /**
         * 在微信端定位
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
            Ewin.confirm({ message: "是否删除课程？" ,btnok:"确认",btncl:"取消"}).on(function (e) {//弹窗确认
                if (!e) {
                    return;
                }
                addNewCallOver = 1;
                $.get("deleteCourse/"+courseId,function(data){
                    if(data.status == 200){
                        location.reload();
                    }else{
                        alert("删除失败！");
                    }
                })
            });
        }

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
    <script src="{{asset("js/jquery.qrcode.min.js")}}"></script>
    <script src="{{asset("js/dialog.js")}}"></script>
@endsection
