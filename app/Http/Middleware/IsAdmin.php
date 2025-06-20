<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
