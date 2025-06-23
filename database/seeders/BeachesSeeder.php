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
                // Insert vào bảng beaches
                $beachId = DB::table('beaches')->insertGetId([
                    'title' => $beach['title'],
                    'image' => $beach['image'],
                    'region' => $beach['region'],
                    'short_description' => $beach['shortDescription'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert vào bảng beach_details
                DB::table('beach_details')->insert([
                    'beach_id' => $beachId,
                    'long_description' => $beach['longDescription'],
                    'highlight_quote' => $beach['highlightQuote'],
                    'long_description2' => $beach['longDescription2'],
                    'tags' => json_encode($beach['tags']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insertedCount++;
            } catch (\Exception $e) {
                $this->command->error('Lỗi khi insert beach hoặc beach_details cho "' . $beach['title'] . '": ' . $e->getMessage());
            }
        }

        $this->command->info('Đã seed ' . $insertedCount . ' beaches và beach_details thành công.');
    }
}
