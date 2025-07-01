<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourBooking;
use Illuminate\Support\Facades\Auth;

class TourBookingController extends Controller
{
    public function showBookingForm($id)
    {
        $tour = Tour::findOrFail($id);
        $totalBooked = TourBooking::where('tour_id', $tour->id)->sum('number_of_people');
        return view('user.tour_booking', compact('tour', 'totalBooked'));
    }

    public function storeBooking(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'number_of_people' => 'required|integer|min:1|max:20',
            'note' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra tổng số người đã đặt
        $totalBooked = TourBooking::where('tour_id', $tour->id)->sum('number_of_people');
        if ($totalBooked + $validated['number_of_people'] > $tour->capacity) {
            return back()->with('error', 'Số lượng vé còn lại không đủ!')->withInput();
        }

        $booking = TourBooking::create([
            'user_id' => Auth::id(),
            'tour_id' => $tour->id,
            'full_name' => $validated['full_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'number_of_people' => $validated['number_of_people'],
            'booking_date' => now(),
            'status' => 'pending',
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('user.history')->with('success', 'Đặt tour thành công! Vui lòng chờ CEO xác nhận.');
    }
}
