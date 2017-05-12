@extends('layouts.teacher')
@section('content')


    <div class="row">

        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <button type="button" class="btn btn-primary" onclick="callOver(this)" id="{{$course->id}}">
                @if($course->isOpenCall == 1)关闭點名@else 开启点名 @endif
            </button>
            <button type="button" class="btn btn-success" onclick="exportCourseExcel('{{$course->id}}')">导出所有考勤记录</button>
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#joinCourse" data-whatever="" onclick="getJoinCourseUrl({{$course->id}})">组班</button>
            <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#ask" data-whatever="" onclick="ask('{{$course->id}}')">随机提问</button>

            <div>
                <h3>基本信息</h3>
                <div>
                    <p id="attendInfo">{{'应到:'.$courseInfo['studentTotal'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已到:'.$courseInfo['attend'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;旷课:'.$courseInfo['unCall'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;迟到:'.$courseInfo['late'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请假:'.$courseInfo['leave'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;出勤率:'.($courseInfo['attend_rate']*100).'%'}}</p>
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
                </tr>
                </thead>
                <tbody>
                @foreach($records as $record)
                <tr>
                    <td>{{$record->Sname}}</td>
                    <td>{{$record->Sno}}</td>
                    <td>
                        <select name="status" id="{{$record->id}}" onchange="changeRecordStatus(this,'{{$record->id}}','{{$record->Cid}}')">
                            <option value="1"  @if($record->status==1)selected="selected"@endif>已到</option>
                            <option value="2"  @if($record->status==2)selected="selected"@endif>旷课</option>
                            <option value="3"  @if($record->status==3)selected="selected"@endif>迟到</option>
                            <option value="4"  @if($record->status==4)selected="selected"@endif>请假</option>
                        </select>
                        {{--{{$record->status}}--}}
                    </td>
                    <td>
                        <span id="score-{{$record->id}}">{{$record->score}}</span>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addScore" onclick="addScore(this,{{$record}})">加分</button>
                    </td>
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
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="comfirmAddScore()">确认</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="joinCourse" tabindex="-1" role="dialog" aria-labelledby="joinCourse">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="width:840px">
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

    <div class="modal fade" id="ask" tabindex="-1" role="dialog" aria-labelledby="addScore">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">加分</h4>
                </div>
                <div class="modal-body">
                    <h3 id="askName"></h3>
                        <div class="form-group">
                            <label for="Cno" class="control-label" >加分</label>
                            <input type="text" class="form-control" id="scoreForAsk"  onkeyup="this.value=this.value.replace(/[^0-9.-]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g,'')" >
                        </div>
                        <div class="form-group">
                            <label for="Cno" class="control-label" >状态</label>
                            <select name="statusForAsk" id="statusForAsk">
                                <option value="1"  >已到</option>
                                <option value="2"  >旷课</option>
                                <option value="3"  >迟到</option>
                                <option value="4"  >请假</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="comfirmAsk()">确认</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var cur_record = null;//当前操作的record
        var cur_score_el = null;//当前操作的加分分数的元素；
        var cur_ask_record = null;//当前提问的学生
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

        //随机提问
        //扫码组班二维码弹窗
        $('#ask').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('提问 ' + recipient)
            modal.find('.modal-body input').val()
//            console.log(modal);
        });

        $(function(){
        });

        /**
         * 开启点名
         */
        function callOver(t){
            var courseId = $(t).attr('id');
            var course = null;//课程信息
            changeCallover(t,courseId);
        }

        /**
         * 确认修改课程点名态，
         */
        function changeCallover(t,courseId){
            $.get("{{url('')}}"+"/teacher/changeCallOverStatus/"+courseId+"/0",
                function(data){
                    if(data.status == 200){
                        location.reload();//为了方便刷新点名次数，直接刷新
                    }
                    else{
                        alert(data.errMsg);
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
                width: 800, //宽度 
                height:800, //高度 
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
        function changeRecordStatus(t,recordId,courseId) {
            $.get("{{url('')}}/teacher/changeRecordStatus/"+recordId+"/"+$(t).val(),
                function(data){
                    $.post("{{url('/teacher/updateAttendInfo')}}",{courseId:courseId},
                        function (data) {
                            $("#attendInfo").html('应到:'+data.studentTotal+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已到:'+data.attend+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;旷课:'+data.unCall+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;迟到:'+data.late+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请假:'+data.leave+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;出勤率:'+data.attend_rate*100+'%');
                        }
                    )
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
        /**
         * 确认加分
         */
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

        /**
         *初始化提问学生的信息
         */
        function ask(courseId) {
            $.get('{{url("teacher/ask")}}?courseId='+courseId,
            function (value) {
                if(value){
                    cur_ask_record = value;
                    $("#askName").html(cur_ask_record.Sname);
                    $("#statusForAsk").val(cur_ask_record.status);
                }else{
                    console.log("当节课没有已经签到的学生！");
                }
            }
            )
        }

        /**
         * 提问完成
         */
        function comfirmAsk() {
            console.log($("#statusForAsk").val());
            if(cur_ask_record){
                $.post('{{url("teacher/ask")}}',
                    {
                        score:$("#scoreForAsk").val(),
                        status:$("#statusForAsk").val(),
                        recordId:cur_ask_record.id
                    },
                    function (data) {
                        console.log(data);
                    }
                )
            }
        }
    </script>
    <script src="{{asset("js/jquery.qrcode.min.js")}}"></script>
@endsection
