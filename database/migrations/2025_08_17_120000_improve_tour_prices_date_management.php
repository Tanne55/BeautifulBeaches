<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trước tiên, cập nhật dữ liệu hiện có
        DB::table('tour_prices')
            ->whereNull('start_date')
            ->update(['start_date' => now()->toDateString()]);
            
        DB::table('tour_prices')
            ->whereNull('end_date')
            ->update(['end_date' => now()->addYear()->toDateString()]);

        Schema::table('tour_prices', function (Blueprint $table) {
            // Đảm bảo start_date và end_date không null
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
            
            // Thêm index để tối ưu hóa query theo date range
            $table->index(['tour_id', 'start_date', 'end_date'], 'idx_tour_prices_date_range');
            
            // Thêm index cho việc tìm giá hiện tại
            $table->index(['start_date', 'end_date'], 'idx_tour_prices_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_prices', function (Blueprint $table) {
            // Xóa indexes
            $table->dropIndex('idx_tour_prices_date_range');
            $table->dropIndex('idx_tour_prices_current');
            
            // Khôi phục nullable
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
        });
    }
};
