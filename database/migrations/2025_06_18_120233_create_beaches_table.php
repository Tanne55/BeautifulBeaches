<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beaches', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('image');
            $table->string('title');
            $table->text('short_description');
            $table->text('long_description');
            $table->text('long_description_2');
            $table->string('highlight_quote');
            $table->json('tags')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2);
            $table->integer('capacity');
            $table->string('duration');
            $table->tinyInteger('rating');
            $table->integer('reviews');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beaches');
    }
};
