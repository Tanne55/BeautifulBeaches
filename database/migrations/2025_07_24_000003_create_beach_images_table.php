<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('beach_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_id')->constrained('beaches')->onDelete('cascade');
            $table->string('image_url', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beach_images');
    }
};
