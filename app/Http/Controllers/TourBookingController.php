<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TourBookingController extends Controller
{
    protected BookingService $bookingService;
    
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function showBookingForm(Request $request, $id)
    {
        $tour = Tour::with('detail', 'prices')->findOrFail($id);
        $totalBooked = TourBooking::where('tour_id', $tour->id)->sum('number_of_people');
        
        return view('user.tour_booking', compact('tour', 'totalBooked'));
    }

    public function storeBooking(Request $request, $id)
    {
        $tour = Tour::with('detail', 'prices')->findOrFail($id);
        
        // Validation - tách riêng booking_date và selected_departure_date
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|size:10|regex:/^[0-9]+$/',
            'number_of_people' => 'required|integer|min:1|max:20',
            'selected_departure_date' => 'required|date|after:today',
            'note' => 'nullable|string|max:1000',
        ]);

        try {
            // Sử dụng BookingService để tạo booking
            $booking = $this->bookingService->createBooking([
                'user_id' => Auth::check() ? Auth::id() : null,
                'tour_id' => $tour->id,
                'full_name' => $validated['full_name'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'number_of_people' => $validated['number_of_people'],
                'selected_departure_date' => $validated['selected_departure_date'],
                'note' => $validated['note'] ?? null,
            ]);

            $successMessage = 'Đặt tour thành công! Mã đặt tour của bạn là: ' . $booking->booking_code . '. ' . 
                ($booking->contact_email ? 'Chúng tôi sẽ gửi email xác nhận đến ' . $booking->contact_email : '') .
                '. Vui lòng chờ xác nhận từ chúng tôi. Bạn có thể tra cứu trạng thái đặt tour bằng mã này.';

            // Lưu booking code trong session để dễ truy xuất
            session()->flash('booking_code', $booking->booking_code);
            session()->flash('booking_id', $booking->id);

            if (Auth::check()) {
                return redirect()->route('user.history')->with('success', $successMessage);
            } else {
                return redirect()->back()->with('success', $successMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
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
        $booking = TourBooking::with(['tour', 'tour.detail', 'tickets'])->where('booking_code', $bookingCode)->first();
        
        if (!$booking) {
            return view('bookings.result')
                ->with('error', 'Mã đặt tour không hợp lệ. Vui lòng kiểm tra lại.');
        }
        
        return view('bookings.result', compact('booking'));
    }

    /**
     * API to get tickets for a booking (for guest access)
     */
    public function getBookingTickets(Request $request)
    {
        $bookingCode = $request->input('booking_code');
        
        if (!$bookingCode) {
            return response()->json(['error' => 'Booking code is required'], 400);
        }
        
        $booking = TourBooking::with('tickets')->where('booking_code', $bookingCode)->first();
        
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        
        return response()->json([
            'success' => true,
            'tickets' => $booking->tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'full_name' => $ticket->full_name,
                    'email' => $ticket->email,
                    'phone' => $ticket->phone,
                    'status' => $ticket->status,
                    'unit_price' => $ticket->unit_price,
                    'status_text' => $this->getTicketStatusText($ticket->status),
                    'status_class' => $this->getTicketStatusClass($ticket->status),
                    'created_at' => $ticket->created_at->format('d/m/Y H:i')
                ];
            })
        ]);
    }
    
    private function getTicketStatusText($status)
    {
        switch ($status) {
            case 'valid':
                return 'Hợp lệ';
            case 'used':
                return 'Đã sử dụng';
            case 'cancelled':
                return 'Đã hủy';
            default:
                return ucfirst($status);
        }
    }
    
    private function getTicketStatusClass($status)
    {
        switch ($status) {
            case 'valid':
                return 'success';
            case 'used':
                return 'warning';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }
    
    /**
     * API để kiểm tra availability của tour cho ngày cụ thể
     */
    public function checkAvailability(Request $request, $tourId)
    {
        $tour = Tour::findOrFail($tourId);
        $date = $request->get('date');
        $numberOfPeople = $request->get('people', 1);
        
        if (!$date) {
            return response()->json(['error' => 'Thiếu thông tin ngày khởi hành'], 400);
        }
        
        try {
            $availability = $this->bookingService->checkAvailability($tour, $date, $numberOfPeople);
            $pricing = $this->bookingService->calculatePrice($tour, $date, $numberOfPeople);
            
            return response()->json([
                'success' => true,
                'availability' => $availability,
                'pricing' => $pricing
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
