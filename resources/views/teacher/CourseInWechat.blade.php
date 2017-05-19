@extends('layouts.teacher')
@section('title')我的课程（wechat）@endsection
@section('css')
    <link href="{{ asset('css/course.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
    <div class="course">
        @foreach($courses as $course)
            <div class="course-list-item wechat-full-width animated flipInX">
                <div class="left">

                    <h2 class="course-title"><a href="{{url('/teacher/showCurCourse/'.$course->id)}}">{{$course->Cname}}</a></h2>

                    <p style="font-size: 14px;color:#666">{{$course->weekday}}<span class="float-right">{{$course->Address}}</span></p>

                    <p style="color:#999">
                        <span class="isOpenCall @if($course->isOpenCall == 1) red @endif"> @if($course->isOpenCall == 1)正在点名中...@else未开启点名@endif</span>
                        <span class="float-right callOver">第{{$course->callOver}}次点名</span>
                    </p>

                </div>
                <div class="right">
                    <button type="button" class="btn btn-danger" onclick="callOver(this)" id="{{$course->id}}">
                        @if($course->isOpenCall == 1)关闭點名@else 开启点名 @endif
                    </button>
                    <button type="button" class="btn btn-primary" onclick="courseLocateInWechat({{$course->id}})">微信定位</button>
                </div>
            </div>
        @endforeach
    </div>
    </div>



    <script type="text/javascript">
        wx.config(<?php echo $js->config(array('getLocation'), false)?>);
        var addNewCallOver = 0;//是否开启新的一次点名；1:是 0:否

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
                        $(t).parent().parent().find('.isOpenCall').toggleClass('red');
                        $(t).parent().parent().find('.isOpenCall').html(isOpenCall);
                        $(t).html(changOpenCallBtnText);
                        $(t).parent().parent().find('.callOver').html("第"+data.callOver+"次点名");
                    }
                    else{
                        console.log(data.errMsg);
                    }
                })
        }


        function courseLocateInWechat(courseId){
            wx.ready(function() {
                wx.getLocation({
                    type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
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
    </script>
    <script src="{{asset("js/dialog.js")}}"></script>
@endsection
