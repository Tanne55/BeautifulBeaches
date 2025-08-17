<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourBookingGroup;
use Carbon\Carbon;

class TourBookingGroupSeeder extends Seeder
{
    public function run()
    {
        $tours = Tour::all();
        
        foreach ($tours as $tour) {
            // Lấy các booking có status 'pending' hoặc 'confirmed' với cùng selected_departure_date
            $tourBookings = TourBooking::where('tour_id', $tour->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get()
                ->groupBy('selected_departure_date'); // Group theo ngày khởi hành
            
            foreach ($tourBookings as $departureDate => $bookingsForDate) {
                if ($bookingsForDate->count() >= 2) {
                    // Chọn 2-5 booking ngẫu nhiên để nhóm lại
                    $selectedBookings = $bookingsForDate->random(min($bookingsForDate->count(), rand(2, 5)));
                    
                    // Tính tổng số người và tổng tiền
                    $totalPeople = $selectedBookings->sum('number_of_people');
                    $totalAmount = $selectedBookings->sum('total_amount');
                    $bookingIds = $selectedBookings->pluck('id')->toArray();
                    
                    // Sinh mã group_code duy nhất
                    do {
                        $groupCode = 'GRP-' . strtoupper(Str::random(8));
                    } while (TourBookingGroup::where('group_code', $groupCode)->exists());
                    
                    // Tạo tour booking group
                    TourBookingGroup::create([
                        'tour_id' => $tour->id,
                        'group_code' => $groupCode,
                        'total_people' => $totalPeople,
                        'total_amount' => $totalAmount,
                        'booking_ids' => $bookingIds, // Laravel sẽ tự động convert thành JSON
                        'selected_departure_date' => Carbon::parse($departureDate),
                    ]);
                    
                    // Cập nhật status của các booking thành 'grouped'
                    TourBooking::whereIn('id', $bookingIds)->update(['status' => 'grouped']);
                    
                    
                }
            }
        }
        
        $this->command->info('TourBookingGroup seeder completed successfully!');
    }
}
