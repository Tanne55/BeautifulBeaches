<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tour;
use App\Models\User;
use App\Models\TourBooking;
use Carbon\Carbon;

class TourBookingSeeder extends Seeder
{
    public function run()
    {
        $tours = Tour::with(['detail', 'prices'])->get();
        $users = User::where('role', 'user')->get();
        
        if ($tours->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Không có tour hoặc user để tạo booking!');
            return;
        }
        
        $year = now()->year;
        $currentMonth = now()->month;
        
        // Tạo booking cho 12 tháng qua và tương lai
        foreach ($tours as $tour) {
            // Lấy departure dates từ tour detail
            $departureDates = [];
            if ($tour->detail && $tour->detail->departure_dates) {
                if (is_array($tour->detail->departure_dates)) {
                    $departureDates = $tour->detail->departure_dates;
                } elseif (is_string($tour->detail->departure_dates)) {
                    $departureDates = json_decode($tour->detail->departure_dates, true) ?? [];
                }
            }
            
            // Nếu không có departure dates, tạo một số ngày mặc định
            if (empty($departureDates)) {
                $departureDates = [
                    now()->addDays(7)->format('Y-m-d H:i:s'),
                    now()->addDays(14)->format('Y-m-d H:i:s'),
                    now()->addDays(21)->format('Y-m-d H:i:s'),
                    now()->addDays(28)->format('Y-m-d H:i:s'),
                ];
            }
            
            // Lấy giá tour từ prices hoặc fallback
            $price = 0;
            if ($tour->prices && $tour->prices->isNotEmpty()) {
                $priceRecord = $tour->prices->first();
                $price = $priceRecord->discount && $priceRecord->discount > 0 
                    ? $priceRecord->final_price 
                    : $priceRecord->price;
            } else {
                $price = $tour->price ?? 500000; // Default price
            }
            
            // Tạo 5-15 booking ngẫu nhiên cho mỗi tour
            $bookingCount = rand(5, 15);
            
            for ($i = 0; $i < $bookingCount; $i++) {
                $user = $users->random();
                $numberOfPeople = rand(1, 8);
                
                // Chọn ngẫu nhiên một departure date
                $selectedDepartureDateStr = $departureDates[array_rand($departureDates)];
                $selectedDepartureDate = Carbon::parse($selectedDepartureDateStr);
                
                // Booking date là 1-30 ngày trước departure date
                $bookingDate = $selectedDepartureDate->copy()->subDays(rand(1, 30));
                
                // Đảm bảo booking_date không vượt quá hiện tại
                if ($bookingDate->isFuture()) {
                    $bookingDate = now()->subDays(rand(1, 7));
                }
                
                // Tính total amount
                $totalAmount = $price * $numberOfPeople;
                
                // Sinh mã booking_code duy nhất
                do {
                    $bookingCode = 'BK-' . strtoupper(Str::random(8));
                } while (TourBooking::where('booking_code', $bookingCode)->exists());
                
                // Random status - phần lớn confirmed/pending để có thể group
                $statuses = ['pending', 'pending', 'confirmed', 'confirmed', 'cancelled'];
                $status = $statuses[array_rand($statuses)];
                
                // Nếu departure date đã qua, không tạo pending booking
                if ($selectedDepartureDate->isPast() && $status === 'pending') {
                    $status = 'confirmed';
                }
                
                TourBooking::create([
                    'user_id' => $user->id,
                    'tour_id' => $tour->id,
                    'booking_code' => $bookingCode,
                    'full_name' => $user->name,
                    'contact_email' => $user->email,
                    'contact_phone' => '09' . rand(10000000, 99999999),
                    'number_of_people' => $numberOfPeople,
                    'booking_date' => $bookingDate,
                    'selected_departure_date' => $selectedDepartureDate,
                    'status' => $status,
                    'total_amount' => $totalAmount,
                    'note' => null,
                ]);
            }
        }
        
        $this->command->info('TourBooking seeder completed successfully!');
    }
} 