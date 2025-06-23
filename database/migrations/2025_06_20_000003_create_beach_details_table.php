<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beach_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_id')->constrained('beaches')->onDelete('cascade');
            $table->text('long_description');
            $table->text('highlight_quote')->nullable();
            $table->text('long_description2')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('beach_details');
    }
}; 