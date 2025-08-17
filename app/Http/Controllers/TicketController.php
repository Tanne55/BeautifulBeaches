<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Lấy giá đơn vị cho mỗi vé dựa trên tour và giá hiện tại
     */
    private function getTicketUnitPrice($booking)
    {
        $tour = $booking->tour;
        if (!$tour) {
            return 0;
        }
        
        // Lấy giá hiện tại
        $today = now()->format('Y-m-d');
        $price = $tour->prices()->where('start_date', '<=', $today)
                             ->where('end_date', '>=', $today)
                             ->first();
                             
        if (!$price) {
            $price = $tour->prices()->first();
        }
        
        return $price ? ($price->discount && $price->discount > 0 ? $price->final_price : $price->price) : ($tour->price ?? 0);
    }
    
    /**
     * Cập nhật tổng số tiền booking dựa trên trạng thái của vé
     */
    private function updateBookingTotalAmount($bookingId)
    {
        $booking = TourBooking::with(['tickets'])->findOrFail($bookingId);
        $validTickets = $booking->tickets->whereIn('status', ['valid', 'used']); // Chỉ tính vé hợp lệ và đã sử dụng
        
        // Tính tổng số tiền từ các vé hợp lệ
        $totalAmount = 0;
        foreach ($validTickets as $ticket) {
            $totalAmount += $ticket->unit_price ?? 0;
        }
        
        // Cập nhật total_amount cho booking
        $booking->total_amount = $totalAmount;
        $booking->save();
        
        return $totalAmount;
    }
    
    public function index()
    {
        $ceoId = Auth::id();
        $bookingsQuery = TourBooking::whereHas('tour', function ($query) use ($ceoId) {
            $query->where('ceo_id', $ceoId);
        })
        ->with(['tour', 'tickets']);

        // Lọc theo tháng booking_date nếu có request
        if (request()->filled('month')) {
            $month = request('month');
            $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $bookingsQuery->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);
        }

        // Lọc theo tên tour
        if (request()->filled('tour')) {
            $bookingsQuery->whereHas('tour', function ($q) {
                $q->where('title', 'like', '%' . request('tour') . '%');
            });
        }
        // Lọc theo tên người đặt
        if (request()->filled('user')) {
            $bookingsQuery->where('full_name', 'like', '%' . request('user') . '%');
        }
        // Lọc theo email
        if (request()->filled('email')) {
            $bookingsQuery->where('contact_email', 'like', '%' . request('email') . '%');
        }
        // Lọc theo trạng thái booking
        if (request()->filled('status')) {
            $bookingsQuery->where('status', request('status'));
        }

        $bookings = $bookingsQuery->orderByDesc('booking_date')->paginate(15);
        return view('ceo.tickets.index', compact('bookings'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['tourBooking.tour', 'tourBooking.user']);
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        return view('ceo.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $ticket->load(['tourBooking.tour', 'tourBooking.user']);
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        return view('ceo.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $ticket->load(['tourBooking.tour']);
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        $data = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:valid,used,cancelled',
        ]);
        
        // Lưu trạng thái cũ để kiểm tra xem có thay đổi không
        $oldStatus = $ticket->status;
        $ticket->update($data);
        
        // Nếu có thay đổi trạng thái, cập nhật total_amount của booking
        if ($oldStatus !== $data['status']) {
            $this->updateBookingTotalAmount($ticket->tour_booking_id);
        }
        
        return redirect()->route('ceo.bookings.index', ['open_booking' => $ticket->tour_booking_id])->with('success', 'Cập nhật vé thành công!');
    }

    public function generateTickets(TourBooking $tourBooking)
    {
        $ceoId = Auth::id();
        if ($tourBooking->tour->ceo_id !== $ceoId) {
            abort(403);
        }
        $numberOfPeople = $tourBooking->number_of_people;
        
        // Tính unit_price cho vé
        $unitPrice = $this->getTicketUnitPrice($tourBooking);
        
        for ($i = 0; $i < $numberOfPeople; $i++) {
            Ticket::create([
                'tour_booking_id' => $tourBooking->id,
                'ticket_code' => 'TIX-' . strtoupper(Str::random(8)),
                'full_name' => $tourBooking->full_name,
                'email' => $tourBooking->contact_email,
                'phone' => $tourBooking->contact_phone,
                'status' => 'valid',
                'unit_price' => $unitPrice,
            ]);
        }
        
        // Cập nhật tổng số tiền booking
        $this->updateBookingTotalAmount($tourBooking->id);

        return redirect()->route('ceo.bookings.index', ['open_booking' => $tourBooking->id])->with('success', 'Tickets generated successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $ticket->load(['tourBooking.tour']);
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'status' => 'required|in:valid,used,cancelled',
        ]);

        // Lưu trạng thái cũ
        $oldStatus = $ticket->status;
        $ticket->update(['status' => $request->status]);
        
        // Nếu có thay đổi trạng thái, cập nhật total_amount của booking
        if ($oldStatus !== $request->status) {
            $this->updateBookingTotalAmount($ticket->tour_booking_id);
        }

        return redirect()->route('ceo.bookings.index', ['open_booking' => $ticket->tour_booking_id])->with('success', 'Ticket status updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->load(['tourBooking.tour']);
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        $bookingId = $ticket->tour_booking_id;
        $ticket->delete();
        return redirect()->route('ceo.bookings.index', ['open_booking' => $bookingId])->with('success', 'Ticket deleted successfully!');
    }
} 