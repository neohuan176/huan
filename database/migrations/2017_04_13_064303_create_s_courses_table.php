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
            $table->integer('Cid');//课程id
            $table->string('Cname');//课程名称
            $table->integer('Sid');//学生id
            $table->string('Sname');//学生姓名
            $table->timestamps();
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
