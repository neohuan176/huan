<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use App\Student;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Log;

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
        $openid = Session::get('wechat_user')['id'];
        $avatarUrl = Session::get('wechat_user')['avatar'];
        Log::info('头像'.$avatarUrl);
        return Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'openid' => $openid,
            'school' => $data['school'],
            'phone' => $data['phone'],
            'institute' => $data['institute'],
            'major' => $data['major'],
            'class' => $data['class'],
            'stuNo' => $data['stuNo'],
            'avatarUrl' => $avatarUrl
        ]);

    }
}
