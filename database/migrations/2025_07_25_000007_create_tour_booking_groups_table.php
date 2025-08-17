<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tour_booking_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->string('group_code', 50)->unique();
            $table->date('selected_departure_date');
            $table->integer('total_people');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('booking_ids');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('tour_booking_groups');
    }
};
