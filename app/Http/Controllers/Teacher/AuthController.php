<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Teacher;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    //
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/teacher';
    protected $guard = 'teacher';
    protected $loginView = 'teacher.login';
    protected $registerView = 'teacher.register';
    protected $redirectAfterLogout = '/';

    public function __construct()
    {
//        $this->middleware('guest:teacher', ['except' => 'getLogout']);
        $this->middleware('guest:teacher', ['except' => ['getLogout','logout']]);
    }

    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:teachers',
            'password' => 'required|confirmed|min:6',
        ]);

    }

    protected function create(array $data)
    {
        return Teacher::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

    }
}
