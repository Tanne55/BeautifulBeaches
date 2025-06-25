<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            'password' => '1234',
            'role' => 'admin',
        ]);

        // CEO
        User::create([
            'name' => 'CEO Beachy',
            'email' => 'ceo@example.com',
            'password' => '1234',
            'role' => 'ceo',
        ]);

        // User thường
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => '1234',
            'role' => 'user',
        ]);
    }
}
