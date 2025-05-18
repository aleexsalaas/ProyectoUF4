<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ShareUserAndToken
{
    public function handle(Request $request, Closure $next)
    {
        $user = Session::get('user', null);
        $token = Session::get('api_token', null);
    
        View::share('user', $user);
        View::share('api_token', $token);
    
        return $next($request);
    }
    
}
