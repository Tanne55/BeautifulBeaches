<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourBooking;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Tính giá đơn vị cho mỗi vé dựa trên giá tour đã qua discount
     */
    private function calculateUnitPrice($booking)
    {
        $tour = $booking->tour;
        if (!$tour) {
            return 500000; // Default price
        }
        
        // Lấy giá từ bảng prices dựa trên departure date
        $departureDate = $booking->selected_departure_date ? 
            $booking->selected_departure_date->format('Y-m-d') : 
            now()->format('Y-m-d');
            
        if ($tour->prices && $tour->prices->count() > 0) {
            // Tìm price record phù hợp với departure date
            $priceRecord = $tour->prices()
                ->where('start_date', '<=', $departureDate)
                ->where('end_date', '>=', $departureDate)
                ->orderByDesc('start_date')
                ->first();
                
            // Nếu không tìm thấy trong range, lấy price gần nhất
            if (!$priceRecord) {
                $priceRecord = $tour->prices()->orderByDesc('created_at')->first();
            }
            
            if ($priceRecord) {
                // Sử dụng final_price accessor hoặc tính toán discount
                if ($priceRecord->discount && $priceRecord->discount > 0) {
                    // Tính giá sau discount: price - (price * discount / 100)
                    return $priceRecord->price - ($priceRecord->price * $priceRecord->discount / 100);
                } else {
                    // Không có discount, trả về giá gốc
                    return $priceRecord->price;
                }
            }
        }
        
        // Fallback về tour price nếu không có price records
        return $tour->price ?? 500000;
    }
    
    public function run()
    {
        // Chỉ tạo vé cho booking có status 'confirmed' hoặc 'grouped'
        $bookings = TourBooking::with('tour.prices')
            ->whereIn('status', ['confirmed', 'grouped'])
            ->get();
            
        foreach ($bookings as $booking) {
            // Skip nếu đã có vé
            if ($booking->tickets()->count() > 0) {
                continue;
            }
            
            // Tính toán giá vé đơn vị
            $unitPrice = $this->calculateUnitPrice($booking);
            
            for ($i = 0; $i < $booking->number_of_people; $i++) {
                do {
                    $code = 'TIX-' . strtoupper(Str::random(8));
                } while (Ticket::where('ticket_code', $code)->exists());
                
                // Tạo status ngẫu nhiên: chủ yếu là used, một ít valid
                $statuses = ['used', 'used', 'used', 'valid', 'valid'];
                $status = $statuses[array_rand($statuses)];
                
                // Nếu departure date chưa tới thì không thể là used
                if ($booking->selected_departure_date && Carbon::parse($booking->selected_departure_date)->isFuture()) {
                    $status = 'valid';
                }
                
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
        
        $this->command->info('Ticket seeder completed successfully!');
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