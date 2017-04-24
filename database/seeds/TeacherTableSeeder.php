<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teachers')->insert([
            ['openid'=>'130202051009','account'=>'L777520','password'=>'666666','name'=>'neo',
                'email'=>'804258636@qq.com','school'=>'北京理工大学珠海学院'],
            ['openid'=>'130202051010','account'=>'Y777520','password'=>'qinshi520','name'=>'Leo',
                'email'=>'a804258636@outlook.com','school'=>'北京理工大学珠海学院'],
        ]);
    }
}
