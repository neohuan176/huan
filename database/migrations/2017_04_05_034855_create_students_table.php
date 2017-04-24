<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');//id
            $table->string('openid')->unique();//微信OpenId
            $table->string('password',60);//密码
            $table->string('name');//姓名
            $table->string('email')->unique();//邮箱
            $table->string('school');//学校
            $table->string('phone');//电话
            $table->string('institute');//学院·
            $table->string('major');//专业
            $table->string('class');//班级
            $table->string('stuNo')->unique();//学号
            $table->double('longitude');//经度
            $table->double('latitude');//纬度
            $table->dateTime('location_update');//地理位置更新时间
            $table->rememberToken();//记住密码凭证
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
        Schema::drop('students');
    }
}
