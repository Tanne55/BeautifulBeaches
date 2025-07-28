<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourBooking;
use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Tính giá đơn vị cho mỗi vé dựa trên giá tour
     */
    private function calculateUnitPrice($booking)
    {
        $tour = $booking->tour;
        if (!$tour) {
            return 0;
        }
        
        // Lấy giá từ tour hoặc từ bảng giá
        if ($tour->prices && $tour->prices->count() > 0) {
            $price = $tour->prices->first();
            return $price->discount && $price->discount > 0 ? $price->final_price : $price->price;
        }
        
        return $tour->price ?? 0;
    }
    
    public function run()
    {
        $bookings = TourBooking::with('tour.prices')->get();
        foreach ($bookings as $booking) {
            // Tính toán giá vé đơn vị
            $unitPrice = $this->calculateUnitPrice($booking);
            
            for ($i = 0; $i < $booking->number_of_people; $i++) {
                do {
                    $code = 'TIX-' . strtoupper(Str::random(8));
                } while (Ticket::where('ticket_code', $code)->exists());
                // Đa số vé là used, một số valid/cancelled
                $status = ($i < $booking->number_of_people - 1) ? 'used' : (rand(0,1) ? 'valid' : 'cancelled');
                Ticket::create([
                    'tour_booking_id' => $booking->id,
                    'ticket_code' => $code,
                    'full_name' => $booking->full_name,
                    'email' => $booking->contact_email,
                    'phone' => $booking->contact_phone,
                    'status' => $status,
                    'unit_price' => $unitPrice,
                ]);
            }
            
            // Cập nhật total_amount dựa trên vé hợp lệ
            $this->updateBookingTotalAmount($booking->id);
        }
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
} 