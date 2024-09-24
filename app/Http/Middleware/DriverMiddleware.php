<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;


class DriverMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( Auth::check() )
        {
            if (Auth::guard('api')->check() && Auth::user()->user_type == "D" && Auth::user()->status == "Y") {
                return $next($request);
            } else {
                $message = ["message" => "Permission Denied",'status' => 401];
                return response($message, 401);
            }
        }

        // if (Auth::guard('api')->check() && Auth::user()->user_type == "D" && Auth::user()->status == "Y") {
        //     return $next($request);
        // } else {
        //     $message = ["message" => "Permission Denied",'status' => 401];
        //     return response($message, 401);
        // } 
    }

}
