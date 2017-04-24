<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attend_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default(3);//签到状态  1已到，2迟到，3旷课，4早退
            $table->string('Sno');//学号
            $table->integer('callOver');//课程的第几次点名，和Course的callOver外键
            $table->dateTime('attendDate');//出勤时间
            $table->unsignedInteger('Cid');//课程id
            $table->string('Cname');//课程名称
            $table->unsignedInteger('Sid');//学生id
            $table->string('Sname');//学生姓名
            $table->integer('score')->default(0);//提问得分
            $table->timestamps();

            $table->foreign('Sno')->references('stuNo')->on('students')->onDelete('cascade');
            $table->foreign('Cid')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('Sid')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attend_records');
    }
}
