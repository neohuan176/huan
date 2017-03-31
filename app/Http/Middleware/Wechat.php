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
        Log::info("session中存在用户！");
        return $next($request);
    }
}
