@extends('layouts.student')
@section('content')

{{--<div id="map" style="width:0px;height:0px"></div>--}}
{{--<p id="tip"></p>--}}



    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo $js->config(array('getLocation'), false)?>);

        $(function(){
            wx.ready(function() {
                wx.getLocation({
                    type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
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

        {{--var map, geolocation;--}}
        {{--//加载地图，调用浏览器定位服务--}}
        {{--$(function () {--}}
        {{--map = new AMap.Map('map', {--}}
            {{--resizeEnable: true--}}
        {{--});--}}
        {{--map.plugin('AMap.Geolocation', function() {--}}
            {{--geolocation = new AMap.Geolocation({--}}
                {{--enableHighAccuracy: true,//是否使用高精度定位，默认:true--}}
                {{--timeout: 10000,          //超过10秒后停止定位，默认：无穷大--}}
                {{--buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)--}}
                {{--zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false--}}
                {{--buttonPosition:'RB'--}}
            {{--});--}}
            {{--map.addControl(geolocation);--}}
            {{--geolocation.getCurrentPosition();--}}
            {{--AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息--}}
            {{--AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息--}}
        {{--});--}}
        {{--//解析定位结果--}}
        {{--function onComplete(data) {--}}
            {{--var str=['定位成功'];--}}
            {{--str.push('经度：' + data.position.getLng());--}}
            {{--str.push('纬度：' + data.position.getLat());--}}
            {{--if(data.accuracy){--}}
                {{--str.push('精度：' + data.accuracy + ' 米');--}}
            {{--}//如为IP精确定位结果则没有精度信息--}}
            {{--str.push('是否经过偏移：' + (data.isConverted ? '是' : '否'));--}}
            {{--document.getElementById('tip').innerHTML = str.join('<br>');--}}

            {{--$.post("{{url('student/updateStudentPosition')}}",--}}
                {{--{--}}
                    {{--Longitude:  data.position.getLng(),--}}
                    {{--Latitude:   data.position.getLat(),--}}
                {{--},--}}
                {{--function(data){--}}
                    {{--if(data.status == 200){--}}
                        {{--console.log(data);--}}
                        {{--$("#locationMsg").html(data);--}}
                        {{--$.get("{{url('student/callOverInPage')}}",--}}
                            {{--function (data) {--}}
                                {{--console.log(data);--}}
                                {{--$("#msg").html(data);--}}
                            {{--}--}}
                        {{--)--}}
                    {{--}--}}
                    {{--else{--}}
                        {{--console.log(data.errMsg);--}}
                    {{--}--}}
                {{--});--}}
        {{--}--}}
        {{--//解析定位错误信息--}}
        {{--function onError(data) {--}}
            {{--document.getElementById('tip').innerHTML = '定位失败';--}}
        {{--}--}}

        {{--})--}}

    </script>

    <h1 id="locationMsg"></h1>
    <h1 id="msg"></h1>
@endsection