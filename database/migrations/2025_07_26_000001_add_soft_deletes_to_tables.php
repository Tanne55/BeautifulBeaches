<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add soft deletes to beach_details
        Schema::table('beach_details', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to regions
        Schema::table('regions', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to beach_images
        Schema::table('beach_images', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to tour_images
        Schema::table('tour_images', function (Blueprint $table) {
            $table->softDeletes();
        });


        // Add soft deletes to tour_prices
        Schema::table('tour_prices', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to tour_booking_groups
        Schema::table('tour_booking_groups', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to cancellation_requests
        Schema::table('cancellation_requests', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        $tables = [
            'beach_details',
            'regions',
            'beach_images',
            'tour_images',
            'tour_prices',
            'tour_booking_groups',
            'cancellation_requests',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
