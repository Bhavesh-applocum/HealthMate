<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AdminAuth
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
        if(!Auth::guard('admin')->check()){
            return redirect('login')->with('error', 'Login as admin to access this page.');
        }

        $last_session_id  =  Session::get('last_session_id');
         $session_id = Session::getId();
 
         if(Auth::guard('admin')->user()->last_session_id != $last_session_id){ 
             Session::getHandler()->destroy($session_id);
             Auth::guard('admin')->logout();
             return redirect('login');
        }
        return $next($request);
    }
}
