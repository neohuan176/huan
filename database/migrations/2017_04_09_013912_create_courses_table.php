<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');//id
            $table->string('Cno');//课程编号  自动生成还是手动：先手动
            $table->string('Cname');//课程名称
            $table->time('StartTime');//上课时间
            $table->time('EndTime');//下课时间
            $table->string('Address');//上课地点
            $table->unsignedInteger('TeacherId');//教师id
            $table->string('TeacherName');//教师名字
            $table->double('Longitude');//经度
            $table->double('Latitude');//纬度
            $table->integer('callOver')->default(0);//点名次数
            $table->boolean('isOpenCall')->default(false);//增加是否开启点名字段
            $table->dateTime('openCallOverTime')->default(date('Y-m-d H:i:s',time()));//上一次开启点名的时间
            $table->string('weekday')->default(1);//周几上课0-7
            $table->integer('student_count');//课程总人数
            $table->boolean('isEnd')->default(false);//是否结束课程

            $table->foreign('TeacherId')->references('id')->on('teachers')->onDelete('cascade');

            $table->timestamps();//时间戳
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('courses');
    }
}
