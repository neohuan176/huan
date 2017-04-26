<?php

namespace App\Http\Middleware;

use Closure;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Log;

class Wechat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
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
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/huan/public/oauth_callback',
            ],
        ];
        $app = new Application($config);
        $oauth = $app->oauth;
//        Session::forget("wechat_user");
//        如果用户未登录,就发起网页授权请求
        if (!Session::has("wechat_user")) {
            Session::put("target_url",$request->url());
            $response = $oauth->scopes(['snsapi_userinfo'])
                ->setRequest($request)
                ->redirect();
            return $response;
        }
        if(Session::get("wechat_user")['id'] == null){//存在微信用户信息，但是openid为空 ，不知道为什么是空，偶尔测试到
            Session::put("target_url",$request->url());
            $response = $oauth->scopes(['snsapi_userinfo'])
                ->setRequest($request)
                ->redirect();
            return $response;
        }
        return $next($request);
    }
}
