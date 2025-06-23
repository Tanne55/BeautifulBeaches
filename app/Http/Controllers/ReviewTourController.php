<?php

namespace App\Http\Controllers;

use App\Models\ReviewTour;
use Illuminate\Http\Request;

class ReviewTourController extends Controller
{
    public function index()
    {
        return ReviewTour::all();
    }

    public function show($id)
    {
        return ReviewTour::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tour_id' => 'required|exists:tours,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
        ]);
        return ReviewTour::create($data);
    }

    public function update(Request $request, $id)
    {
        $review = ReviewTour::findOrFail($id);
        $review->update($request->all());
        return $review;
    }

    public function destroy($id)
    {
        ReviewTour::destroy($id);
        return response()->noContent();
    }
} 