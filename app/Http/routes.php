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
    Route::any('/oauth_callback',['as'=>'web.oauth_callback','uses'=>'IndexController@oauthCallBack']);//微信网页授权回调接口
    Route::any('/updateTable',['as'=>'web.updateTable','uses'=>'IndexController@updateTable']);
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


    Route::get('/teacher', 'TeacherController@index');//教师主页
    Route::get('teacher/course', 'TeaCherController@showCourse');//课程表
    Route::get('/teacher/changeCallOverStatus/{courseId}/{addNewCallOver}', 'TeaCherController@changeCallOverStatus');//改变开启点名状态
    Route::get('/teacher/getCourse/{courseId}', 'TeaCherController@getCourse');//获取单个课程信息
    Route::post('/teacher/updateCoursePosition/{courseId}', 'TeaCherController@updateCoursePosition');//更新上课地理位置
    Route::get('/teacher/exportCourseExcel/{courseId}', 'TeaCherController@exportCourseExcel');//导出excel考勤表
    Route::get('/teacher/showCourseInWechat', 'TeaCherController@showCourseInWechat');//在微信显示教师课程表
    Route::get('/teacher/deleteCourse/{courseId}', 'TeaCherController@deleteCourse');//删除课程
    Route::get('/teacher/showCurCourse/{courseId}', 'TeaCherController@showCurCourse');//显示当前考勤总信息
    Route::get('/teacher/changeRecordStatus/{recordId}/{status}', 'TeaCherController@changeRecordStatus');//改变单个学生考勤状态
    Route::post('/teacher/addScore', 'TeaCherController@addScore');//加分
    Route::get('/teacher/myCourse', 'TeaCherController@showMyCourse');//教师的课程，可以删除，开课，查看班级学生操作
    Route::get('/teacher/showCourseStudents/{courseId}', 'TeaCherController@showCourseStudents');//显示课程所有学生
    Route::post('/teacher/updateAttendInfo', 'TeaCherController@updateAttendInfo');//更新教师修改学生考勤状态后的信息
    Route::get('/teacher/changeEndCourse/{courseId}', 'TeaCherController@changeEndCourse');//结课，开课
    Route::any('/teacher/toUpdateCourse/{courseId}', 'TeaCherController@toUpdateCourse');//跳转到修改课程信息页面
    Route::post('/teacher/uploadTeachFile', 'TeaCherController@uploadTeachFile');//上传课件
    Route::get('/teacher/showCourseTeachFile/{courseId}', 'TeaCherController@showCourseTeachFile');//显示课件
    Route::get('/teacher/downloadTeachFile/{fileId}', 'TeaCherController@downloadTeachFile');//下载课件
    Route::any('/teacher/ask', 'TeaCherController@ask');//上课随机提问
    Route::any('/teacher/updateTeacherInfo', 'TeaCherController@updateTeacherInfo');//修改教师个人信息
    Route::any('/teacher/updatePassword', 'TeaCherController@updatePassword');//修改教师个人信息

});

//学生微信端路由
Route::group(['middleware' => ['web','wechat']], function () {
//    學生登录注册路由
    Route::get('student/login', 'Student\AuthController@getLogin');
    Route::post('student/login', 'Student\AuthController@postLogin');
    Route::get('student/logout', 'Student\AuthController@getLogout');
    Route::get('student/register', 'Student\AuthController@getRegister');
    Route::post('student/register', 'Student\AuthController@postRegister');

    Route::get('/student', 'StudentController@index');
    Route::any('/student/joinCourse/{courseId}', 'StudentController@joinCourse');
    Route::get('/student/callOverPage', 'StudentController@callOverPage');//跳转微信网页签到页面
    Route::post('/student/updateStudentPosition', 'StudentController@updateStudentPosition');
    Route::any('/student/callOverInPage', 'StudentController@callOverInPage');//在微信网页签到
    Route::get('/student/showStudentCourse', 'StudentController@showStudentCourse');
    Route::get('/student/myAttendRecord', 'StudentController@showMyAttendRecord');
    Route::any('/student/showMyInfo', 'StudentController@showMyInfo');
    Route::any('/student/QrCallOver/{courseId}/{timestamp}', 'StudentController@QrCallOver');
});

//学生非微信路由
Route::group(['middleware' => ['web']], function () {
    Route::get('/student/showCourseTeachFile/{courseId}', 'StudentController@showCourseTeachFile');
    Route::get('/student/downloadTeachFile/{fileId}', 'StudentController@downloadTeachFile');
});


//api路由
Route::group(['middleware' => ['web']], function () {
    Route::post('teacher/addCourse','TeacherController@addCourse');
});


//管理员路由
Route::group(['middleware' => ['web']], function () {
    Route::post('/admin/teacher/alterTeacher/{teacherId}', 'HomeController@alterTeacherById');
    Route::get('/admin/teacher/showTeacherByType', 'HomeController@showTeacherByType');
    Route::get('/admin/teacher/delTeacherById/{teacherId}', 'HomeController@delTeacherById');

    Route::post('/admin/course/alterCourse/{courseId}', 'HomeController@alterCourseById');
    Route::get('/admin/course/showCourseByType', 'HomeController@showCourseByType');
    Route::get('/admin/course/delCourseById/{courseId}', 'HomeController@delCourseById');

});