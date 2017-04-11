<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $fillable = ['Cno','Cname'];//可批量填充数据
}
