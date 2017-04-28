<?php

namespace App\Http\Controllers;

use App\Acme\StudentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Course;
use App\Student;
use App\TeachFile;

class StudentController extends Controller
{
    //微信接入配置，到时改成返回单例
    protected  $options = [
        'debug'     => true,
        'app_id'    => 'wx2a8f750c494dee0b',
        'secret'    => 'f8a372d1d0b791e3260c06c957655ceb',
        'token'     => 'neo',
        'aes_key' => 'EajwoAzVnZxYTFUAakjM1aOf4L3VRdaHe86nnLJytEg',
        'log' => [
            'level' => 'debug',
            'file'  => '/ProSoftware/xampp/htdocs/huan/tmp/easywechat.log',
        ],
    ];
    protected $studentService;
    protected $openid;
    public function __construct(StudentServices $studentService){
//        $this->middleware('student');
        $this->studentService = $studentService;
        $this->openid = Session::get('wechat_user')['id'];
        $this->middleware('studentExist')->except(['showCourseTeachFile','downloadTeachFile']);//判断学生是否已经注册
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 学生主页
     */
    public function index()
    {
        return view('studentHome');
    }

    /**
     * 学生加入课程
     */
    public function joinCourse(Request $request){
        $courseId = $request->route('courseId');
        $openid = Session::get('wechat_user')['id'];

        if($this->studentService->isAlreadyJoinCourse($openid,$courseId)){
            return "你已经加入该课程了！";
        }
        if(!$this->studentService->joinCourse($courseId,$openid)){
            return "加入课程失败";
        }
        return "加入成功";
    }

    /**
     * @param Request $request
     * @return $this
     * 打开签到网页
     */
    public function callOverPage(Request $request){
        $app = new Application($this->options);
        $js = $app->js;
        return view('student.wechat.callOverPage')->with(['js' => $js]);
    }

    /**
     * @param Request $request
     * @return String
     * 学生在微信网页考勤。
     */
    public function callOverInPage(Request $request){
        $openid = Session::get('wechat_user')['id'];
        return $this->studentService->callOver($openid);
    }

    /**
     * @param Request $request
     * @return array
     * 在网页更新地理位置信息
     */
    public function updateStudentPosition(Request $request){
        $longitude = $request->input('Longitude');
        $latitude = $request->input('Latitude');
        $openid = Session::get('wechat_user')['id'];
        $res = $this->studentService->updateStudentPosition($openid,$longitude,$latitude);
        if($res){
            return ['status' => '200','value' =>  "更新地理位置信息成功"];
        }
        else{
            return ['errMsg' => '点名状态修改失败！','status' => '110'];
        }
    }


    /**
     * @param Request $request
     * @return $this
     * 查看我的课程
     */
    public function showStudentCourse(Request $request){
//        $openid = Session::get('wechat_user')['id'];
        $courses = $this->studentService->getMyCourse($this->openid);
        return view('student.wechat.myCourse')->with(['courses'=>$courses]);
    }

    /**
     * @param Request $request
     * @return $this
     * 显示我的课程考勤统计
     */
    public function showMyAttendRecord(Request $request){
        $records = $this->studentService->getMyAttendRecord($this->openid);
        return view('student.wechat.myAttendRecord')->with(['records'=>$records]);
    }

    /**
     * @param Request $request
     * @return $this
     * 显示课件
     */
    public function showCourseTeachFile(Request $request){
        $courseId = $request->route('courseId');
        $files = TeachFile::where('Cid','=',$courseId)->get();
        Log::info($courseId);
        return view('student.wechat.courseTeachFile')->with(['files'=>$files]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * 下载课件
     */
    public function downloadTeachFile(Request $request){
        $fileId = $request->route('fileId');
        $fileInfo = TeachFile::find($fileId);
        $fileRealPath = $fileInfo->filePath;//文件保存的真实文件名 ，fileName是原始文件名
        $fileInfo->downloadTimes+=1;//下载次数+1
        $fileInfo->save();
        return response()->download(base_path().'/storage/app/teacherUpload/'.$fileRealPath,$fileInfo->fileName);
    }


    /**
     * @param Request $request
     * @return $this
     * 显示学生个人资料，并提供修改
     */
    public function showMyInfo(Request $request){
        if($request->isMethod('get')){//显示
            $studentInfo = $this->studentService->getMyInfo($this->openid);
            return view('student.wechat.showMyInfo')->with(['student'=>$studentInfo]);
        }else{
            $this->validate($request, [
                'email' => 'email',
                'major' => 'required',
                'phone' => 'required|digits:11',
            ]);
            $data = Input::all();
            $student = Student::where('openid',$this->openid)->first();
            $student->email = $data['email'];
            $student->school = $data['school'];
            $student->institute = $data['institute'];
            $student->major = $data['major'];
            $student->class = $data['class'];
            $student->phone = $data['phone'];
            $student->save();
            return redirect()->back();
        }
    }
}
