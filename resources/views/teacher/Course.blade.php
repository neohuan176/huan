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
                    <th>上课地点</th>
                    <th>上课时间</th>
                    <th>上课坐标</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>创业就业指导</td>
                    <td>MB102</td>
                    <td>15:55-17:30</td>
                    <td>0.44,5.33</td>
                    <td>
                        <button type="button" class="btn btn-primary">開啓點名</button>
                        <button type="button" class="btn btn-success">導出課表</button>
                        <button type="button" class="btn btn-info">重新定位</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </td>
                </tr>
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
                                    <input class="form-control" id="Cname"></input>
                                </div>
                                <div class="form-group">
                                    <label for="StartTime" class="control-label">上课时间</label>
                                    <input class="form-control" id="StartTime"></input>
                                </div>
                                <div class="form-group">
                                    <label for="EndTime" class="control-label">下课时间</label>
                                    <input class="form-control" id="EndTime"></input>
                                </div>
                                <div class="form-group">
                                    <label for="Address" class="control-label">上课地点</label>
                                    <input class="form-control" id="Address"></input>
                                </div>
                                <div class="form-group">
                                    <label for="Address" class="control-label">點擊獲取坐標</label>
                                    <input type="btn" class="form-control btn btn-success" value="获取坐标" id="Address">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary">添加</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script type="text/javascript">
        $('#addCourse').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('New message to ' + recipient)
            modal.find('.modal-body input').val(recipient)
        })
    </script>
@endsection
