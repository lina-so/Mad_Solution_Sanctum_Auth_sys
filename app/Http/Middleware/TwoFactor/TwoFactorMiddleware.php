<?php

namespace App\Http\Middleware\TwoFactor;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(auth()->check() &&  $user->verify_code)
        {
        return $next($request);
        }
        else{
             return new Response('please verify your email', 401);

        }
    }
}
