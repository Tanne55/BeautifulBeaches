<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Hàm xử lý dữ liệu user, dùng cho view
    

    public function dashboard()
    {
        // Trả về view profile cho user
        return view('user.dashboard');
    }

    public function history()
    {
        $bookings = \App\Models\TourBooking::with('tour')
            ->where('user_id',Auth::id())
            ->orderByDesc('created_at')
            ->get();
        return view('user.history.index', compact('bookings'));
    }
} 