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
            'user_id' => 'nullable|exists:users,id',
            'tour_id' => 'required|exists:tours,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
        ]);
        
        // Ensure user_id is set to null if not provided
        $data['user_id'] = $data['user_id'] ?? null;
        
        // Nếu không có user_id (khách), yêu cầu thông tin khách
        if (!$data['user_id']) {
            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
            ]);
        }
        
        ReviewTour::create($data);
        return redirect()->route('tour.show', $data['tour_id'])->with('success', 'Bình luận của bạn đã được gửi!');
    }

    public function update(Request $request, $id)
    {
        $review = ReviewTour::findOrFail($id);
        $review->update($request->all());
        return $review;
    }

    public function destroy($id)
    {
        $review = ReviewTour::findOrFail($id);
        $review->delete();
        return response()->noContent();
    }
} 