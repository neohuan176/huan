<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Auth;
use Log;
use App\Course;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
//        $this->middleware('auth:teacher');
    }

    public function index()
    {
        return view('teacherHome');
    }

    //显示教师的课程
    public function showCourse(){
        $courses = Course::where('TeacherId' ,'=', Auth::guard('teacher')->user()->id)->get();
        return view('teacher.Course')->with(['courses' => $courses]);
    }

    //添加课程
    public function addCourse(Request $request){
        $data = Input::all();
        $course = new Course();
        $course->Cno = $data['Cno'];
        $course->Cname = $data['Cname'];
        $course->StartTime = $data['StartTime'];
        $course->EndTime = $data['EndTime'];
        $course->Address = $data['Address'];
        $course->Longitude = $data['Longitude'];
        $course->Latitude = $data['Latitude'];
        $course->TeacherId =  Auth::guard('teacher')->user()->id;
        $course->TeacherName =  Auth::guard('teacher')->user()->name;
        if($course->save()){
            return ['status' => '200'];
        }
        else{
            return ['errMsg' => '添加课程失败','status' => '110'];
        }
    }

    #修改开启点名状态
    public function changeCallOverStatus(Request $request){
        $courseId = $request->route('courseId');
        $course = Course::find($courseId);
        $course->isOpenCall = $course->isOpenCall==0?1:0;
        if($course->save()){
            return ['status' => '200','isOpenCall' =>  $course->isOpenCall];
        }
        else{
            return ['errMsg' => '点名状态修改失败！','status' => '110'];
        }
    }
}
