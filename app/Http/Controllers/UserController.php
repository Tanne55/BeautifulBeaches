<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Models\UserBan;
use App\Models\TourBooking;

class UserController extends Controller
{
    // Hàm xử lý dữ liệu user, dùng cho view
    

    public function dashboard()
    {
        // Lấy thông tin người dùng hiện tại và profile của họ
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        
        // Lấy số lượng booking của user này
        $bookingCount = TourBooking::where('user_id', $user->id)->count();
        
        return view('user.dashboard', compact('user', 'profile', 'bookingCount'));
    }

    public function history()
    {
        $bookings = TourBooking::with('tour')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        // Tổng tiền các booking đang ở trạng thái 'pending' của user
        $pendingTotalAmount = $bookings->where('status', 'pending')->sum('total_amount');

        return view('user.history.index', compact('bookings', 'pendingTotalAmount'));
    }
} 