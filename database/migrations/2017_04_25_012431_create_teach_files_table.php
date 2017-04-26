<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teach_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fileName');//文件名
            $table->string('filePath');//文件路径
            $table->bigInteger('size');//文件大小
            $table->unsignedInteger('Cid');//课程id
            $table->integer('downloadTimes')->default(0);//下载次数
            $table->timestamps();

            $table->foreign('Cid')->references('id')->on('courses')->onDelete('cascade');//课程id外键
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teach_files');
    }
}
