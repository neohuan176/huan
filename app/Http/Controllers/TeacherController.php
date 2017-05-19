<?php

namespace App\Http\Controllers;

use App\Acme\TeacherServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Auth;
use Log;
use App\Course;
use EasyWeChat\Foundation\Application;
use App\AttendRecord;
use App\Student;
use App\SCourse;
use Maatwebsite\Excel\Facades\Excel;
use App\TeachFile;

class TeacherController extends Controller
{
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
    protected $TeacherServ;
    public function __construct(TeacherServices $teacherServices)
    {
        $this->TeacherServ = $teacherServices;
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
        $app = new Application($this->options);
        $js = $app->js;
        $courses = Course::where('TeacherId' ,'=', Auth::guard('teacher')->user()->id)->where('isEnd',false)->get();
        return view('teacher.Course')->with(['courses' => $courses,'js'=>$js]);
    }

    /**
     * @param Request $request
     * @return $this
     * 显示教师的课程，用于显示所有学生，和给学生发消息。
     */
    public function showMyCourse(Request $request){
        $courses = Course::where('TeacherId' ,'=', Auth::guard('teacher')->user()->id)->get();
        return view('teacher.myCourse')->with(['courses' => $courses]);
    }

    /**
     * @param Request $request
     * @return $this
     * 显示本课程的所有学生
     */
    public function showCourseStudents(Request $request){
        $courseId = $request->route('courseId');
        $students = Student::whereIn('id',SCourse::where('Cid', $courseId)->pluck('Sid'))->orderBy('stuNo','asc')->get();
        return view('teacher.courseStudents')->with(['students' => $students]);
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
//        $course->StartTime = $data['StartTime'];
//        $course->EndTime = $data['EndTime'];
        $course->Address = $data['Address'];
        $course->weekday = $data['weekday'];
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

        if($course->callOver ==0 ){//如果是课程第一次开启点名，怎样都加1
            $course->callOver += 1;
        }elseif($addNewCallOver){//是否开始一次新的点名
            $course->callOver += 1;
            Log::info("开启一次新的点名！".$addNewCallOver);
        }

        if($course->save()){
            return ['status' => '200','isOpenCall' =>  $course->isOpenCall , 'callOver' => $course->callOver];
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
            return "课程定位成功";
        }
        else{
            return "修改失败";
        }
    }

    /**
     * @param Request $request
     * 导出课程考勤表
     */
    public function exportCourseExcel(Request $request){
        $courseId = $request->route('courseId');
        $this->TeacherServ->exportCourseExcel($courseId);
    }

    /**
     * @param Request $request
     * @return $this
     * 在微信端打开我的课程
     * 主要是为了课程定位准确
     */
    public function showCourseInWechat(Request $request){
        $app = new Application($this->options);
        $js = $app->js;
        $courses = Course::where('TeacherId' ,'=', Auth::guard('teacher')->user()->id)->get();
        return view('teacher.CourseInWechat')->with(['courses' => $courses,'js'=>$js]);
    }

    /**
     * @param Request $request
     * @return array
     * 删除课程----------->要增加外键删除
     */
    public function deleteCourse(Request $request){
        $courseId = $request->route('courseId');
        if(Course::destroy($courseId)){
            return ['status'=>200];
        }
        return ['status'=>110];
    }

    /**
     * @param Request $request
     * @return $this
     * 显示当节课程的最新一次考勤信息记录，并提供考勤当前考勤记录的基本操作
     */
    public function showCurCourse(Request $request){
        $courseId = $request->route('courseId');
        //找出该课程的考勤次数
        $course = Course::find($courseId);
        $callOver = $course->callOver;
        $records = AttendRecord::where('Cid',$courseId)->where('callOver',$callOver)->orderBy('status','asc')->get();//找出最近一次的考勤记录表
        //统计出已到，旷课，迟到，请假
        $late = 0;//迟到次人数
        $unCall = 0;//旷课人数
        $leave = 0;//请假人数
        $attend = 0;//实到人数
        $total = $course->student_count;
            foreach ($records as $record){
                    switch ($record->status){
                        case 1 :  $attend++ ;break;
                        case 2 :  $unCall++ ;break;
                        case 3 :  $late++;$attend++ ;break;//迟到也是已到
                        case 4 :  $leave++ ;break;
                    }
            }
        if($total == 0){//不能除0
            $attend_rate = 0;
        }else{
            $attend_rate = round($attend/$total,4);
        }
        $info = ['attend'=>$attend,'unCall'=>$unCall,'late'=>$late,'leave'=>$leave,'studentTotal'=>$total,'attend_rate'=>$attend_rate];
        return view('teacher.courseAttendenceRecord')->with(["records"=>$records,'course'=>$course,'courseInfo'=>$info]);
    }

    /**
     * @param Request $request
     * @return string
     * 修改学生签到状态
     */
    public function changeRecordStatus(Request $request){
        $recordId = $request->route('recordId');
        $status = $request->route('status');
        $record = AttendRecord::find($recordId);
        $record->status = $status;
        if($record->save()){
            return "修改签到状态成功";
        }else{
            return "修改失败";
        }
    }

    /**
     * @param Request $request
     * @return string
     * 学生加分操作
     */
    public function addScore(Request $request){
        $score = $request->input('score');
        $recordId = $request->input('recordId');
        $record =  AttendRecord::find($recordId);
        $record->score+=$score;
        $record->save();
        return ['score'=>$record->score,'msg'=>"加分成功！"];
    }


    /**
     * @param Request $request
     * @return array
     * 更新教师修改学生考勤状态后的信息
     */
    public function updateAttendInfo(Request $request){
        $courseId = $request->input('courseId');
        //找出该课程的考勤次数
        $course = Course::find($courseId);
        $callOver = $course->callOver;
        $records = AttendRecord::where('Cid',$courseId)->where('callOver',$callOver)->get();//找出最近一次的考勤记录表

        //统计出已到，旷课，迟到，请假
        $late = 0;//迟到次人数
        $unCall = 0;//旷课人数
        $leave = 0;//请假人数
        $attend = 0;//实到人数
        $total = $course->student_count;
        foreach ($records as $record){
            switch ($record->status){
                case 1 :  $attend++ ;break;
                case 2 :  $unCall++ ;break;
                case 3 :  $late++;$attend++;break;
                case 4 :  $leave++ ;break;
            }
        }
        if($total == 0){//不能除0
            $attend_rate = 0;
        }else{
            $attend_rate = round($attend/$total,4);
        }
        $info = ['attend'=>$attend,'unCall'=>$unCall,'late'=>$late,'leave'=>$leave,'studentTotal'=>$total,'attend_rate'=>$attend_rate];
        return $info;
    }


    /**
     * @param Request $request
     * @return string
     * 结课
     */
    public function changeEndCourse(Request $request){
        $courseId = $request->route('courseId');
        $course = Course::find($courseId);
        $course->isEnd = $course->isEnd==0?1:0;
        $course->save();
        return "修改课程结课状态成功".$course->Cname;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 跳转修改课程信息页面
     */
    public function toUpdateCourse(Request $request){
        $courseId = $request->route('courseId');
        if($request->isMethod('get')){
            $courseInfo = Course::find($courseId);
            return view('teacher.updateCourse',['courseInfo'=>$courseInfo]);
        }else{
            $this->validate($request, [
                'weekday' => 'required',
            ]);
            $course = Course::find($courseId);
            $course->weekday = Input::get('weekday');
            $course->Address = Input::get('Address');
            $course->save();
            return redirect('/teacher/course');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 上传课件
     */
    public function uploadTeachFile(Request $request){
        $courseId = $request->get('courseId');
        $file = $request->file('myFile');//获取上传文件
        $clientName = $file -> getClientOriginalName();//文件原名
        $extension = $file -> getClientOriginalExtension(); //上传文件的后缀.
        $realPath = $file->getRealPath();//临时文件的绝对路径
        $size = $file->getSize();
        $newName = md5(date('ymdhis').$clientName).".".$extension;//生成新的文件名
        $teach_file = new TeachFile();
        $teach_file->fileName = $clientName;
        $teach_file->filePath = $newName;
        $teach_file->size = $size;
        $teach_file->Cid = $courseId;
        if(Storage::disk('teacherUpload')->put($newName,file_get_contents($realPath)) && $teach_file->save()){//保存到storage
                return redirect('teacher/course');
        }else{
            exit('上传失败！');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 显示课程课件
     */
    public function showCourseTeachFile(Request $request){
        $courseId = $request->route('courseId');
        $files = TeachFile::where('Cid','=',$courseId)->get();
        Log::info($courseId);
        return view('teacher.courseTeachFile')->with(['files'=>$files]);
    }

    /**
     * @param Request $request
     * @return mixed
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
     * @return mixed
     * 提问
     */
    public function ask(Request $request){
        if($request->isMethod("get")){
            $courseId = $request->get('courseId');
            $course = Course::find($courseId);
            $attendRecords = AttendRecord::where('Cid',$courseId)->where('callOver',$course->callOver)->whereIn('status',array(1,3))->get();
            if(count($attendRecords)!=0){
                $cur_ask_record = $attendRecords[array_rand($attendRecords->toArray(),1)];
                Log::info("提问".$cur_ask_record->Sname);
                return $cur_ask_record;
            }else{
                return null;
            }
        }else{//post请求
            $cur_ask_record = AttendRecord::find($request->get('recordId'));
            if($cur_ask_record){
                $cur_ask_record->score+=$request->get('score');
                $cur_ask_record->status=$request->get('status');
                $cur_ask_record->save();
                return "提问成功！";
            }else{
                return "没有学生已到！";
            }

        }
    }

    /**
     * @param Request $request
     * @return $this
     * 修改教师信息
     */
    public function updateTeacherInfo(Request $request){
        $teacher = $request->user('teacher');
        if($request->isMethod("get")){
            return view("teacher.updateTeacherInfo")->with(['teacher'=>$teacher]);
        }else{
            $this->validate($request, [
                'school' => 'required',
                'phone' => 'required|digits:11',
//                'password' => 'required|min:6|confirmed',
//                'password_confirmation' => 'required|min:6',
            ]);

            $data = Input::all();
            $teacher->name = $data['name'];
            $teacher->school = $data['school'];
            $teacher->phone = $data['phone'];
//            $teacher->password = bcrypt($data['password']);
            if($teacher->save()){
                return view("teacher.updateTeacherInfo")->with(['teacher'=>$teacher])->with(['success'=>true]);
            }else{
                return "修改失败";
            }
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * 修改密码
     */
    public function updatePassword(Request $request){
        $teacher = $request->user('teacher');
        if($request->isMethod('get')){
            return view("teacher.updatePassword");
        }else{
            $this->validate($request, [
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);
            $data = Input::all();
            $teacher->password = bcrypt($data['password']);
            if($teacher->save()){
                return view("teacher.updatePassword")->with(['success'=>true]);
            }else{
                return "修改失败";
            }
        }
    }
}
