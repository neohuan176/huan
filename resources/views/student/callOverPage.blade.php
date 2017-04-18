@extends('layouts.student')
@section('content')

    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo $js->config(array('getLocation'), false)?>);

        $(function(){
            wx.ready(function() {
                wx.getLocation({
                    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                    success: function (res) {
                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                        var speed = res.speed; // 速度，以米/每秒计
                        var accuracy = res.accuracy; // 位置精度
                        $.post("{{url('student/updateStudentPosition')}}",
                            {
                                Longitude:  longitude,
                                Latitude:   latitude,
                            },
                            function(data){
                                if(data.status == 200){
                                    console.log(data);
                                    $("#locationMsg").html(data);
                                    $.get("{{url('student/callOverInPage')}}",
                                    function (data) {
                                        console.log(data);
                                        $("#msg").html(data);
                                    }
                                    )
                                }
                                else{
                                    console.log(data.errMsg);
                                }
                            })
                    }
                })
            })
        })



    </script>

    <h1 id="locationMsg"></h1>
    <h1 id="msg"></h1>
@endsection