<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only allow regular users (web guard) to access admin areas
        // Block Google OAuth users from accessing dashboard
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        // If not authenticated via web guard, redirect to login
        return redirect()->route('login');
    }
}
