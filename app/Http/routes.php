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

Route::group(['middleware' => ['web','wechat']], function () {
    Route::auth();
    Route::get('/home', 'HomeController@index');

    Route::get('teacher/login', 'Teacher\AuthController@getLogin');
    Route::post('teacher/login', 'Teacher\AuthController@postLogin');
    Route::get('teacher/logout', 'Teacher\AuthController@getLogout');
    Route::get('teacher/register', 'Teacher\AuthController@getRegister');
    Route::post('teacher/register', 'Teacher\AuthController@postRegister');
    Route::get('/teacher', 'TeacherController@index');
});
