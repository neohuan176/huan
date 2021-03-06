<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Log;
use App\Acme\StudentServices;

class IndexController extends Controller
{
    protected $studentServices;
    //微信sdk基本配置信息
    protected  $options = [
        'debug'     => true,
        'app_id'    => 'wx2a8f750c494dee0b',
        'secret'    => 'f8a372d1d0b791e3260c06c957655ceb',
        'token'     => 'neo',
        'aes_key' => 'EajwoAzVnZxYTFUAakjM1aOf4L3VRdaHe86nnLJytEg',
        'log' => [
        'level' => 'debug',
        'file'  => '/ProSoftware/xampp/htdocs/huan/tmp/easywechat.log',
        ],
    ];

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 微信服务器回调地址
     */
    public function index(){
        $app = new Application($this->options);
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'event':
                {
                    //判断event事件的类型
                    switch ($message->Event){

                        case "LOCATION" ://上报地理位置事件
                                App::make('StudentServ')->initStudentLocationWgToGc($message);//重新初始化学生的地理位置信息，并将经纬度wgs84,转为gcj02
                                return "success";
                            break;

                        case "CLICK" ://点击类型
                        {
                            switch ($message->EventKey){
                                case "CallOver" ://考勤签到
                                    return App::make('StudentServ')->callOver($message->FromUserName);//返回学生考勤信息
                                    break;
                            }
                            break;
                        }
                        default :break;
                    }
                }
                    //先判断是否含有学生的经纬度，然后将最新的地理位置信息保存到学生的信息中。（先学生未注册判断，）
//                    判断是否获取到了用户的地理位置，保存地理位置信息到学生的信息中，
//                      怎么判断学生的地理位置信息是最新的（增加一个字段<更新地理位置信息的时间>，
//                  然后在考勤的时候对比考勤开启的时间和更新地理位置信息的时间是否在一定的时间段内。
//              如果不在这个时间段内，就提示学生重新进入公众号或者检查有没允许公众号获取地理位置信息。）
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        // 从项目实例中得到服务端应用实例。
        $response = $server->serve();
        return $response;
    }

    /**
     * 更新微信自定义菜单
     */
    public function createMenu(){
        $app = new Application($this->options);
        $menu = $app->menu;
        $buttons = [
            //旧的签到（通过微信定位的地理位置信心签到，发现偏差很大，最后发现原因是，微信自动上报的地理坐标是wgs84，而课室选择地图的是gcj02坐标的。所以偏差大）
            [
                "type" => "click",
                "name" => "签到",
                "key"  => "CallOver"
            ],
            [
                "name"       => "教师",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "教师注册",
                        "url"  => "http://zy595312011.vicp.io/huan/public/teacher/register"
                    ],
                    [
                        "type" => "view",
                        "name" => "教师主页",
                        "url"  => "http://zy595312011.vicp.io/huan/public/teacher"
                    ],
                    [
                        "type" => "view",
                        "name" => "我的课程",
                        "url"  => "http://zy595312011.vicp.io/huan/public/teacher/showCourseInWechat"
                    ],
                    [
                        "type" => "scancode_push",
                        "name" => "扫一扫",
                        "key" =>"rselfmenu",
                    ],
                ],
            ],
            [
                "name"       => "学生",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "学生注册",
                        "url"  => "http://zy595312011.vicp.io/huan/public/student/register"
                    ],
                    [
                        "type" => "view",
                        "name" => "个人信息",
                        "url"  => "http://zy595312011.vicp.io/huan/public/student/showMyInfo"
                    ],
//                    [
//                        "type" => "view",
//                        "name" => "签到",
//                        "url"  => "http://zy595312011.vicp.io/huan/public/student/callOverPage"
//                    ],
                    [
                        "type" => "view",
                        "name" => "考勤记录",
                        "url"  => "http://zy595312011.vicp.io/huan/public/student/myAttendRecord"
                    ],

                    [
                        "type" => "view",
                        "name" => "我的课程",
                        "url"  => "http://zy595312011.vicp.io/huan/public/student/showStudentCourse"
                    ],
                    [
                        "type" => "view",
                        "name" => "学生网页签到",
                        "url" => "http://zy595312011.vicp.io/huan/public/student/callOverPage"
                    ],

                ],
            ],
        ];
        $menu->add($buttons);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 微信网页授权回调方法
     */
    public function oauthCallBack(){
        $config = [
            'debug'     => true,
            'app_id'    => 'wx2a8f750c494dee0b',
            'secret'    => 'f8a372d1d0b791e3260c06c957655ceb',
            'token'     => 'neo',
            'aes_key' => 'EajwoAzVnZxYTFUAakjM1aOf4L3VRdaHe86nnLJytEg',
            'log' => [
                'level' => 'debug',
                'file'  => '/ProSoftware/xampp/htdocs/huan/tmp/easywechat.log',
            ],
        ];
        $app = new Application($config);
        $oauth = $app->oauth;
        $user = $oauth->user();
        Session::put("wechat_user",$user->toArray());//往Session中加入用户的微信基本信息
        $targetUrl = !Session::has("target_url") ? '/' : Session::get("target_url");
        return redirect($targetUrl);
    }

    /**
     * 用来更新数据表
     */
    public function updateTable(){
        Schema::table('teachers',function($table){

//            $table->foreign('Cid')->references('id')->on('courses')->onDelete('cascade');

//            $table->dropForeign('attend_records_Cid_foreign');

            $table->string('phone');
//            $table->dropColumn('openCallOverTime');
//            $table->string('openCallOverTime')->default(date('Y-m-d H:i:s',time()));//地理位置更新时间
//            $table->foreign('TeacherId')->references('id')->on('teachers');
//            $table->foreign('TeacherName')->references('name')->on('teachers');

//            $table->double('longitude');
//            $table->double('latitude');
//        return '更新表成功！';
        });
    }
}
