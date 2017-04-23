@extends('layouts.teacher')
@section('content')


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <form method="post" action="{{url('/teacher/toUpdateCourse/'.$courseInfo->id)}}">
                <div class="form-group">
                    <label for="exampleInputEmail1">上课时间</label>
                    <input type="text" class="form-control" id="startTime" name="StartTime" placeholder="格式：14:30:00" value="{{$courseInfo->StartTime}}">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">上课时间</label>
                    <input type="text" class="form-control" id="endTime" name="EndTime" placeholder="格式：14:30:00" value="{{$courseInfo->EndTime}}">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">星期几</label>
                    <input type="text" class="form-control" id="weekday" name="weekday" placeholder="格式：1,2,5" value="{{$courseInfo->weekday}}">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">上课地点</label>
                    <input type="text" class="form-control" id="address" name="Address" placeholder="上课地点" value="{{$courseInfo->Address}}">
                </div>
                <button type="submit" class="btn btn-default">保存</button>
            </form>
        </div>
    </div>

    <script type="text/javascript">


        $(function(){


        });

    </script>
@endsection
