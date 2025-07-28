<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckUserBanned
{
    /**
     * Kiểm tra nếu người dùng đang bị ban
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Kiểm tra trực tiếp xem user có bị ban không
            $user = Auth::user();
            $userId = $user->id;
            $now = now();
            $isBanned = \App\Models\UserBan::where('user_id', $userId)
                ->where('start_date', '<=', $now)
                ->where(function($q) use ($now) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->exists();
                
            if ($isBanned) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ Admin để được hỗ trợ.');
            }
        }

        return $next($request);
    }
}
