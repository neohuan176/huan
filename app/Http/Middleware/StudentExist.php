<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StudentExist
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
        //        如果学生为注册，就跳转主页页面，否则跳转到请求页面
        $openid = Session::get('wechat_user')['id'];
        if(count(DB::table('students')->where('openid','=',$openid)->get()) < 1){
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                Log::info("去注册！");
                return redirect('student/register');
            }
        }
        Log::info("学生已经注册！");
        return $next($request);
    }
}
