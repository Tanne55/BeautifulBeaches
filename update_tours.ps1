# Script to update tours.json - replace departure_time with departure_dates array
$jsonPath = "P:\LaravelProject\BeautifulBeaches\database\Data\tours.json"

# Read the JSON content
$content = Get-Content $jsonPath -Raw

# Define the replacement pattern
# Replace "departure_time": "YYYY-MM-DDTHH:mm:ss" with departure_dates array
$pattern = '"departure_time":\s*"([^"]+)"'
$replacement = {
    param($match)
    $originalTime = $match.Groups[1].Value
    
    # Generate 4 different dates (every 3-4 days)
    $baseDate = [DateTime]::Parse($originalTime)
    $dates = @()
    
    for ($i = 0; $i -lt 4; $i++) {
        $newDate = $baseDate.AddDays($i * 3)
        $dates += '"' + $newDate.ToString("yyyy-MM-ddTHH:mm:ss") + '"'
    }
    
    return '"departure_dates": [' + ($dates -join ', ') + ']'
}

# Perform the replacement
$newContent = [regex]::Replace($content, $pattern, $replacement)

# Write back to file
$newContent | Set-Content $jsonPath -Encoding UTF8

Write-Host "Updated tours.json successfully!"
