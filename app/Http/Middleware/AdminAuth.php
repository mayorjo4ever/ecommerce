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
        // Check if user is authenticated as admin
        if (!Auth::guard('admin')->check()) {
            // If not authenticated and not already on login page, redirect to login
            if (!$request->is('admin/login')) {
                return redirect()->route('admin.login');
            }
        }

        // If authenticated but trying to access login page, redirect to dashboard
        if (Auth::guard('admin')->check() && $request->is('admin/login')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
