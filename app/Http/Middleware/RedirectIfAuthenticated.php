<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::guard('admin')->check()) {
            if ($request->session()->has('redirect_to')) {
                $redirect = $request->session()->get('redirect_to');
                $request->session()->forget('redirect_to');
                return redirect()->to($redirect);
            }
            return redirect()->route('admin.home');
        }

        return $next($request);
    }
}
