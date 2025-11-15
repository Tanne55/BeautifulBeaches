<?php

namespace App\Http\Controllers;

use App\Models\Beach;
use App\Models\Tour;

class TestController extends Controller
{
    public function index()
    {
        $beaches = Beach::with([
            'images' => function ($query) {
                $query->ordered();
            }
        ])->get();

        $tours = Tour::with([
            'images' => function ($query) {
                $query->ordered();
            }
        ])->get();

        return view('test', compact('beaches', 'tours'));
    }
}


