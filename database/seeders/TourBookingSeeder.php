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
        
        // Lặp qua 12 tháng (từ tháng hiện tại về trước 11 tháng)
        for ($monthOffset = 0; $monthOffset < 12; $monthOffset++) {
            foreach ($tours as $tour) {
                // Lấy departure dates gốc từ tour detail
                $baseDepartureDates = [];
                if ($tour->detail && $tour->detail->departure_dates) {
                    if (is_array($tour->detail->departure_dates)) {
                        $baseDepartureDates = $tour->detail->departure_dates;
                    } elseif (is_string($tour->detail->departure_dates)) {
                        $baseDepartureDates = json_decode($tour->detail->departure_dates, true) ?? [];
                    }
                }
                
                // Nếu không có departure dates, tạo một số ngày mặc định dựa trên tháng hiện tại
                if (empty($baseDepartureDates)) {
                    $baseDate = now()->addDays(7);
                    $baseDepartureDates = [
                        $baseDate->format('Y-m-d\TH:i:s'),
                        $baseDate->copy()->addDays(3)->format('Y-m-d\TH:i:s'),
                        $baseDate->copy()->addDays(6)->format('Y-m-d\TH:i:s'),
                        $baseDate->copy()->addDays(9)->format('Y-m-d\TH:i:s'),
                    ];
                }
                
                // Điều chỉnh departure_dates về tháng tương ứng (điều chỉnh tháng về trước)
                $departureDates = [];
                foreach ($baseDepartureDates as $dateStr) {
                    try {
                        $date = Carbon::parse($dateStr);
                        // Điều chỉnh tháng về trước
                        $adjustedDate = $date->copy()->subMonths($monthOffset);
                        $departureDates[] = $adjustedDate->format('Y-m-d\TH:i:s');
                    } catch (\Exception $e) {
                        // Nếu không parse được, bỏ qua
                        continue;
                    }
                }
                
                if (empty($departureDates)) {
                    continue; // Bỏ qua tour này nếu không có departure dates hợp lệ
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
                
                // Tạo 5-15 booking ngẫu nhiên cho mỗi tour mỗi tháng
                $bookingCount = rand(5, 15);
                
                for ($i = 0; $i < $bookingCount; $i++) {
                    $user = $users->random();
                    $numberOfPeople = rand(1, 8);
                    
                    // Chọn ngẫu nhiên một departure date
                    $selectedDepartureDateStr = $departureDates[array_rand($departureDates)];
                    $selectedDepartureDate = Carbon::parse($selectedDepartureDateStr);
                    
                    // Xác định booking date dựa trên departure date
                    $isPast = $selectedDepartureDate->isPast();
                    
                    if ($isPast) {
                        // Nếu departure date quá khứ: booking_date <= departure_date
                        // Tạo booking_date trong khoảng 1-30 ngày trước departure_date
                        $daysBeforeDeparture = rand(1, 30);
                        $bookingDate = $selectedDepartureDate->copy()->subDays($daysBeforeDeparture);
                    } else {
                        // Nếu departure date tương lai: booking_date là 1-30 ngày trước departure date
                        $bookingDate = $selectedDepartureDate->copy()->subDays(rand(1, 30));
                        
                        // Đảm bảo booking_date <= now() (không tạo booking_date quá xa trong tương lai)
                        if ($bookingDate->gt(now())) {
                            $bookingDate = now()->copy();
                        }
                    }
                    
                    // Tính total amount
                    $totalAmount = $price * $numberOfPeople;
                    
                    // Sinh mã booking_code duy nhất
                    do {
                        $bookingCode = 'BK-' . strtoupper(Str::random(8));
                    } while (TourBooking::where('booking_code', $bookingCode)->exists());
                    
                    // Xác định status dựa trên departure date (đã xác định $isPast ở trên)
                    if ($isPast) {
                        // Bookings quá khứ: chủ yếu confirmed, một ít cancelled
                        $statuses = ['confirmed', 'confirmed', 'confirmed', 'confirmed', 'cancelled'];
                    } else {
                        // Bookings tương lai: pending, confirmed, cancelled
                        $statuses = ['pending', 'pending', 'confirmed', 'confirmed', 'cancelled'];
                    }
                    $status = $statuses[array_rand($statuses)];
                    
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
        }
        
        $this->command->info('TourBooking seeder completed successfully!');
    }
} 