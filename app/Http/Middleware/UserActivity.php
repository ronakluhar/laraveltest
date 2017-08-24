<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
class UserActivity
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
        if(Auth::check())
        {
            // Save User each activity;
            $user = new User;
            $user->SaveUserActivity(Auth::user()->id);            
        }
        return $next($request);
    }
}
