<?php

/**
 * Pre-migration script to update all data files with current timestamps
 * Run this script before running migrations to ensure data is not outdated
 */

echo "Starting pre-migration data update...\n";

// List of data files to update
$dataFiles = [
    'database/Data/tours.json',
    'database/Data/beach_data.json'
];

// Get current timestamp
$currentTimestamp = date('Y-m-d H:i:s');
echo "Current timestamp: $currentTimestamp\n\n";

foreach ($dataFiles as $file) {
    if (!file_exists($file)) {
        echo "Warning: File $file does not exist, skipping...\n";
        continue;
    }
    
    echo "Updating $file...\n";
    
    // Read the file
    $data = file_get_contents($file);
    
    if ($data === false) {
        echo "Error: Could not read $file\n";
        continue;
    }
    
    // Replace all hardcoded dates with current timestamp
    $updatedData = preg_replace(
        '/"created_at":\s*"[^"]*"/',
        '"created_at": "' . $currentTimestamp . '"',
        $data
    );
    
    $updatedData = preg_replace(
        '/"updated_at":\s*"[^"]*"/',
        '"updated_at": "' . $currentTimestamp . '"',
        $updatedData
    );
    
    // Write back to file
    if (file_put_contents($file, $updatedData) === false) {
        echo "Error: Could not write to $file\n";
        continue;
    }
    
    echo "Successfully updated $file\n";
}

echo "\nPre-migration update completed!\n";
echo "You can now run your migrations with fresh timestamps.\n";

?> 