<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            'password' => '1234',
            'role' => 'admin',
            'phone' => '+84 909 123 456',
            'address' => '123 Đường Admin, Quận 1, TP.HCM',
            'avatar' => 'assets/image/users/admin-avatar.jpg',
            'language' => 'vi',
            'last_login' => now(),
            'email_verified' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Admin profile
        UserProfile::create([
            'user_id' => $admin->id,
            'dob' => '1990-05-15',
            'nationality' => 'Vietnam',
            'preferences' => json_encode([
                'notifications' => true,
                'theme' => 'light',
                'two_factor_auth' => true
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // CEO
        $ceo = User::create([
            'name' => 'CEO Beachy',
            'email' => 'ceo@example.com',
            'password' => '1234',
            'role' => 'ceo',
            'phone' => '+84 918 888 888',
            'address' => '789 Đường Doanh Nhân, Quận 3, TP.HCM',
            'avatar' => 'assets/image/users/ceo-avatar.jpg',
            'language' => 'vi',
            'last_login' => now()->subDays(1),
            'email_verified' => true,
            'email_verified_at' => now()->subDays(5),
            'remember_token' => Str::random(10),
            'created_at' => now()->subDays(5),
            'updated_at' => now(),
        ]);
        
        // CEO profile
        UserProfile::create([
            'user_id' => $ceo->id,
            'dob' => '1985-08-20',
            'nationality' => 'Vietnam',
            'preferences' => json_encode([
                'notifications' => true,
                'theme' => 'dark',
                'dashboard_widgets' => ['revenue', 'users', 'bookings', 'reviews']
            ]),
            'created_at' => now()->subDays(5),
            'updated_at' => now(),
        ]);

        // Regular user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => '1234',
            'role' => 'user',
            'phone' => '+84 935 777 999',
            'address' => '456 Đường Khách Hàng, Quận 7, TP.HCM',
            'avatar' => 'assets/image/users/user-avatar.jpg',
            'language' => 'vi',
            'last_login' => now()->subDays(3),
            'email_verified' => true,
            'email_verified_at' => now()->subDays(30),
            'remember_token' => Str::random(10),
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(15),
        ]);
        
        // User profile
        UserProfile::create([
            'user_id' => $user->id,
            'dob' => '1995-12-10',
            'nationality' => 'Vietnam',
            'preferences' => json_encode([
                'notifications' => false,
                'theme' => 'light',
                'favorite_beaches' => ['Nha Trang', 'Phú Quốc', 'Đà Nẵng']
            ]),
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(15),
        ]);
    }
}
