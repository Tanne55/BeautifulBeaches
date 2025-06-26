<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()?->role !== 'user') {
            if (Auth::user()?->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif (Auth::user()?->role === 'ceo') {
                return redirect('/ceo/dashboard');
            }
            return response()->view('403page');
        }
        return $next($request);
    }
}
