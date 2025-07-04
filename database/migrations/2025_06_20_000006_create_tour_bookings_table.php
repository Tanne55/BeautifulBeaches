<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->date('booking_date');
            $table->string('full_name');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('note')->nullable();
            $table->integer('number_of_people')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};
