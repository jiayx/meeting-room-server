<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
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
        if (Auth::guard('admin')->guest()) {
            // return redirect('/admin/login', 302, [], $request->isSecure());
            return redirect('/admin/login', 302, [], config('app.env') == 'production');
        } else {
            return $next($request);
        }
    }
}
