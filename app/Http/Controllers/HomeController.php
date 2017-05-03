<?php

namespace App\Http\Controllers;

use App\Acme\CommonServices;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Course;
use App\Teacher;
use Illuminate\Support\Facades\Input;
class HomeController extends Controller
{
    protected $commonServ;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonServices $commonServices)
    {
        $this->commonServ = $commonServices;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * @param Request $request
     * @return $this
     * 根据类型查找教师
     */
    public function showTeacherByType(Request $request){
        $paginate = 4;
        $type = $request->has('type')?$request->get('type'):1;
        $searchInput = "";
        if($request->has('searchInput')){
            $searchInput = $request->get('searchInput');
        }
        switch ($type){
            case 1:$teachers = Teacher::paginate($paginate);break;//查找全部
            case 2:$teachers = Teacher::where('name','like','%'.$searchInput.'%')->paginate($paginate);break;//按照姓名查找
            case 3:$teachers = Teacher::where('school','like','%'.$searchInput.'%')->paginate($paginate);break;//按照学校查找
            case 4:$teachers = Teacher::where('email','like','%'.$searchInput.'%')->paginate($paginate);break;//按照邮箱查找
            case 5:$teachers = Teacher::where('phone','like','%'.$searchInput.'%')->paginate($paginate);break;//按照电话查找
        }
        return view('admin.teacher.allTeachers')->with(['teachers'=>$teachers,'type'=>$type,'searchInput'=>$searchInput]);
    }

    /**
     * @param Request $request
     * @return string
     * 根据id删除教师
     */
    public function delTeacherById(Request $request){
        $teacherId = $request->route('teacherId');
        Teacher::destroy($teacherId);
        return "删除教师成功！";
    }

    /**
     * @param Request $request
     * @return string
     * 根据教师id修改教师信息
     */
    public function alterTeacherById(Request  $request){
        $teacherId = $request->route('teacherId');
        $teacher = Teacher::find($teacherId);
        $teacher->name = Input::get('name');
        $teacher->school = Input::get('school');
        $teacher->email = Input::get('email');
        $teacher->phone = Input::get('phone');
        if($teacher->save()){
            return "修改成功！";
        }else{
            return "修改失败";
        }
    }



    /**
     * @param Request $request
     * @return $this
     * 根据类型查找课程
     */
    public function showCourseByType(Request $request){
        $paginate = 4;
        $type = $request->has('type')?$request->get('type'):1;
        $searchInput = "";
        if($request->has('searchInput')){
            $searchInput = $request->get('searchInput');
        }
        switch ($type){
            case 1:$courses = Course::paginate($paginate);break;//查找全部
            case 2:$courses = Course::where('Cname','like','%'.$searchInput.'%')->paginate($paginate);break;//按照名称查找
            case 3:$courses = Course::where('Cno','like','%'.$searchInput.'%')->paginate($paginate);break;//按照编号查找
            case 4:$courses = Course::where('TeacherName','like','%'.$searchInput.'%')->paginate($paginate);break;//按照任课老师查找
        }
        return view('admin.course.allCourse')->with(['courses'=>$courses,'type'=>$type,'searchInput'=>$searchInput]);
    }

    /**
     * @param Request $request
     * @return string
     * 根据id删除课程
     */
    public function delCourseById(Request $request){
        $courseId = $request->route('courseId');
        Course::destroy($courseId);
        return "删除课程成功！";
    }

    /**
     * @param Request $request
     * @return string
     * 根据教师id修改课程信息
     */
    public function alterCourseById(Request  $request){
        $courseId = $request->route('courseId');
        $course = Course::find($courseId);
        $course->Cname = Input::get('Cname');
        $course->Cno = Input::get('Cno');
        $course->TeacherName = Input::get('TeacherName');
        $course->Address = Input::get('Address');
        $course->weekday = Input::get('weekday');
        if($course->save()){
            return "修改成功！";
        }else{
            return "修改失败";
        }
    }

}
