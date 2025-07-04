<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            ->whereHas('tour', function ($q) use ($ceoId) {
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

    public function reports(Request $request)
    {
        $ceoId = Auth::id();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Lấy filter từ request
        $from = $request->input('from');
        $to = $request->input('to');
        $tour = $request->input('tour');
        $people = $request->input('people');

        $bookingsQuery = \App\Models\TourBooking::with('tour')
            ->whereHas('tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);

        if ($from) {
            $bookingsQuery->whereDate('booking_date', '>=', $from);
        }
        if ($to) {
            $bookingsQuery->whereDate('booking_date', '<=', $to);
        }
        if ($tour) {
            $bookingsQuery->whereHas('tour', function ($q) use ($tour) {
                $q->where('title', 'like', '%' . $tour . '%');
            });
        }
        if ($people === '>=4') {
            $bookingsQuery->where('number_of_people', '>=', 4);
        } elseif ($people === '<4') {
            $bookingsQuery->where('number_of_people', '<', 4);
        }

        $bookings = $bookingsQuery->orderByDesc('booking_date')->paginate(10)->withQueryString();

        $lastMonth = $now->copy()->subMonth();
        $startOfLastMonth = $lastMonth->copy()->startOfMonth();
        $endOfLastMonth = $lastMonth->copy()->endOfMonth();

        // Vé KHÔNG bị hủy trong tháng này, chỉ lấy tour của CEO hiện tại
        $tickets = \App\Models\Ticket::where('status', '!=', 'canceled')
            ->whereHas('tourBooking', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);
            })
            ->whereHas('tourBooking.tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->with(['tourBooking.tour'])
            ->get();

        // Tổng doanh thu tháng này
        $totalRevenue = $tickets->sum(function ($ticket) {
            return $ticket->tourBooking->tour->price ?? 0;
        });

        // Tổng số vé (giao dịch) tháng này
        $totalTickets = $tickets->count();

        // ARPU
        $arpu = $totalTickets > 0 ? $totalRevenue / $totalTickets : 0;

        // Vé KHÔNG bị hủy trong tháng trước
        $lastMonthTickets = \App\Models\Ticket::where('status', '!=', 'canceled')
            ->whereHas('tourBooking', function ($q) use ($startOfLastMonth, $endOfLastMonth) {
                $q->whereBetween('booking_date', [$startOfLastMonth, $endOfLastMonth]);
            })
            ->whereHas('tourBooking.tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->with(['tourBooking.tour'])
            ->get();

        $lastMonthRevenue = $lastMonthTickets->sum(function ($ticket) {
            return $ticket->tourBooking->tour->price ?? 0;
        });

        // Tăng trưởng
        $growthPercent = $lastMonthRevenue > 0
            ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : null;

        // Doanh thu theo từng tour (chỉ tính vé không bị hủy)
        $tourRevenue = \App\Models\Ticket::where('status', '!=', 'canceled')
            ->whereHas('tourBooking.tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->whereHas('tourBooking', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);
            })
            ->with(['tourBooking.tour'])
            ->get()
            ->groupBy(function($ticket) {
                return $ticket->tourBooking->tour->title ?? 'N/A';
            })
            ->map(function($tickets) {
                return $tickets->sum(function($ticket) {
                    return $ticket->tourBooking->tour->price ?? 0;
                });
            });

        $tourNames = $tourRevenue->keys()->toArray();
        $tourRevenueData = $tourRevenue->values()->toArray();

        // Số booking đã xác nhận trong tháng này
        $totalBookings = $bookings->count();

        // Số booking đã xác nhận trong tháng trước
        $lastMonthBookings = \App\Models\TourBooking::with('tour')
            ->whereHas('tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->where('status','!=', 'cancelled')
            ->whereBetween('booking_date', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Tăng trưởng số booking
        $growthPercent = $lastMonthBookings > 0
            ? (($totalBookings - $lastMonthBookings) / $lastMonthBookings) * 100
            : null;

            // Log::info('Total bookings this month: ' . $totalBookings);
            // Log::info('Total bookings last month: ' . $lastMonthBookings);
        return view('ceo.reports.index', compact(
            'tickets',
            'totalRevenue',
            'totalTickets',
            'arpu',
            'growthPercent',
            'now',
            'bookings',
            'tourNames',
            'tourRevenueData',
            'totalBookings',
            'lastMonthBookings',
        ));
    }

    public function confirmBooking(Request $request, $bookingId)
    {
        $booking = \App\Models\TourBooking::with(['tour', 'tickets'])->findOrFail($bookingId);
        if ($booking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking đã được xử lý!');
        }
        // Sinh vé nếu chưa có vé nào
        if ($booking->tickets->count() === 0) {
            for ($i = 0; $i < $booking->number_of_people; $i++) {
                do {
                    $code = 'TIX-' . strtoupper(\Illuminate\Support\Str::random(8));
                } while (\App\Models\Ticket::where('ticket_code', $code)->exists());
                \App\Models\Ticket::create([
                    'tour_booking_id' => $booking->id,
                    'ticket_code' => $code,
                    'full_name' => $booking->full_name,
                    'email' => $booking->contact_email,
                    'phone' => $booking->contact_phone,
                    'status' => 'valid',
                ]);
            }
        }
        $booking->status = 'confirmed';
        $booking->save();
        return redirect()->route('ceo.bookings.index')->with('success', 'Đã xác nhận và sinh vé cho booking!');
    }
}
