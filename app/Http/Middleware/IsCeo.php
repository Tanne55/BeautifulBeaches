<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsCeo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()?->role !== 'ceo') {
            if (Auth::user()?->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif (Auth::user()?->role === 'user') {
                return redirect('/user/dashboard');
            }
            abort(403, 'Bạn không có quyền truy cập.');
        }
        return $next($request);
    }
}
