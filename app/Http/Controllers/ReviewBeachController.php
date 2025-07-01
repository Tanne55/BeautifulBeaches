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
            'user_id' => 'nullable|exists:users,id',
            'beach_id' => 'required|exists:beaches,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
        ]);
        
        // Nếu không có user_id (khách), yêu cầu thông tin khách
        if (!$data['user_id']) {
            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
            ]);
        }
        
        ReviewBeach::create($data);
        return redirect()->route('beaches.show', $data['beach_id'])->with('success', 'Bình luận của bạn đã được gửi!');
    }

    public function update(Request $request, $id)
    {
        $review = ReviewBeach::findOrFail($id);
        $review->update($request->all());
        return $review;
    }

    public function destroy($id)
    {
        $review = ReviewBeach::findOrFail($id);
        $review->delete();
        return response()->noContent();
    }
} 