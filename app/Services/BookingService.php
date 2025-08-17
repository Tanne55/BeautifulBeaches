<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\TourBooking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingService
{
    /**
     * Kiểm tra availability cho tour trong ngày cụ thể
     */
    public function checkAvailability(Tour $tour, string $date, int $requestedPeople = 1): array
    {
        // Kiểm tra ngày có trong danh sách departure_dates không
        if ($tour->detail && !empty($tour->detail->departure_dates)) {
            $availableDates = array_map(function($datetime) {
                return Carbon::parse($datetime)->format('Y-m-d');
            }, $tour->detail->departure_dates);
            
            if (!in_array($date, $availableDates)) {
                return [
                    'available' => false,
                    'message' => 'Ngày này không có tour khởi hành'
                ];
            }
        }
        
        // Tính số chỗ đã đặt
        $bookedPeople = TourBooking::where('tour_id', $tour->id)
            ->where('selected_departure_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'grouped'])
            ->sum('number_of_people');
            
        $remainingCapacity = $tour->capacity - $bookedPeople;
        
        return [
            'available' => $remainingCapacity >= $requestedPeople,
            'capacity' => $tour->capacity,
            'booked' => $bookedPeople,
            'remaining' => $remainingCapacity,
            'can_book' => $remainingCapacity >= $requestedPeople,
            'message' => $remainingCapacity >= $requestedPeople 
                ? "Còn lại {$remainingCapacity} chỗ" 
                : ($remainingCapacity > 0 ? "Chỉ còn {$remainingCapacity} chỗ, không đủ cho {$requestedPeople} người" : 'Đã hết chỗ')
        ];
    }
    
    /**
     * Tính giá tour cho ngày cụ thể
     */
    public function calculatePrice(Tour $tour, string $date, int $numberOfPeople = 1): array
    {
        $price = $tour->prices()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
            
        if (!$price) {
            $price = $tour->prices()->first();
        }
        
        if (!$price) {
            return [
                'unit_price' => 0,
                'total_amount' => 0,
                'has_discount' => false,
                'discount_percent' => 0,
                'original_price' => 0
            ];
        }
        
        $originalPrice = $price->price;
        $discountPercent = $price->discount ?? 0;
        $hasDiscount = $discountPercent > 0;
        
        $unitPrice = $hasDiscount 
            ? $originalPrice - ($originalPrice * $discountPercent / 100)
            : $originalPrice;
            
        return [
            'unit_price' => $unitPrice,
            'total_amount' => $unitPrice * $numberOfPeople,
            'has_discount' => $hasDiscount,
            'discount_percent' => $discountPercent,
            'original_price' => $originalPrice
        ];
    }
    
    /**
     * Tạo booking code duy nhất
     */
    public function generateBookingCode(): string
    {
        do {
            $bookingCode = 'BK-' . strtoupper(Str::random(8));
        } while (TourBooking::where('booking_code', $bookingCode)->exists());
        
        return $bookingCode;
    }
    
    /**
     * Tạo booking mới với transaction
     */
    public function createBooking(array $data): TourBooking
    {
        return DB::transaction(function () use ($data) {
            // Kiểm tra availability lần cuối trước khi tạo
            $tour = Tour::findOrFail($data['tour_id']);
            $availability = $this->checkAvailability(
                $tour, 
                $data['selected_departure_date'], 
                $data['number_of_people']
            );
            
            if (!$availability['can_book']) {
                throw new \Exception($availability['message']);
            }
            
            // Tính giá
            $pricing = $this->calculatePrice(
                $tour, 
                $data['selected_departure_date'], 
                $data['number_of_people']
            );
            
            // Tạo booking code
            $bookingCode = $this->generateBookingCode();
            
            // Tạo booking
            return TourBooking::create([
                'user_id' => $data['user_id'] ?? null,
                'tour_id' => $data['tour_id'],
                'booking_code' => $bookingCode,
                'full_name' => $data['full_name'],
                'contact_email' => $data['contact_email'],
                'contact_phone' => $data['contact_phone'],
                'number_of_people' => $data['number_of_people'],
                'booking_date' => now()->format('Y-m-d'),
                'selected_departure_date' => $data['selected_departure_date'],
                'status' => 'pending',
                'note' => $data['note'] ?? null,
                'total_amount' => $pricing['total_amount'],
            ]);
        });
    }
    
    /**
     * Cập nhật status booking
     */
    public function updateBookingStatus(TourBooking $booking, string $status): bool
    {
        $allowedStatuses = ['pending', 'confirmed', 'grouped', 'cancelled', 'partially_cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new \InvalidArgumentException("Status '{$status}' không hợp lệ");
        }
        
        return $booking->update(['status' => $status]);
    }
    
    /**
     * Hủy booking
     */
    public function cancelBooking(TourBooking $booking, string $reason = null): bool
    {
        if (!$booking->canBeCancelled()) {
            throw new \Exception('Không thể hủy booking này');
        }
        
        return DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => 'cancelled',
                'note' => $booking->note . ($reason ? "\nLý do hủy: {$reason}" : '')
            ]);
            
            return true;
        });
    }
}
