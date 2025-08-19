<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tour;

echo "=== DEBUG TOUR PRICES ===" . PHP_EOL;

$tour = Tour::with(['prices'])->first();

if ($tour) {
    echo "Tour: " . $tour->title . PHP_EOL;
    echo "Tour ID: " . $tour->id . PHP_EOL;
    echo "Has prices: " . ($tour->prices ? 'Yes (' . $tour->prices->count() . ')' : 'No') . PHP_EOL;
    
    if ($tour->prices->count() > 0) {
        foreach ($tour->prices as $index => $price) {
            echo "Price #$index:" . PHP_EOL;
            echo "  - Price: " . $price->price . PHP_EOL;
            echo "  - Discount: " . $price->discount . PHP_EOL;
            echo "  - Start date: " . $price->start_date . PHP_EOL;
            echo "  - End date: " . $price->end_date . PHP_EOL;
            echo "  - Created at: " . $price->created_at . PHP_EOL;
        }
        
        echo PHP_EOL . "=== CURRENT PRICE DETAILS ===" . PHP_EOL;
        $priceDetails = $tour->current_price_details;
        echo "Current price details: " . json_encode($priceDetails, JSON_PRETTY_PRINT) . PHP_EOL;
        
        echo PHP_EOL . "=== CURRENT PRICE ===" . PHP_EOL;
        echo "Current price: " . $tour->current_price . PHP_EOL;
        
    } else {
        echo "No prices found for this tour" . PHP_EOL;
    }
} else {
    echo "No tours found in database" . PHP_EOL;
}

echo PHP_EOL . "=== CHECK ALL TOURS ===" . PHP_EOL;
$allTours = Tour::with(['prices'])->take(3)->get();
foreach ($allTours as $t) {
    echo "Tour {$t->id}: {$t->title} - Prices: {$t->prices->count()}" . PHP_EOL;
}
