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

    /**
     * @param $courseId
     * 导出考勤表
     */
    public function exportCourseExcel($courseId){

        //方案1：找出该课程的所有考勤记录，按照学生姓名分组，获得每个分组（学生）的记录。。。。。

//先找出课程的总共考勤次数；然后新建二维数组 record[自增][考勤次数]
        //方案2（简单？）：找出课程的所有学生。遍历每一个学生。根据学生id和课程id找出学生的全部考勤记录，（把考勤记录处理）
        //考勤记录处理（单个学生）：新建一个数组，把考勤记录按照考勤顺序排序，遍历考勤记录，for循环遍历课程考勤次。如果没有
        //对应次数的考勤记录，就默认添加该数组项为“旷课2”，否则就添加该数组的考勤状态和加分情况，
        //统计每个学生的应到次数，实到次数，迟到次数，旷课次数，请假次数。->orderBy('stuNo', 'desc')->groupBy('major)
        //pluck可能有问题。。。。注意
//        Log::info(SCourse::where('Cid', $courseId)->pluck('Sid'));
        $students = Student::whereIn('id',SCourse::where('Cid', $courseId)->pluck('Sid'))->get();
        $Data = array();
        $course = Course::find($courseId);
        $callOver = $course->callOver;//该课程的考勤次数

        //处理表头
        $Data[0][0] = "专业";
        $Data[0][1] = "姓名";
        $Data[0][2] = "学号";
        $callOverIndex = 1;
        for($k=3 ; $k<=$callOver+2;$k++,$callOverIndex++){
            $Data[0][$k] = '考勤'.$callOverIndex;
        }
        $Data[0][$callOver+2+1] = "请假";
        $Data[0][$callOver+2+2] = "迟到";
        $Data[0][$callOver+2+3] = "旷课";
        $Data[0][$callOver+2+4] = "应到";
        $Data[0][$callOver+2+5] = "实到";
        $Data[0][$callOver+2+6] = "总加分";

        $i = 1;
        foreach($students as $student){
            $studentRecords = AttendRecord::where('Cid',$courseId)->where('Sid',$student->id)->orderBy('callOver', 'desc')->get();
            //添加姓名学号
            $Data[$i][0] = $student->major;//考勤表专业列
            $Data[$i][1] = $student->name;//姓名列
            $Data[$i][2] = $student->stuNo;//学号列
            $score = 0;//加分总分
            $late = 0;//迟到次数
            $unCall = 0;//旷课次数
            $leave = 0;//请假
            $attend = 0;//实到
            for($j=1 ; $j <= $callOver ;$j++){//遍历该课程的所有考勤
                $cur_record = null;
                $status = " ";//考勤状态
                foreach ($studentRecords as $record){
                    if($record->callOver == $j){//有对应课程考勤次数（第几次）的记录;
                        $cur_record = $record;
                        switch ($cur_record->status){
                            case 1 : $status = "#"; $attend++ ;break;
                            case 2 : $status = "旷课";$unCall++ ;break;
                            case 3 : $status = "迟到"; $late++;$attend++ ;break;
                            case 4 : $status = "请假"; $leave++ ;break;
                        }
                        if($cur_record->score != 0){//当次记录有加分
                            $status.=" +$cur_record->score";
                            $score+=$cur_record->score;
                        }
                        $Data[$i][$j + 2] = $status;
                    }
                }
                if(!$cur_record){//如果没有那次的记录，直接记旷课
                    $Data[$i][$j + 2] = "旷课";
                    $unCall++;
                }
            }

            //处理尾部
            $Data[$i][$callOver + 2 +1] = $leave;
            $Data[$i][$callOver + 2 +2] = $late;
            $Data[$i][$callOver + 2 +3] = $unCall;
            $Data[$i][$callOver + 2 +4] = $callOver;
            $Data[$i][$callOver + 2 +5] = $attend;
            $Data[$i][$callOver + 2 +6] = $score;

            $i++;//记录是第几行记录
        }
        Excel::create($course->Cname.'考勤表',function($excel) use ($Data){
            $excel->sheet('callOver', function($sheet) use ($Data){
                $sheet->rows($Data);
            });
        })->export('xls');
    }

}