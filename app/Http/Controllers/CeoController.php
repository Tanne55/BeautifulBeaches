<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CeoController extends Controller
{
    public function dashboard()
    {
        // Trả về view dashboard cho CEO
        return view('ceo.dashboard');
    }

    public function bookings()
    {
        $ceoId = Auth::id();
        $bookings = \App\Models\TourBooking::with(['tour'])
            ->whereHas('tour', function($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->orderByDesc('created_at')
            ->get();
        return view('ceo.bookings.index', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, $bookingId)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        $booking = \App\Models\TourBooking::with('tour')->findOrFail($bookingId);
        if ($booking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        $booking->status = $request->status;
        $booking->save();
        return redirect()->route('ceo.bookings.index')->with('success', 'Cập nhật trạng thái booking thành công!');
    }

    public function reports()
    {
        $ceoId = Auth::id();
        $now = now();
        $bookings = \App\Models\TourBooking::with('tour')
            ->whereHas('tour', function($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->where('status', 'confirmed')
            ->whereMonth('booking_date', $now->month)
            ->whereYear('booking_date', $now->year)
            ->get();
        $totalRevenue = $bookings->sum(function($b) { return $b->tour->price ?? 0; });
        return view('ceo.reports.index', compact('bookings', 'totalRevenue', 'now'));
    }
} 