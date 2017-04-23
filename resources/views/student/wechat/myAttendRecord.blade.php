@extends('layouts.student')
@section('content')


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>课程</th>
                    <th>应到</th>
                    <th>已到</th>
                    <th>迟到</th>
                    <th>旷课</th>
                    <th>请假</th>
                    <th>加分</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $record)
                <tr>
                    <td>{{$record[0]}}</td>
                    <td>{{$record[1]}}</td>
                    <td>{{$record[2]}}</td>
                    <td>{{$record[3]}}</td>
                    <td>{{$record[4]}}</td>
                    <td>{{$record[5]}}</td>
                    <td>{{$record[6]}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
