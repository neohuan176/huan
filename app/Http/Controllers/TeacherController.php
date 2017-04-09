<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
//        $this->middleware('auth:teacher');
    }

    public function index()
    {
//        $teacher = Auth::guard('teacher')->user();
        return view('teacherHome');
    }

    //添加课程
    public function addCourse(){
        //获取传过来的数据
        //将数据添加到数据库
        //返回添加信息。
    }
}
