<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('Cid');//课程id
            $table->string('Cname');//课程名称
            $table->unsignedInteger('Sid');//学生id
            $table->string('Sname');//学生姓名
            $table->string('openid');//微信OpenId
            $table->timestamps();

            $table->foreign('Cid')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('Sid')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('openid')->references('openid')->on('students')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('s_courses');
    }
}
