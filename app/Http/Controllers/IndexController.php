<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Log;

class IndexController extends Controller
{
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

    //微信服务器验证
    public function index(){
        $app = new Application($this->options);
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            return "家里的风景。test";
        });
        // 从项目实例中得到服务端应用实例。
        $response = $server->serve();
        return $response;
    }
    // 自定义菜单。
    public function createMenu(){
        $app = new Application($this->options);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "考勤",
                "key"  => "V1001_TODAY_MUSIC"
            ],
            [
                "name"       => "教师",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "注册",
                        "url"  => "http://16k86z5010.iok.la/huan/public/teacher/register"
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
    }

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
        Session::put("wechat_user",$user->toArray());
        $targetUrl = !Session::has("target_url") ? '/' : Session::get("target_url");
        return redirect($targetUrl);
    }
}
