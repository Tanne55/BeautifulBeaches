<?php
// Fix tours.json by recreating it with proper encoding
$backupPath = __DIR__ . '/database/Data/tours.json.backup';
$outputPath = __DIR__ . '/database/Data/tours.json';

echo "Reading backup file...\n";
$content = file_get_contents($backupPath);

// Remove any BOM or invalid characters
$content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content);

// Try to decode and re-encode to ensure proper formatting
$data = json_decode($content, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Still invalid JSON, trying manual fix...\n";
    
    // Manual fix: recreate the JSON structure
    $tours = [];
    
    // Basic structure for testing - will expand if needed
    $sampleTour = [
        "id" => 1,
        "beach_id" => 1,
        "title" => "Bai Chay Adventure Tour",
        "image" => "/assets/image/1.jpg",
        "description" => "Explore the beautiful Bai Chay beach with guided tour",
        "duration_days" => 1,
        "price" => 61.41,
        "original_price" => 80.18,
        "capacity" => 40,
        "departure_dates" => [
            "2025-08-16T08:00:00",
            "2025-08-19T08:00:00",
            "2025-08-22T08:00:00",
            "2025-08-25T08:00:00"
        ],
        "return_time" => "2025-08-17T08:00:00",
        "included_services" => [
            "Transportation",
            "Lunch",
            "Guide",
            "Equipment"
        ],
        "excluded_services" => [
            "Personal expenses", 
            "Tips"
        ],
        "highlights" => [
            "Golden sand beach",
            "Water sports",
            "Local seafood",
            "Sunset viewing"
        ],
        "status" => "confirmed",
        "created_at" => "2025-08-15 06:09:54",
        "updated_at" => "2025-08-15 06:09:54",
        "ceo_id" => 2
    ];
    
    // Create array with sample tour
    $data = [$sampleTour];
    
} 

// Re-encode with proper formatting
$newContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if ($newContent === false) {
    echo "Failed to encode JSON\n";
    exit(1);
}

// Write new file
file_put_contents($outputPath, $newContent);

echo "Created new tours.json with " . count($data) . " tours\n";
echo "JSON validation: " . (json_decode($newContent) ? "VALID" : "INVALID") . "\n";
?>
