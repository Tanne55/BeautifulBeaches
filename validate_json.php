<?php
// Script to validate and fix tours.json
$jsonPath = __DIR__ . '/database/Data/tours.json';

echo "Validating tours.json...\n";

$content = file_get_contents($jsonPath);
$data = json_decode($content, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg() . "\n";
    echo "Error occurred at position: " . json_last_error() . "\n";
    
    // Try to find the problematic line
    $lines = explode("\n", $content);
    echo "File has " . count($lines) . " lines\n";
    
    // Check for common issues
    $issues = [];
    foreach ($lines as $lineNum => $line) {
        $line = trim($line);
        
        // Check for incomplete objects
        if (preg_match('/^\{\s*"ceo_id":\s*\d+\s*$/', $line)) {
            $issues[] = "Line " . ($lineNum + 1) . ": Incomplete object: $line";
        }
        
        // Check for trailing commas before closing brackets
        if (preg_match('/,\s*[\}\]]/', $line)) {
            $issues[] = "Line " . ($lineNum + 1) . ": Trailing comma: $line";
        }
    }
    
    if (!empty($issues)) {
        echo "Found issues:\n";
        foreach ($issues as $issue) {
            echo "- $issue\n";
        }
    }
    
    exit(1);
} else {
    echo "JSON is valid!\n";
    echo "Found " . count($data) . " tours\n";
    
    // Check each tour for required fields
    $requiredFields = ['id', 'beach_id', 'title', 'departure_dates', 'ceo_id'];
    $invalidTours = [];
    
    foreach ($data as $index => $tour) {
        foreach ($requiredFields as $field) {
            if (!isset($tour[$field])) {
                $invalidTours[] = "Tour at index $index missing field: $field";
            }
        }
        
        // Check if departure_dates is array
        if (isset($tour['departure_dates']) && !is_array($tour['departure_dates'])) {
            $invalidTours[] = "Tour at index $index: departure_dates is not an array";
        }
    }
    
    if (!empty($invalidTours)) {
        echo "Found invalid tours:\n";
        foreach ($invalidTours as $error) {
            echo "- $error\n";
        }
    } else {
        echo "All tours are valid!\n";
    }
}
?>
