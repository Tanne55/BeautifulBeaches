<?php

namespace App\Http\Controllers;

use App\Models\Beach;
use App\Models\Tour;

class HomeController extends Controller
{
    public function index()
    {
        $beaches = Beach::with(['detail', 'region']) // nạp quan hệ 1-1 và region
            ->select('id', 'region_id', 'image', 'title') // chỉ lấy cột cần thiết
            ->take(4)
            ->get()
            ->map(function ($beach) {
                return [
                    'id' => $beach->id,
                    'region' => $beach->region_name,
                    'image' => $beach->image,
                    'title' => $beach->title,
                    'short_description' => $beach->short_description,
                ];
            });

        // Lấy dữ liệu tours với prices
        $tours = Tour::with(['beach', 'prices', 'detail'])
            ->where('status', 'confirmed')
            ->take(3)
            ->get()
            ->map(function ($tour) {
                return [
                    'id' => $tour->id,
                    'title' => $tour->title,
                    'image' => $tour->image,
                    'beach_name' => $tour->beach ? $tour->beach->title : 'N/A',
                    'beach_region' => $tour->beach && $tour->beach->region ? $tour->beach->region->name : 'N/A',
                    'duration_days' => $tour->duration_days,
                    'max_people' => $tour->max_people ?? $tour->capacity ?? 'N/A',
                    'current_price' => $tour->current_price,
                    'average_rating' => $tour->average_rating,
                    'total_reviews' => $tour->total_reviews,
                    'short_description' => $tour->beach && $tour->beach->short_description ? $tour->beach->short_description : null,
                ];
            });

        return view('welcome', compact('beaches', 'tours'));
    }
}

