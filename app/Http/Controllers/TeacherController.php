<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *教师引导页
     */
    public function index()
    {
        return view('teacherHome');
    }

    /**
     * @return $this
     * 显示教师的课程
     */
    public function showCourse(){
        $courses = Course::where('TeacherId' ,'=', Auth::guard('teacher')->user()->id)->get();
        return view('teacher.Course')->with(['courses' => $courses]);
    }

    /**
     * @param Request $request
     * @return array
     * 添加课程
     */
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

    /**
     * @param Request $request
     * @return array
     * 开启关闭点名
     */
    public function changeCallOverStatus(Request $request){
        //开启点名状态，如果同一节课开启了两次点名，判断开启点名的时间是否在这节课的时间内。
        //在前端判断，如果当前开启点名时间(new date)和上一次点名时间($course->openCallOverTime)之差小于课堂时间。就弹出是否开始一次新的点名提示。选择否，就继续上一次点名,点名次数不加1，选择是就，点名次数加1.
        $courseId = $request->route('courseId');
        $addNewCallOver = $request->route('addNewCallOver');
        $course = Course::find($courseId);
        if($course->isOpenCall==0){//每一次开启的时候都更新开启点名的时间
            $course->openCallOverTime = date('Y-m-d H:i:s',time()+8*3600);//设置开启点名的时间
        }else{//关闭点名
            //关闭点名，每一次关闭点名，都查找出这个次点名没有记录的学生，并且把旷课记录添加到考勤记录里面。
            //1、先找出该课程的所有学生，2、再找出该课程当次考勤记录没有考勤的学生。3、插入没有考勤学生的数据
            $students = DB::select('select *  from students where id 
                                in( select Sid from s_courses where Cid = ?)
                                and id NOT IN (select Sid from attend_records where Cid = ? and callOver = ?)',[$course->id,$course->id,$course->callOver]);
            Log::info("关闭点名");
            foreach ($students as $student){
                DB::table('attend_records')->insert(
                    array('status' => 2, 'Sno' => $student->stuNo, 'callOver'=>$course->callOver,'attendDate'=>date('Y-m-d H:i:s',time()+8*3600),'Cid'=>$course->id,'Cname'=>$course->Cname,'Sid'=>$student->id,'Sname'=>$student->name)
                );
            }
        }
        $course->isOpenCall = $course->isOpenCall==0?1:0;//修改点名状态，0关闭，1开启

        if($addNewCallOver){//是否开始一次新的点名
//            $course->openCallOverTime = date('Y-m-d H:i:s',time()+8*3600);//设置开启点名的时间
            $course->callOver += 1;
            Log::info("开启一次新的点名！".$addNewCallOver);
        }

        if($course->save()){
            return ['status' => '200','isOpenCall' =>  $course->isOpenCall];
        }
        else{
            return ['errMsg' => '点名状态修改失败！','status' => '110'];
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 获取单个课程信息
     */
    public function getCourse(Request $request){
        $courseId = $request->route('courseId');
        return Course::find($courseId);
    }

    /**
     * @param Request $request
     * @return string
     * 更新课程的上课地点
     */
    public function updateCoursePosition(Request $request){
        $courseId = $request->route('courseId');
        $course = Course::find($courseId);
        $course->Longitude = $request->get('Longitude');
        $course->Latitude = $request->get('Latitude');
        if($course->save()){
            Log::info($course->Longitude);
            return "修改成功";
        }
        else{
            return "修改失败";
        }
    }
}
