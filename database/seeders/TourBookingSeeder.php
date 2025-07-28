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
        $tours = Tour::all();
        $users = User::where('role', 'user')->get();
        $year = now()->year;
        $currentMonth = now()->month;
        $previousMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $previousYear = $currentMonth == 1 ? $year - 1 : $year;
        
        // Tạo booking trải đều 12 tháng, nhiều tour, nhiều user
        foreach ($tours as $tour) {
            for ($m = 0; $m < 12; $m++) {
                $user = $users->random();
                $numberOfPeople = rand(1, 8);
                $month = now()->copy()->subMonths($m);
                $bookingDate = $month->copy()->day(rand(1, 28));
                // Lấy giá từ bảng tour_prices, kiểm tra tồn tại
                $price = 0;
                if ($tour->relationLoaded('prices') && $tour->prices && $tour->prices->count() > 0) {
                    $price = $tour->prices->first()->price ?? 0;
                } else {
                    // Nếu chưa load quan hệ, thử lấy trực tiếp từ DB
                    $priceRow = DB::table('tour_prices')->where('tour_id', $tour->id)->first();
                    $price = $priceRow ? $priceRow->price : 0;
                }
                $totalAmount = $price * $numberOfPeople;
                // Sinh mã booking_code duy nhất
                do {
                    $bookingCode = 'BK-' . strtoupper(Str::random(8));
                } while (TourBooking::where('booking_code', $bookingCode)->exists());
                TourBooking::create([
                    'user_id' => $user->id,
                    'tour_id' => $tour->id,
                    'booking_code' => $bookingCode,
                    'full_name' => $user->name,
                    'contact_email' => $user->email,
                    'contact_phone' => '09' . rand(10000000, 99999999),
                    'number_of_people' => $numberOfPeople,
                    'booking_date' => $bookingDate,
                    'status' => 'confirmed',
                    'total_amount' => $totalAmount,
                    'note' => null,
                ]);
            }
        }
    }
} 