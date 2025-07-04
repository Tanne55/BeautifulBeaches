<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        
        foreach ($tours as $tour) {
            for ($i = 1; $i <= 2; $i++) {
                $user = $users->random();
                $numberOfPeople = rand(1, 5);
                
                // Random chọn giữa tháng hiện tại và tháng trước
                if (rand(0, 1) == 0) {
                    // Tháng hiện tại
                    $bookingDate = Carbon::create($year, $currentMonth, rand(1, 28));
                } else {
                    // Tháng trước
                    $bookingDate = Carbon::create($previousYear, $previousMonth, rand(1, 28));
                }
                
                TourBooking::create([
                    'user_id' => $user->id,
                    'tour_id' => $tour->id,
                    'full_name' => $user->name,
                    'contact_email' => $user->email,
                    'contact_phone' => '09' . rand(10000000, 99999999),
                    'number_of_people' => $numberOfPeople,
                    'booking_date' => $bookingDate,
                    'status' => 'confirmed',
                    'note' => null,
                ]);
            }
        }
    }
} 