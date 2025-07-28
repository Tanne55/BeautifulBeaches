<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCeo;
use App\Http\Middleware\IsUser;

class LoginController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Kiểm tra xem user có đang bị ban không
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
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ Admin để được hỗ trợ');
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
