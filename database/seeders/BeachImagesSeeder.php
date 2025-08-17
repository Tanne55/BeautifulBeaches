<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BeachImage;
use App\Models\Beach;

class BeachImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beaches = Beach::all();
        
        // Danh sách các loại ảnh và URL mẫu
        $imageTypes = [
            'hero' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1551524164-6cf17ac0b820?w=1200&h=800&fit=crop',
            ],
            'gallery' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1544551763-77ef2d0cfc6c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1520637836862-4d197d17c38a?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
            ],
            'thumbnail' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop',
            ],
            'detail' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1544551763-77ef2d0cfc6c?w=600&h=400&fit=crop',
            ],
            'panorama' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1600&h=600&fit=crop',
            ]
        ];

        $captions = [
            'hero' => [
                'Khung cảnh tuyệt đẹp của bãi biển',
                'Hoàng hôn trên biển xanh',
                'Thiên đường nhiệt đới',
            ],
            'gallery' => [
                'Nước biển trong xanh',
                'Cát trắng mịn màng',
                'Dừa xanh nghiêng mình',
                'Sóng vỗ bờ êm ái',
                'Khung cảnh bình minh',
            ],
            'thumbnail' => [
                'Ảnh đại diện bãi biển',
                'Hình thu nhỏ',
            ],
            'detail' => [
                'Chi tiết bãi biển',
                'Góc nhìn đặc biệt',
            ],
            'panorama' => [
                'Toàn cảnh bãi biển',
                'Khung cảnh 360 độ',
            ]
        ];

        foreach ($beaches as $index => $beach) {
            $sortOrder = 0;
            
            // Tạo ảnh hero (1 ảnh, là ảnh chính)
            BeachImage::create([
                'beach_id' => $beach->id,
                'image_url' => $imageTypes['hero'][$index % count($imageTypes['hero'])],
                'alt_text' => "Hero image for {$beach->title}",
                'caption' => $captions['hero'][$index % count($captions['hero'])],
                'is_primary' => true,
                'sort_order' => $sortOrder++,
                'image_type' => 'hero',
            ]);

            // Tạo ảnh gallery (3-5 ảnh)
            $galleryCount = rand(3, 5);
            for ($i = 0; $i < $galleryCount; $i++) {
                BeachImage::create([
                    'beach_id' => $beach->id,
                    'image_url' => $imageTypes['gallery'][($index + $i) % count($imageTypes['gallery'])],
                    'alt_text' => "Gallery image " . ($i + 1) . " for {$beach->title}",
                    'caption' => $captions['gallery'][($index + $i) % count($captions['gallery'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'gallery',
                ]);
            }

            // Tạo ảnh thumbnail
            BeachImage::create([
                'beach_id' => $beach->id,
                'image_url' => $imageTypes['thumbnail'][$index % count($imageTypes['thumbnail'])],
                'alt_text' => "Thumbnail for {$beach->title}",
                'caption' => $captions['thumbnail'][$index % count($captions['thumbnail'])],
                'is_primary' => false,
                'sort_order' => $sortOrder++,
                'image_type' => 'thumbnail',
            ]);

            // Tạo ảnh detail (1-2 ảnh)
            $detailCount = rand(1, 2);
            for ($i = 0; $i < $detailCount; $i++) {
                BeachImage::create([
                    'beach_id' => $beach->id,
                    'image_url' => $imageTypes['detail'][($index + $i) % count($imageTypes['detail'])],
                    'alt_text' => "Detail image " . ($i + 1) . " for {$beach->title}",
                    'caption' => $captions['detail'][($index + $i) % count($captions['detail'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'detail',
                ]);
            }

            // Tạo ảnh panorama (1 ảnh)
            if (rand(0, 1)) { // 50% chance để có ảnh panorama
                BeachImage::create([
                    'beach_id' => $beach->id,
                    'image_url' => $imageTypes['panorama'][$index % count($imageTypes['panorama'])],
                    'alt_text' => "Panorama view of {$beach->title}",
                    'caption' => $captions['panorama'][$index % count($captions['panorama'])],
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                    'image_type' => 'panorama',
                ]);
            }
        }
    }
}
