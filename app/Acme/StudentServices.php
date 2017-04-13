<?php
/**
 * Created by PhpStorm.
 * User: a8042
 * Date: 2017/4/12
 * Time: 10:05
 */

namespace App\Student;
use App\Teacher;
use App\Student;
use App\Course;

use Illuminate\Support\Facades\DB;
class StudentServices
{
    public function __construct()
    {
        Log::info("创建studentServices实例");
    }

    public function initStudentLocation($studentInfo){
        if($this->studentExist($studentInfo->openid)){
            $student = DB::table('students')->where('openid','=',$studentInfo->openid)->first();
            if($student){
                $student->longitude = $studentInfo->Longitude;
                $student->latitude = $studentInfo->Latitude;
                $student->location_update = date();
                $student->save();
                return $student;
            }
        }
    }

    public function studentExist($openid){
        return count(DB::table('students')->where('openid','=',$openid)->get());
    }
}