<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Models\UserBan;
use App\Models\TourBooking;
use App\Models\Beach;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function history(Request $request)
    {
        $query = TourBooking::with('tour')
            ->where('user_id', Auth::id());

        // Filter theo tên tour
        if ($request->filled('search')) {
            $query->whereHas('tour', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        // Filter theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter theo ngày từ
        if ($request->filled('date_from')) {
            $query->where('booking_date', '>=', $request->date_from);
        }

        $bookings = $query->orderByDesc('created_at')->paginate(10);

        // Tổng tiền các booking đang ở trạng thái 'confirmed' của user
        $pendingTotalAmount = $bookings->where('status', 'pending')->sum('total_amount');

        return view('user.history.index', compact('bookings', 'pendingTotalAmount'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        // Nếu profile không tồn tại, tạo mới
        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->save();
        }

        // Lấy danh sách các bãi biển cho tùy chọn bãi biển yêu thích
        $beaches = Beach::select('id', 'title')->get();

        return view('user.profile.edit', compact('user', 'profile', 'beaches'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favorite_beaches' => 'nullable|array',
            // Theme validation removed temporarily
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('user.profile.edit')
                ->withErrors($validator)
                ->withInput();
        }

        // Cập nhật thông tin User
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Xử lý upload avatar nếu có
        if ($request->hasFile('avatar')) {
            // Đảm bảo thư mục avatars tồn tại
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }

            // Xóa avatar cũ nếu có và nếu nó nằm trong thư mục storage/avatars
            // (chỉ xóa avatar do user upload trước đó, không xóa avatar được seed)
            if ($user->avatar && strpos($user->avatar, 'avatars/') !== false && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu avatar mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        // Cập nhật hoặc tạo UserProfile
        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
        }

        $profile->dob = $request->dob;
        $profile->nationality = $request->nationality;

        // Cập nhật tùy chọn
        $preferences = $profile->preferences ?? [];
        if ($request->has('favorite_beaches')) {
            $preferences['favorite_beaches'] = $request->favorite_beaches;
        }
        // Theme handling temporarily removed

        $profile->preferences = $preferences;
        $profile->save();

        return redirect()->route('user.dashboard')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }
}