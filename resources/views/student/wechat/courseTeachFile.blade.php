@extends('layouts.student')
@section('content')


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1  col-md-10 col-md-offset-1 main">
            <h3>请在浏览器打开下载</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>文件名</th>
                    <th>大小</th>
                    <th>下载次数</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr>
                        <td><a href="{{url('student/downloadTeachFile/'.$file->id)}}" target="_blank">{{$file->fileName}}</a></td>
                        <td>{{round($file->size/1024/1024,2)}}kb</td>
                        <td>{{$file->downloadTimes}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
