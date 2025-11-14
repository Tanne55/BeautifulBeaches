<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotForm()
    {
        return view('forgot_password');
    }

    /**
     * Hiển thị form đổi mật khẩu sau khi xác nhận email
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('forgot.password')->with('error', 'Email không hợp lệ');
        }

        return view('reset_password', compact('email'));
    }

    /**
     * Xử lý yêu cầu quên mật khẩu
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Kiểm tra xem email có tồn tại trong hệ thống không
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email sai hoặc chưa từng đăng kí'
            ])->onlyInput('email');
        }

        // Nếu email tồn tại, chuyển hướng đến trang đặt lại mật khẩu
        return redirect()->route('password.reset.form', ['email' => $request->email])
            ->with('success', 'Email hợp lệ! Vui lòng nhập mật khẩu mới');
    }

    /**
     * Xử lý đặt lại mật khẩu
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại trong hệ thống'
            ]);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Tự động đăng nhập sau khi đổi mật khẩu
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Mật khẩu đã được đổi thành công!');
    }
}
