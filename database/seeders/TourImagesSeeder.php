<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TourImage;
use App\Models\Tour;

class TourImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tours = Tour::all();
        
        // Danh sách các loại ảnh và URL mẫu
        $imageTypes = [
            'hero' => [
                'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&h=800&fit=crop',
            ],
            'gallery' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1520637736862-4d197d17c38a?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
            ],
            'thumbnail' => [
                'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=300&fit=crop',
            ],
            'detail' => [
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1520637736862-4d197d17c38a?w=600&h=400&fit=crop',
            ],
            'activity' => [
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
            ],
            'location' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1520637736862-4d197d17c38a?w=800&h=600&fit=crop',
            ]
        ];

        $captions = [
            'hero' => [
                'Hành trình khám phá tuyệt vời',
                'Trải nghiệm đáng nhớ',
                'Tour du lịch hấp dẫn',
            ],
            'gallery' => [
                'Khoảnh khắc đẹp trong tour',
                'Hoạt động thú vị',
                'Cảnh đẹp dọc đường',
                'Trải nghiệm độc đáo',
                'Kỷ niệm khó quên',
            ],
            'thumbnail' => [
                'Ảnh đại diện tour',
                'Hình thu nhỏ',
            ],
            'detail' => [
                'Chi tiết tour du lịch',
                'Thông tin tour',
            ],
            'activity' => [
                'Hoạt động trong tour',
                'Trải nghiệm thú vị',
                'Tham gia hoạt động',
            ],
            'location' => [
                'Địa điểm tham quan',
                'Điểm đến hấp dẫn',
                'Cảnh quan thiên nhiên',
            ]
        ];

        foreach ($tours as $index => $tour) {
            $sortOrder = 0;
            
            // Tạo ảnh hero (1 ảnh, là ảnh chính)
            TourImage::create([
                'tour_id' => $tour->id,
                'image_url' => $imageTypes['hero'][$index % count($imageTypes['hero'])],
                'alt_text' => "Hero image for {$tour->title}",
                'caption' => $captions['hero'][$index % count($captions['hero'])],
                'is_primary' => true,
                'sort_order' => $sortOrder++,
                'image_type' => 'hero',
            ]);

            // Tạo ảnh gallery (3-5 ảnh)
            $galleryCount = rand(3, 5);
            for ($i = 0; $i < $galleryCount; $i++) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => $imageTypes['gallery'][($index + $i) % count($imageTypes['gallery'])],
                    'alt_text' => "Gallery image " . ($i + 1) . " for {$tour->title}",
                    'caption' => $captions['gallery'][($index + $i) % count($captions['gallery'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'gallery',
                ]);
            }

            // Tạo ảnh thumbnail
            TourImage::create([
                'tour_id' => $tour->id,
                'image_url' => $imageTypes['thumbnail'][$index % count($imageTypes['thumbnail'])],
                'alt_text' => "Thumbnail for {$tour->title}",
                'caption' => $captions['thumbnail'][$index % count($captions['thumbnail'])],
                'is_primary' => false,
                'sort_order' => $sortOrder++,
                'image_type' => 'thumbnail',
            ]);

            // Tạo ảnh activity (2-3 ảnh)
            $activityCount = rand(2, 3);
            for ($i = 0; $i < $activityCount; $i++) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => $imageTypes['activity'][($index + $i) % count($imageTypes['activity'])],
                    'alt_text' => "Activity image " . ($i + 1) . " for {$tour->title}",
                    'caption' => $captions['activity'][($index + $i) % count($captions['activity'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'activity',
                ]);
            }

            // Tạo ảnh location (1-2 ảnh)
            $locationCount = rand(1, 2);
            for ($i = 0; $i < $locationCount; $i++) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => $imageTypes['location'][($index + $i) % count($imageTypes['location'])],
                    'alt_text' => "Location image " . ($i + 1) . " for {$tour->title}",
                    'caption' => $captions['location'][($index + $i) % count($captions['location'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'location',
                ]);
            }

            // Tạo ảnh detail (1 ảnh)
            if (rand(0, 1)) { // 50% chance để có ảnh detail
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => $imageTypes['detail'][$index % count($imageTypes['detail'])],
                    'alt_text' => "Detail view of {$tour->title}",
                    'caption' => $captions['detail'][$index % count($captions['detail'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'detail',
                ]);
            }
        }
    }
}
