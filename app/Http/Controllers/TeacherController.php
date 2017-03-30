<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
//        $this->middleware('auth:teacher');
    }

    public function index()
    {
//        $teacher = Auth::guard('teacher')->user();
        return view('teacherHome');
    }
}
