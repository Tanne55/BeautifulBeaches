<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()?->role !== 'admin') {
            if (Auth::user()?->role === 'ceo') {
                return redirect('/ceo/dashboard');
            } elseif (Auth::user()?->role === 'user') {
                return redirect('/user/dashboard');
            }
            return response()->view('403page');
        }
        return $next($request);
    }
}
