@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">

            <!-- Swiper -->
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="background: #d94e52;color: #fff;padding: 15px;font-size:18px;line-height: 48px">
                        <h2>教师</h2>
                        <ul>
                            <li><p>随时创建课程</p></li>
                            <li><p>随时更改课室位置</p></li>
                            <li><p>自动统计考勤数据</p></li>
                            <li><p>基于地理位置的考勤</p></li>
                            <li><p>一键导出考勤数据</p></li>
                        </ul>
                    </div>
                    <div class="swiper-slide" style="background: #43a9d9;color: #fff;padding: 15px;font-size:18px;line-height: 48px">
                        <h2>学生</h2>
                        <ul>
                            <li><p>扫码加入课程</p></li>
                            <li><p>扫码签到</p></li>
                            <li><p>地理位置签到</p></li>
                            <li><p>查看课程</p></li>
                            <li><p>查看课程考勤记录</p></li>
                        </ul>
                    </div>
                    <div class="swiper-slide" style="background: #d98638;color: #fff;padding: 15px;font-size:18px;line-height: 48px">
                        <h2>师生互动</h2>
                        <ul>
                            <li><p>课堂提问</p></li>
                            <li><p>课件共享</p></li>
                        </ul>
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>

        </div>
    </div>
</div>

<script src="{{asset("js/swiper.min.js")}}"></script>

<script type="text/javascript">
    <!-- Initialize Swiper -->
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        autoplay:2500,
        coverflow: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows : true
        }
    });
</script>
@endsection
