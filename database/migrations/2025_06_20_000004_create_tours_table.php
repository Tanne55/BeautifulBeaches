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
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2)->nullable();
            $table->integer('capacity');
            $table->integer('duration_days')->default(1);
            $table->enum('status', ['active', 'inactive','outdated'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
}; 