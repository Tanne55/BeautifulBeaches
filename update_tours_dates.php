<?php

// Script to update tour dates in tours.json with current timestamp
$toursFile = 'database/Data/tours.json';

// Read the tours.json file
$toursData = file_get_contents($toursFile);

if ($toursData === false) {
    echo "Error: Could not read tours.json file\n";
    exit(1);
}

// Get tomorrow's timestamp
$tomorrowTimestamp = date('Y-m-d H:i:s', strtotime('+1 day'));
$tomorrowDate = date('Y-m-d', strtotime('+1 day'));

// Decode JSON to work with the data
$tours = json_decode($toursData, true);

if ($tours === null) {
    echo "Error: Invalid JSON format in tours.json\n";
    exit(1);
}

// Update each tour
foreach ($tours as &$tour) {
    // Update created_at and updated_at
    $tour['created_at'] = $tomorrowTimestamp;
    $tour['updated_at'] = $tomorrowTimestamp;
    
    // Update departure_time to tomorrow at 08:00:00
    $tour['departure_time'] = $tomorrowDate . 'T08:00:00';
    
    // Calculate return_time based on duration_days
    $departureDate = new DateTime($tour['departure_time']);
    $returnDate = clone $departureDate;
    $returnDate->add(new DateInterval('P' . $tour['duration_days'] . 'D'));
    $tour['return_time'] = $returnDate->format('Y-m-d\TH:i:s');
}

// Encode back to JSON with proper formatting
$updatedData = json_encode($tours, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Write back to file
if (file_put_contents($toursFile, $updatedData) === false) {
    echo "Error: Could not write to tours.json file\n";
    exit(1);
}

echo "Successfully updated tours.json with tomorrow's timestamp: $tomorrowTimestamp\n";
echo "All created_at, updated_at, departure_time, and return_time fields have been updated.\n";
echo "Departure time set to: " . $tomorrowDate . "T08:00:00\n";
echo "Return time calculated based on duration_days for each tour.\n";

?> 