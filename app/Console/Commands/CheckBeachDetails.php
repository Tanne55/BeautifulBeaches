<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BeachDetail;

class CheckBeachDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-beach-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra dữ liệu trong bảng beach_details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Kiểm tra bảng beach_details");
        
        // Kiểm tra cấu trúc bảng
        $columns = DB::select("SHOW COLUMNS FROM beach_details");
        $this->info("Cấu trúc bảng:");
        foreach ($columns as $column) {
            $this->info("- {$column->Field} ({$column->Type})");
        }
        
        // Lấy dữ liệu từ bảng
        $details = DB::select("SELECT * FROM beach_details");
        $this->info("\nDữ liệu trong bảng:");
        foreach ($details as $detail) {
            $this->info("ID: {$detail->id}, Beach ID: {$detail->beach_id}");
            $this->info("  long_description2: " . ($detail->long_description2 ?? 'NULL'));
            $this->info("  long_description: " . ($detail->long_description ?? 'NULL'));
            $this->info("  highlight_quote: " . ($detail->highlight_quote ?? 'NULL'));
            $this->info("  tags: " . ($detail->tags ?? 'NULL'));
            $this->info("----------");
        }
    }
}
