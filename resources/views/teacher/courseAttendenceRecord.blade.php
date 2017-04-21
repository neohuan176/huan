@extends('layouts.teacher')
@section('content')


    <div class="row">
        <div class="col-sm-2 col-md-2 sidebar" style="top:130px">
            <ul class="nav nav-sidebar">
                <li><a href="{{url('/teacher')}}">教师引导页 <span class="sr-only">(current)</span></a></li>
                <li ><a href="{{url('teacher/course')}}">課程表</a></li>
                <li><a href="#">考勤统计</a></li>
                <li><a href="#"></a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">群发信息</a></li>
            </ul>
        </div>
        <div class="col-sm-10 col-sm-offset-2  col-md-10 col-md-offset-2 main">
        {{--<div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">--}}
            {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCourse" data-whatever="@mdo">添加课程</button>--}}
            <button type="button" class="btn btn-primary" onclick="callOver(this)" id="{{$course->id}}">
                @if($course->isOpenCall == 1)关闭點名@else 开启点名 @endif
            </button>
            <button type="button" class="btn btn-success" onclick="exportCourseExcel('{{$course->id}}')">导出所有考勤记录</button>
            <button type="button" class="btn btn-primary">随机点名</button>
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#joinCourse" data-whatever="" onclick="getJoinCourseUrl({{$course->id}})">组班</button>

            <div>
                <h3>基本信息</h3>
                <div>
                    <p>{{' 应到:'.$courseInfo['studentTotal'].' 已到:'.$courseInfo['attend'].'   旷课:'.$courseInfo['unCall'].'  迟到:'.$courseInfo['late'].'  请假:'.$courseInfo['leave'].'   出勤率:'.($courseInfo['attend_rate']*100).'%'}}</p>
                    <h4>第{{$course->callOver}}次考勤</h4>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>姓名</th>
                    <th>学号</th>
                    <th>考勤状态</th>
                    <th>加分</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $record)
                <tr>
                    <td>{{$record->Sname}}</td>
                    <td>{{$record->Sno}}</td>
                    <td>
                        <select name="status" id="{{$record->id}}" onchange="changeRecordStatus(this,'{{$record->id}}')">
                            <option value="1" onclick="changeRecordStatus(this,'{{$record->id}}')" @if($record->status==1)selected="selected"@endif>已到</option>
                            <option value="2" onclick="changeRecordStatus(this,'{{$record->id}}')" @if($record->status==2)selected="selected"@endif>旷课</option>
                            <option value="3" onclick="changeRecordStatus(this,'{{$record->id}}')" @if($record->status==3)selected="selected"@endif>迟到</option>
                            <option value="4" onclick="changeRecordStatus(this,'{{$record->id}}')" @if($record->status==4)selected="selected"@endif>请假</option>
                        </select>
                        {{--{{$record->status}}--}}
                    </td>
                    <td>
                        <span id="score-{{$record->id}}">{{$record->score}}</span>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addScore" onclick="addScore(this,{{$record}})">加分</button>
                    </td>
                    <td><button type="button" class="btn btn-primary">待定</button></td>
                </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="modal fade" id="addScore" tabindex="-1" role="dialog" aria-labelledby="addScore">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">加分</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="Cno" class="control-label" >分数</label>
                                    <input type="text" class="form-control" id="score"  onkeyup="this.value=this.value.replace(/[^0-9.-]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g,'')" >
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="comfirmAddScore()">添加</button>
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
        </div>
    </div>

    <script type="text/javascript">

        var cur_record = null;//当前操作的record
        var cur_score_el = null;//当前操作的加分分数的元素；

        //添加课程弹窗
        $('#addScore').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('加分 ')
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
         * 开启点名
         */
        function callOver(t){
            var courseId = $(t).attr('id');
            var course = null;//课程信息
            var addNewCallOver = 0;
            $.get("{{url('teacher/getCourse')}}"+"/"+courseId,
                function(data){
                    course = data;
                    var now = Date.parse(new Date());
                    var lastCallOverTime = Date.parse(course.openCallOverTime);
                    console.log(new Date(),course.openCallOverTime);
                    if(now - lastCallOverTime > 2700000 && !course.isOpenCall){//如果点名时间距离上一次点名时间大于45分钟就开启新的一次点名
                        //弹出弹框,先不谈，直接是新增一次点名
                        addNewCallOver = 1;
                        console.log("开启新的一次点名！");
                    }
                    $.get("{{url('')}}/teacher/changeCallOverStatus/"+courseId+"/"+addNewCallOver,
                        function(data){
                            if(data.status == 200){
//                                var isOpenCall = data.isOpenCall?'<p style="color:#c9302c">正在点名中...</p>':'未开启';
                                var changOpenCallBtnText = data.isOpenCall?'关闭点名':'开启点名'
//                                $(t).parent().parent().find('.isOpenCall').html(isOpenCall);
                                location.reload();//为了方便刷新点名次数，直接刷新
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
        function exportCourseExcel(courseId){
            window.open("{{url('')}}/teacher/exportCourseExcel/"+courseId);
        }

        /**
         * 修改学生签到状态
         * @param t
         * @param recordId
         */
        function changeRecordStatus(t,recordId) {
            $.get("{{url('')}}/teacher/changeRecordStatus/"+recordId+"/"+$(t).val(),
                function(data){
                    console.log(data);
                }
            )
        }

        /**
         * 获取当前操作的record，加分操作
         * @param t
         * @param record
         */
        function addScore(t,record){
            cur_record = record;
            cur_score_el = $(t).parent().find('span')[0].id;

        }
        function comfirmAddScore(){
            var score = $("#score").val();
            $.post('{{url('teacher/addScore')}}',
                {
                    score:score,
                    recordId:cur_record.id
                },
                function (data) {
                    console.log(cur_score_el);
                    $("#"+cur_score_el).html(data.score);
                    console.log(data);
                }
            )
        }
    </script>
    <script src="{{asset("js/jquery.qrcode.min.js")}}"></script>
@endsection
