@extends('layouts.teacher')
@section('content')


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>编号</th>
                    <th>地点</th>
                    <th>时间</th>
                    <th>点名</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{$course->Cname}}</td>
                    <td>{{$course->Cno}}</td>
                    <td>{{$course->Address}}</td>
                    {{--<td>{{$course->StartTime}} - {{$course->EndTime}}</td>--}}
                    <td>{{$course->weekday}}</td>
                    <td class="isOpenCall">@if($course->isOpenCall == 1)<p style="color:#c9302c;">正在点名中...</p>@else 未开启 @endif</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="callOver(this)" id="{{$course->id}}">
                            @if($course->isOpenCall == 1)关闭點名@else 开启点名 @endif
                        </button>
                        <button type="button" class="btn btn-danger" onclick="courseLocateInWechat({{$course->id}})">微信定位</button>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        wx.config(<?php echo $js->config(array('getLocation'), false)?>);
        var Longitude = 0;//经度
        var Latitude  = 0;//纬度
        var cur_courseId;//当前操作的课程id


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
    </script>
@endsection
