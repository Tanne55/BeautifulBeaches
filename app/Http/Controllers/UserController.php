<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        // Trả về view profile cho user
        return view('user.dashboard');
    }
} 