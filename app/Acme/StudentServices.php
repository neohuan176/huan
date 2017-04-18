<?php
/**
 * Created by PhpStorm.
 * User: a8042
 * Date: 2017/4/12
 * Time: 10:05
 */

namespace App\Acme;
use App\AttendRecord;
use App\Teacher;
use App\Student;
use App\Course;
use App\SCourse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class StudentServices
{
    public function __construct(){}

    /**
     * @param $studentInfo
     * @return boolean
     * 重新获取学生的地理位置信息，进入微信时使用
     */
    public function initStudentLocation($studentInfo){
        if($this->studentExist($studentInfo->FromUserName)){
            $student = Student::where('openid','=',$studentInfo->FromUserName)->firstOrFail();
            if($student){
                $student->longitude = $studentInfo->Longitude;
                $student->latitude = $studentInfo->Latitude;
                $student->location_update = date('Y-m-d H:i:s',time()+8*3600);
                $student->save();
                return true;
            }
        }
        return false;
    }

    /**
     * @param $courseId
     * @param $openid
     * @return bool
     * 学生加入课程
     */
    public function joinCourse($courseId,$openid){
        $course = Course::find($courseId);
        $student = DB::table('students')->where('openid','=',$openid)->first();
        $s_couse = new SCourse();
        $s_couse->openid = $openid;
        $s_couse->Cid = $course->id;
        $s_couse->Cname = $course->Cname;
        $s_couse->Sid = $student->id;
        $s_couse->Sname = $student->name;
        if($s_couse->save()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $openid
     * 学生考勤签到
     * @return String
     *
     */
    public function callOver($openid){
        //查找学生加入的所有课程
        //查找出加入的课程里开启了考勤的课程
        //先处理只有一个课程的情况（如果上述课程大于两个怎么处理？）
        //获取该课程的信息。
        //获取学生的信息，
        //判断课程上课坐标和学生信息里的坐标是否在一定范围内。
        //如果在这个范围内就生成考勤记录
        //否则就提示相关信息
        $student = Student::where('openid','=',$openid)->first();
        $my_courses = SCourse::where('openid','=',$openid)->pluck('Cid');
        $course = DB::table('courses')->where('isOpenCall','=',1)->whereIn('id',$my_courses)->first();

        //学生考勤的时候要知道是该课程第几次考勤，并且查找该课程当次的考勤记录存不存在该学生的记录，如果存在，就提示已经
        //考勤过了
        if(!$course){
            return "暂无你的考勤课程";
        }
        $attendRecord = AttendRecord::where('Sid','=',$student->id)->where('callOver','=',$course->callOver)->first();
        if($attendRecord && $attendRecord->status==1){//考勤状态为已到
            return "你已经考勤过了";
        }
        if($attendRecord && $attendRecord->status!=1){//考勤状态为其他,就更新考勤状态为已到
            $attendRecord->status = 1;
            $attendRecord->save();
            return "更新考勤状态成功";
        }
        else{
            if($student->location_update < $course->openCallOverTime){//判断是否在考勤时间内,可能不用判断。判断了会引起提前进入公众号就要重新进入才能考勤
                return "请更新你的位置信息。（重新进入公众号）";
            }
            else{
                if($this->isInRange($student,$course) <= 800){//判断是否在考勤范围内,如果在，就添加考勤记录
                    $attend_record = new AttendRecord();
                    $attend_record->status = 1;
                    $attend_record->Sno = $student->stuNo;
                    $attend_record->callOver = $course->callOver;
                    $attend_record->attendDate = date('Y-m-d H:i:s',time()+8*3600);
                    $attend_record->Cid = $course->id;
                    $attend_record->Cname = $course->Cname;
                    $attend_record->Sid = $student->id;
                    $attend_record->Sname = $student->name;
                    if($attend_record->save()){
                        $str = "课程名称：".$attend_record->Cname."\n第".$attend_record->callOver."次考勤";
                        return $str;
                    }
                    else{
                        return "考勤失败！";
                    }
                }else{
                    return "你不在考勤范围内，请确认允许公众号获取地理位置！";
                }
            }

        }
    }

    public function callOverInPage($openid){

    }

    /**
     * @param $openid
     * @return int
     * 判断学生是否存在
     */
    public function studentExist($openid){
        return count(DB::table('students')->where('openid','=',$openid)->get());
    }


    /**
     * @param $openid
     * @param $courseId
     * @return bool
     * 判断学生是否加入该课程
     */
    public function isAlreadyJoinCourse($openid,$courseId){
        return count(DB::table("s_courses")->where('openid','=',$openid)->where('Cid','=',$courseId)->get())?true:false;
    }

    /**
     * @param $student
     * @param $course
     * 判断学生是否在考勤范围内,根据经纬度，计算两点距离
     * @return $s 两点距离
     */
    public function isInRange($student,$course){
        $lat1 = $student->latitude;
        $lng1 = $student->longitude;
        $lng2 = $course->Longitude;
        $lat2 = $course->Latitude;

        Log::info($lng1."   ".$lat1);
        Log::info($lng2."   ".$lat2);

        $PI = 3.14159265;
        $EARTH_RADIUS = 6378137;
        $RAD = pi() / 180.0;

             $radLat1 = $lat1 * $RAD;
             $radLat2 = $lat2 * $RAD;
             $a = $radLat1 - $radLat2;
             $b = ($lng1 - $lng2) * $RAD;
             $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
            $s = $s * $EARTH_RADIUS;
            $s = round($s * 10000) / 10000;
            Log::info($s);
            return $s;
    }


    public function updateStudentPosition($openid,$longitude,$latitude){
        $student = Student::where('openid','=',$openid)->firstOrFail();
        if($student){
            $student->longitude = $longitude;
            $student->latitude = $latitude;
            $student->location_update = date('Y-m-d H:i:s',time()+8*3600);
            $student->save();
            return true;
        }
        else{
            return false;
        }
    }
}