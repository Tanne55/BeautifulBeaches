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
        $tour = Tour::with('detail', 'prices')->findOrFail($id);
        $totalBooked = TourBooking::where('tour_id', $tour->id)->sum('number_of_people');
        return view('user.tour_booking', compact('tour', 'totalBooked'));
    }

    public function storeBooking(Request $request, $id)
    {
        $tour = Tour::with('detail', 'prices')->findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|size:10|regex:/^[0-9]+$/',
            'number_of_people' => 'required|integer|min:1|max:20',
            'note' => 'nullable|string|max:1000',
        ]);

        // Lấy giá hiện tại
        $today = now()->format('Y-m-d');
        $price = $tour->prices->where('start_date', '<=', $today)
                             ->where('end_date', '>=', $today)
                             ->first();
        if (!$price) {
            $price = $tour->prices->first();
        }
        $unitPrice = $price ? ($price->discount && $price->discount > 0 ? $price->final_price : $price->price) : ($tour->price ?? 0);
        $totalAmount = $unitPrice * $validated['number_of_people'];

        // Kiểm tra tour đã khởi hành chưa
        if ($tour->detail && $tour->detail->departure_time && now()->gt($tour->detail->departure_time)) {
            return back()->with('error', 'Không thể đặt tour đã qua thời gian khởi hành!')->withInput();
        }

        // Tạo booking code
        $bookingCode = 'BK' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        
        $booking = TourBooking::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'tour_id' => $tour->id,
            'full_name' => $validated['full_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'number_of_people' => $validated['number_of_people'],
            'booking_date' => now(),
            'status' => 'pending',
            'note' => $validated['note'] ?? null,
            'total_amount' => $totalAmount,
            'booking_code' => $bookingCode,
        ]);

        $successMessage = 'Đặt tour thành công! Mã đặt tour của bạn là: ' . $bookingCode . '. ' . 
            ($booking->contact_email ? 'Chúng tôi sẽ gửi email xác nhận đến ' . $booking->contact_email : '') .
            '. Vui lòng chờ xác nhận từ chúng tôi. Bạn có thể tra cứu trạng thái đặt tour bằng mã này.';

        // Lưu booking code trong session để dễ truy xuất
        session()->flash('booking_code', $bookingCode);
        session()->flash('booking_id', $booking->id);

        if (Auth::check()) {
            return redirect()->route('user.history')->with('success', $successMessage);
        } else {
            return redirect()->back()->with('success', $successMessage);
        }
    }

    /**
     * Track booking by code
     */
    public function trackBooking(Request $request)
    {
        if (!$request->has('booking_code')) {
            return redirect()->route('home');
        }
        
        $bookingCode = $request->input('booking_code');
        $booking = TourBooking::with('tour')->where('booking_code', $bookingCode)->first();
        
        if (!$booking) {
            return redirect()->route('bookings.result')
                ->with('error', 'Mã đặt tour không hợp lệ. Vui lòng kiểm tra lại.');
        }
        
        return redirect()->route('bookings.result', ['booking_code' => $bookingCode]);
    }
    
    /**
     * Show booking result
     */
    public function showBookingResult(Request $request)
    {
        if (!$request->has('booking_code')) {
            return view('bookings.result', ['booking' => null]);
        }
        
        $bookingCode = $request->input('booking_code');
        $booking = TourBooking::with(['tour', 'tour.detail'])->where('booking_code', $bookingCode)->first();
        
        if (!$booking) {
            return view('bookings.result')
                ->with('error', 'Mã đặt tour không hợp lệ. Vui lòng kiểm tra lại.');
        }
        
        return view('bookings.result', compact('booking'));
    }
}
