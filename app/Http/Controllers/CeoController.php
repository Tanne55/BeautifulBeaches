<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CeoController extends Controller
{
    public function dashboard()
    {
        // Trả về view dashboard cho CEO
        return view('ceo.dashboard');
    }
} 