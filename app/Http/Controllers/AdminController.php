<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected function getUsersData()
    {
        return User::all()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'email_verified_at' => $user->email_verified_at,
                'is_banned' => $user->is_banned ?? false,
            ];
        });
    }
    public function dashboard()
    {
        // Trả về view dashboard cho admin
        return view('admin.dashboard');
    }

    // Hiển thị danh sách users cho admin
    public function index()
    {
        $users = $this->getUsersData();
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form thêm mới user
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu user mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,ceo,user',
        ]);

        // Không cho phép tạo user với role là admin
        if ($validated['role'] === 'admin') {
            return redirect()->route('admin.users.create')->with('error', 'Bạn không có quyền tạo tài khoản admin!');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    // Hiển thị form sửa user
    public function edit($id)
    {
        $users = $this->getUsersData();
        $user = $users->firstWhere('id', $id);
        if (Auth::id() && $user->role === "admin") {
            return redirect()->route('admin.users.index')->with('error', 'Không thể sửa tài khoản của admin khác!');
        }
        if (!$user) {
            abort(404);
        }
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,ceo,user',
        ]);
        // Không cho phép chỉnh user thường lên thành admin
        if ($user->role !== 'admin' && $validated['role'] === 'admin') {
            return redirect()->route('admin.users.edit', $id)->with('error', 'Bạn không có quyền nâng cấp tài khoản thành admin!');
        }
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];
        if (!empty($validated['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        $user->update($updateData);
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    // Xóa user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (Auth::id() && $user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa tài khoản của chính mình!');
        }
        if (Auth::id() && $user->role === "admin") {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa tài khoản của admin khác!');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công!');
    }

    // Ban/Unban user
    public function ban($id)
    {
        $user = User::findOrFail($id);
        if (Auth::id() && $user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể ban tài khoản của chính mình!');
        }
        if (Auth::id() && $user->role === "admin") {
            return redirect()->route('admin.users.index')->with('error', 'Không thể ban tài khoản của admin khác!');
        }
        $user->update(['is_banned' => !$user->is_banned]);
        $status = $user->is_banned ? 'ban' : 'mở ban';
        return redirect()->route('admin.users.index')->with('success', "Đã {$status} người dùng {$user->name} thành công!");
    }
} 