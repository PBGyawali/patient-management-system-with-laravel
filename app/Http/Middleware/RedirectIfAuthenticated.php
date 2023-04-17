<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            //redirect if the user is already authenticated
            if (Auth::guard($guard)->check()) {
                if ($request->ajax()) {
                    return response()->json(array('response'=>RouteServiceProvider::HOME));
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
