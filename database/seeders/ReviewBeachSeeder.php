<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beach;
use App\Models\User;
use App\Models\ReviewBeach;

class ReviewBeachSeeder extends Seeder
{
    public function run()
    {
        $beaches = Beach::all();
        $users = User::where('role', 'user')->get();
        $comment = [
            'Bãi biển rất đẹp và sạch sẽ!',
            'Trải nghiệm tuyệt vời, sẽ quay lại.',
            'Nước biển trong xanh, dịch vụ tốt.',
            'Khá đông vào cuối tuần nhưng vẫn ổn.',
            'Phong cảnh hữu tình, thích hợp nghỉ dưỡng.',
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
        
        foreach ($beaches as $beach) {
            // Tạo 2 review từ user đã đăng nhập
            for ($i = 0; $i < 2; $i++) {
                $user = $users->random();
                ReviewBeach::create([
                    'beach_id' => $beach->id,
                    'user_id' => $user->id,
                    'rating' => rand(3, 5),
                    'comment' => $comment[array_rand($comment)],
                ]);
            }
            
            // Tạo 1 review từ khách (không đăng nhập)
            ReviewBeach::create([
                'beach_id' => $beach->id,
                'user_id' => null,
                'guest_name' => $guestNames[array_rand($guestNames)],
                'guest_email' => $guestEmails[array_rand($guestEmails)],
                'rating' => rand(3, 5),
                'comment' => $comment[array_rand($comment)],
            ]);
        }
    }
} 