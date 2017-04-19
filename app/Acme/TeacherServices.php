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
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class TeacherServices
{
    public function __construct(){}

    public function exportCourseExcel($courseId){

        //方案1：找出该课程的所有考勤记录，按照学生姓名分组，获得每个分组（学生）的记录。。。。。

//先找出课程的总共考勤次数；然后新建二维数组 record[自增][考勤次数]
        //方案2（简单？）：找出课程的所有学生。遍历每一个学生。根据学生id和课程id找出学生的全部考勤记录，（把考勤记录处理）
        //考勤记录处理（单个学生）：新建一个数组，把考勤记录按照考勤顺序排序，



        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        Excel::create('学生成绩',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

}