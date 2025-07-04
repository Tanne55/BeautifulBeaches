<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourBooking;
use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    public function run()
    {
        $statuses = ['valid', 'used', 'cancelled'];
        $bookings = TourBooking::all();
        foreach ($bookings as $booking) {
            for ($i = 0; $i < $booking->number_of_people; $i++) {
                Ticket::create([
                    'tour_booking_id' => $booking->id,
                    'ticket_code' => 'TIX-' . strtoupper(Str::random(8)),
                    'full_name' => $booking->full_name,
                    'email' => $booking->contact_email,
                    'phone' => $booking->contact_phone,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
} 