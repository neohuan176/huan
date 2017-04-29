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
class CommonServices
{
    public function __construct(){}

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * 返回所有教师
     */
    public function getAllTeacher(){
        return Teacher::all();
    }

}