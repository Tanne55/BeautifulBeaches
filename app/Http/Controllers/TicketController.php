<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $ceoId = Auth::id();
        $bookings = TourBooking::whereHas('tour', function ($query) use ($ceoId) {
                $query->where('ceo_id', $ceoId);
            })
            ->with(['tour', 'tickets'])
            ->orderByDesc('created_at')
            ->paginate(15);
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
        $ticket->update($data);
        return redirect()->route('ceo.tickets.index', ['open_booking' => $ticket->tour_booking_id])->with('success', 'Cập nhật vé thành công!');
    }

    public function generateTickets(TourBooking $tourBooking)
    {
        $ceoId = Auth::id();
        if ($tourBooking->tour->ceo_id !== $ceoId) {
            abort(403);
        }
        $numberOfPeople = $tourBooking->number_of_people;
        
        for ($i = 0; $i < $numberOfPeople; $i++) {
            Ticket::create([
                'tour_booking_id' => $tourBooking->id,
                'ticket_code' => 'TIX-' . strtoupper(Str::random(8)),
                'full_name' => $tourBooking->full_name,
                'email' => $tourBooking->contact_email,
                'phone' => $tourBooking->contact_phone,
                'status' => 'valid',
            ]);
        }

        return redirect()->back()->with('success', 'Tickets generated successfully!');
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

        $ticket->update(['status' => $request->status]);

        return redirect()->route('ceo.tickets.index', ['open_booking' => $ticket->tour_booking_id])->with('success', 'Ticket status updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->load(['tourBooking.tour']);
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        $bookingId = $ticket->tour_booking_id;
        $ticket->delete();
        return redirect()->route('ceo.tickets.index', ['open_booking' => $bookingId])->with('success', 'Ticket deleted successfully!');
    }
} 