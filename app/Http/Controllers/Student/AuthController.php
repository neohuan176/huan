<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Student;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/student';
    protected $guard = 'student';
    protected $loginView = 'student.login';
    protected $registerView = 'student.register';
    protected $redirectAfterLogout = '/student/login';

    public function __construct()
    {
//        $this->middleware($this->guestMiddleware(), ['except' => ['getLogout','logout']]);
//        $this->middleware('guest:student', ['except' => ['getLogout','logout']]);
    }

    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:students',
            'password' => 'required|confirmed|min:6',
            'school' => 'required',
        ]);

    }

    protected function create(array $data)
    {
        return Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'openid' => time(),//获取用户的openid,先用时间戳代替
            'school' => $data['school'],
        ]);

    }
}
