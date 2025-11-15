<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed theo thứ tự: Users -> Beaches -> Tours -> Reviews -> Support
        $this->call([
            UserSeeder::class,
            BeachesSeeder::class,
            ToursSeeder::class,
            BeachImagesSeeder::class,
            TourImagesSeeder::class,
            TourBookingSeeder::class,
            TourBookingGroupSeeder::class,
            TicketSeeder::class,
            ReviewBeachSeeder::class,
            ReviewTourSeeder::class,
            SupportRequestSeeder::class,
        ]);
    }
}
