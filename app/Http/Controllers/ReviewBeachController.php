<?php

namespace App\Http\Controllers;

use App\Models\ReviewBeach;
use Illuminate\Http\Request;

class ReviewBeachController extends Controller
{
    public function index()
    {
        return ReviewBeach::all();
    }

    public function show($id)
    {
        return ReviewBeach::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'beach_id' => 'required|exists:beaches,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
        ]);
        return ReviewBeach::create($data);
    }

    public function update(Request $request, $id)
    {
        $review = ReviewBeach::findOrFail($id);
        $review->update($request->all());
        return $review;
    }

    public function destroy($id)
    {
        ReviewBeach::destroy($id);
        return response()->noContent();
    }
} 