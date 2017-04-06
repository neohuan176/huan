<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
//            Log::info($guard);
//            switch ($guard){
//                case 'teacher' : return redirect('/teacher');
//                case 'student' : return redirect('/student');
//                default : return redirect('/');
//            }
            return redirect('/');
        }

        return $next($request);
    }
}
