<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Trả về view dashboard cho admin
        return view('admin.dashboard');
    }
} 