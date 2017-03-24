<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use EasyWeChat\Foundation\Application;

class IndexController extends Controller
{
    //微信服务器验证
    public function index(){
        $options = [
            'debug'     => true,
            'app_id'    => 'wx1e2a465af8b71bd5',
            'secret'    => '1c5e08111d8d8ff350a4490af1c080a1',
            'token'     => 'yejinhuan',
            'log' => [
                'level' => 'debug',
                'file'  => '/tmp/easywechat.log',
            ],
            // ...
        ];
        $app = new Application($options);
// 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            return "您好！欢迎关注我!";
        });

        $response = $server->serve();
        return $response;
    }
}
