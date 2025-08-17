<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('tour_id');
            $table->date('booking_date');
            $table->date('selected_departure_date');
            
            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->string('full_name');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('note')->nullable();
            $table->integer('number_of_people')->default(1);
            $table->string('booking_code', 50)->unique()->nullable();
            $table->enum('status', ['pending', 'confirmed', 'grouped', 'cancelled', 'partially_cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};
