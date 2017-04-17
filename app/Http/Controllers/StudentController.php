<?php

namespace App\Http\Controllers;

use App\Acme\StudentServices;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Course;
use App\Student;

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
    public function __construct(StudentServices $studentService){
//        $this->middleware('student');
        $this->studentService = $studentService;
        $this->middleware('studentExist');//判断学生是否已经注册
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
}
