<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ToursSeeder extends Seeder
{
    public function run(): void
    {
        // Kiểm tra file JSON có tồn tại không
        $jsonPath = database_path('Data/tours.json');
        if (!File::exists($jsonPath)) {
            $this->command->info('File tours.json không tồn tại. Bỏ qua seeding tours.');
            return;
        }

        $json = File::get($jsonPath);
        $tours = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Lỗi JSON trong file tours.json: ' . json_last_error_msg());
            return;
        }

        $insertedCount = 0;
        foreach ($tours as $tour) {
            try {
                // Insert vào bảng tours (KHÔNG có price, original_price)
                $tourId = DB::table('tours')->insertGetId([
                    'beach_id' => $tour['beach_id'],
                    'ceo_id' => $tour['ceo_id'],
                    'image' => $tour['image'],
                    'title' => $tour['title'],
                    'capacity' => $tour['capacity'],
                    'duration_days' => $tour['duration_days'],
                    'status' => $tour['status'],
                    'created_at' => $tour['created_at'] ?? now(),
                    'updated_at' => $tour['updated_at'] ?? now(),
                ]);

                // Insert vào bảng tour_prices
                DB::table('tour_prices')->insert([
                    'tour_id' => $tourId,
                    'price' => $tour['price'] ?? 0,
                    'discount' => $tour['original_price'] ?? null,
                    'start_date' => now()->subDays(30), // ← Thêm dòng này
                    'end_date' => now()->addDays(365),
                    'created_at' => $tour['created_at'] ?? now(),
                    'updated_at' => $tour['updated_at'] ?? now(),
                ]);

                // Insert vào bảng tour_details
                DB::table('tour_details')->insert([
                    'tour_id' => $tourId,
                    'departure_dates' => json_encode($tour['departure_dates']),
                    'return_time' => $tour['return_time'],
                    'included_services' => json_encode($tour['included_services']),
                    'excluded_services' => json_encode($tour['excluded_services']),
                    'highlights' => json_encode($tour['highlights']),
                    'created_at' => $tour['created_at'] ?? now(),
                    'updated_at' => $tour['updated_at'] ?? now(),
                ]);
                $insertedCount++;
            } catch (\Exception $e) {
                $this->command->error('Lỗi khi insert tour hoặc tour_details cho "' . $tour['title'] . '": ' . $e->getMessage());
            }
        }

        // XÓA đoạn migrate price/discount từ bảng tours sang tour_prices (không còn cần thiết)

        $this->command->info('Đã seed ' . $insertedCount . ' tours và tour_details thành công.');
    }
}