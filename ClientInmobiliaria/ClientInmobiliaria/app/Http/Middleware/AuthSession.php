<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('api_token') || !Session::has('user')) {
            return redirect()->route('login')->withErrors(['login' => 'Debes iniciar sesión primero']);
        }

        return $next($request);
    }
}
