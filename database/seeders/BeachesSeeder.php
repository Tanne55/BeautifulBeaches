<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BeachesSeeder extends Seeder
{
    public function run(): void
    {
        // Kiểm tra file JSON có tồn tại không
        $jsonPath = database_path('Data/beach_data.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('File beach_data.json không tồn tại trong database/Data/');
            return;
        }

        $json = File::get($jsonPath);
        $beaches = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Lỗi JSON trong file beach_data.json: ' . json_last_error_msg());
            return;
        }

        $insertedCount = 0;
        foreach ($beaches as $beach) {
            try {
                DB::table('beaches')->insert([
                    'region' => $beach['region'],
                    'image' => $beach['image'],
                    'title' => $beach['title'],
                    'short_description' => $beach['shortDescription'],
                    'long_description' => $beach['longDescription'],
                    'long_description_2' => $beach['longDescription2'],
                    'highlight_quote' => $beach['highlightQuote'],
                    'tags' => json_encode($beach['tags']),
                    'price' => $beach['price'],
                    'original_price' => $beach['originalPrice'],
                    'capacity' => $beach['capacity'],
                    'duration' => $beach['duration'],
                    'rating' => $beach['rating'],
                    'reviews' => $beach['reviews'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insertedCount++;
            } catch (\Exception $e) {
                $this->command->error('Lỗi khi insert beach "' . $beach['title'] . '": ' . $e->getMessage());
            }
        }

        $this->command->info('Đã seed ' . $insertedCount . ' beaches thành công.');
    }
}
