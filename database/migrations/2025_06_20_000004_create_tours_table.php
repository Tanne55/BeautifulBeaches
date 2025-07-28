<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_id')->constrained('beaches')->onDelete('cascade');
            $table->foreignId('ceo_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('image')->nullable();
            $table->integer('capacity');
            $table->integer('max_people')->nullable();
            $table->integer('duration_days')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });

        // New: Tour Prices Table
        Schema::create('tour_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tour_inventory');
        Schema::dropIfExists('tour_prices');
        Schema::dropIfExists('tours');
    }
}; 