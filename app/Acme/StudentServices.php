<?php
/**
 * Created by PhpStorm.
 * User: a8042
 * Date: 2017/4/12
 * Time: 10:05
 */

namespace App\Acme;
use App\Teacher;
use App\Student;
use App\Course;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class StudentServices
{
    public function __construct(){}

    /**
     * @param $studentInfo
     * @return boolean
     * 重新获取学生的地理位置信息
     */
    public function initStudentLocation($studentInfo){
        if($this->studentExist($studentInfo->FromUserName)){
            $student = Student::where('openid','=',$studentInfo->FromUserName)->firstOrFail();
            if($student){
                $student->longitude = $studentInfo->Longitude;
                $student->latitude = $studentInfo->Latitude;
                $student->location_update = date('y-m-d h:i:s');
                $student->save();
                return true;
            }
        }
        return false;
    }

    /**
     * @param $openid
     * @return int
     * 判断学生是否存在
     */
    public function studentExist($openid){
        return count(DB::table('students')->where('openid','=',$openid)->get());
    }
}