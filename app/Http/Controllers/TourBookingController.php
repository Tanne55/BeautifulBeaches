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
        return view('user.tour_booking', compact('tour'));
    }

    public function storeBooking(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'note' => 'nullable|string|max:1000',
        ]);
        $booked = TourBooking::where('tour_id', $tour->id)->count();
        if ($booked >= $tour->capacity) {
            return back()->with('error', 'Số lượng vé đã hết!')->withInput();
        }
        $booking = TourBooking::create([
            'user_id' => Auth::id(),
            'tour_id' => $tour->id,
            'full_name' => $validated['full_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'booking_date' => now(),
            'status' => 'pending',
            'note' => $validated['note'] ?? null,
        ]);
        $booking->save();
        return redirect()->route('user.history')->with('success', 'Đặt tour thành công! Vui lòng chờ CEO xác nhận.');
    }
}
