<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\User;
use App\Models\ReviewTour;

class ReviewTourSeeder extends Seeder
{
    public function run()
    {
        $tours = Tour::all();
        $users = User::where('role', 'user')->get();
        $comment = [
            'Tour rất tuyệt vời, hướng dẫn viên thân thiện!',
            'Lịch trình hợp lý, giá cả phải chăng.',
            'Dịch vụ tốt, xe đưa đón mới.',
            'Địa điểm tham quan đẹp, đồ ăn ngon.',
            'Sẽ giới thiệu cho bạn bè!',
        ];
        
        $guestNames = [
            'Nguyễn Văn An',
            'Trần Thị Bình',
            'Lê Hoàng Cường',
            'Phạm Thị Dung',
            'Vũ Minh Đức',
            'Hoàng Thị Em',
            'Đặng Văn Phúc',
            'Bùi Thị Hương',
        ];
        
        $guestEmails = [
            'nguyen.van.an@gmail.com',
            'tran.thi.binh@yahoo.com',
            'le.hoang.cuong@hotmail.com',
            'pham.thi.dung@gmail.com',
            'vu.minh.duc@yahoo.com',
            'hoang.thi.em@gmail.com',
            'dang.van.phuc@hotmail.com',
            'bui.thi.huong@gmail.com',
        ];
        
        foreach ($tours as $tour) {
            // Tạo 2 review từ user đã đăng nhập
            for ($i = 0; $i < 2; $i++) {
                $user = $users->random();
                ReviewTour::create([
                    'tour_id' => $tour->id,
                    'user_id' => $user->id,
                    'rating' => rand(3, 5),
                    'comment' => $comment[array_rand($comment)],
                ]);
            }
            
            // Tạo 1 review từ khách (không đăng nhập)
            ReviewTour::create([
                'tour_id' => $tour->id,
                'user_id' => null,
                'guest_name' => $guestNames[array_rand($guestNames)],
                'guest_email' => $guestEmails[array_rand($guestEmails)],
                'rating' => rand(3, 5),
                'comment' => $comment[array_rand($comment)],
            ]);
        }
    }
} 