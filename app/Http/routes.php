<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::any('/index',['as'=>'web.index','uses'=>'IndexController@index']);
    Route::any('/createMenu',['as'=>'web.createMenu','uses'=>'IndexController@createMenu']);
    Route::any('/oauth_callback',['as'=>'web.oauth_callback','uses'=>'IndexController@oauthCallBack']);
});


Route::group(['middleware' => ['web']], function () {
//管理員登陸注冊路由
    Route::auth();
    Route::get('/home', 'HomeController@index');

//    教师登录注册路由
    Route::get('teacher/login', 'Teacher\AuthController@getLogin');
    Route::post('teacher/login', 'Teacher\AuthController@postLogin');
    Route::get('teacher/logout', 'Teacher\AuthController@getLogout');
    Route::get('teacher/register', 'Teacher\AuthController@getRegister');
    Route::post('teacher/register', 'Teacher\AuthController@postRegister');


    Route::get('/teacher', 'TeacherController@index');
    Route::get('teacher/course', 'TeaCherController@showCourse');
    Route::get('/teacher/changeCallOverStatus/{courseId}', 'TeaCherController@changeCallOverStatus');
});

Route::group(['middleware' => ['web','wechat']], function () {
//    學生登录注册路由
    Route::get('student/login', 'Student\AuthController@getLogin');
    Route::post('student/login', 'Student\AuthController@postLogin');
    Route::get('student/logout', 'Student\AuthController@getLogout');
    Route::get('student/register', 'Student\AuthController@getRegister');
    Route::post('student/register', 'Student\AuthController@postRegister');

    Route::get('/student', 'StudentController@index');
});

//api路由
Route::group(['middleware' => ['web']], function () {
    Route::post('teacher/addCourse','TeacherController@addCourse');
});