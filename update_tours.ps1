# Script to update tours.json - cập nhật tất cả ngày tháng dựa trên thời gian hiện tại
$jsonPath = "P:\LaravelProject\BeautifulBeaches\database\Data\tours.json"

# Đọc file JSON
$jsonContent = Get-Content $jsonPath -Raw -Encoding UTF8
$tours = $jsonContent | ConvertFrom-Json

# Lấy thời gian hiện tại
$now = Get-Date

# Hàm để parse date từ string (hỗ trợ cả format ISO và datetime MySQL)
function Parse-Date {
    param($dateString)
    try {
        # Thử parse format ISO: "2025-08-16T08:00:00"
        if ($dateString -match 'T') {
            return [DateTime]::Parse($dateString)
        }
        # Thử parse format MySQL: "2025-09-08 18:29:58"
        elseif ($dateString -match '\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}') {
            return [DateTime]::ParseExact($dateString, "yyyy-MM-dd HH:mm:ss", $null)
        }
        else {
            return [DateTime]::Parse($dateString)
        }
    }
    catch {
        Write-Warning "Không thể parse ngày: $dateString"
        return $null
    }
}

# Hàm để format date về string
function Format-Date {
    param($date, $format)
    if ($format -eq "ISO") {
        return $date.ToString("yyyy-MM-ddTHH:mm:ss")
    }
    else {
        return $date.ToString("yyyy-MM-dd HH:mm:ss")
    }
}

# Duyệt qua từng tour
foreach ($tour in $tours) {
    # Tìm ngày sớm nhất trong tour (để tính offset)
    $earliestDate = $null
    
    # Kiểm tra departure_dates
    if ($tour.departure_dates -and $tour.departure_dates.Count -gt 0) {
        foreach ($dateStr in $tour.departure_dates) {
            $date = Parse-Date $dateStr
            if ($date -and ($null -eq $earliestDate -or $date -lt $earliestDate)) {
                $earliestDate = $date
            }
        }
    }
    
    # Kiểm tra departure_time nếu có
    if ($tour.departure_time) {
        $date = Parse-Date $tour.departure_time
        if ($date -and ($null -eq $earliestDate -or $date -lt $earliestDate)) {
            $earliestDate = $date
        }
    }
    
    # Nếu không tìm thấy ngày nào, bỏ qua tour này
    if ($null -eq $earliestDate) {
        Write-Warning "Không tìm thấy ngày trong tour ID: $($tour.id)"
        continue
    }
    
    # Tính offset: đảm bảo ngày sớm nhất mới sẽ là ít nhất 7 ngày trong tương lai
    # offset = (now + 7 ngày) - earliestDate
    $targetEarliestDate = $now.AddDays(3)
    $daysOffset = ($targetEarliestDate - $earliestDate).Days
    
    # Đảm bảo offset ít nhất là 0 (nếu ngày cũ đã quá xa trong tương lai)
    if ($daysOffset -lt 0) {
        $daysOffset = 0
    }
    
    # Cập nhật departure_dates
    if ($tour.departure_dates -and $tour.departure_dates.Count -gt 0) {
        $updatedDates = @()
        foreach ($dateStr in $tour.departure_dates) {
            $date = Parse-Date $dateStr
            if ($date) {
                $newDate = $date.AddDays($daysOffset)
                $updatedDates += Format-Date $newDate "ISO"
            }
            else {
                $updatedDates += $dateStr
            }
        }
        $tour.departure_dates = $updatedDates
    }
    
    # Cập nhật departure_time nếu có
    if ($tour.departure_time) {
        $date = Parse-Date $tour.departure_time
        if ($date) {
            $newDate = $date.AddDays($daysOffset)
            $tour.departure_time = Format-Date $newDate "ISO"
        }
    }
    
    # Cập nhật return_time
    if ($tour.return_time) {
        $date = Parse-Date $tour.return_time
        if ($date) {
            $newDate = $date.AddDays($daysOffset)
            $tour.return_time = Format-Date $newDate "ISO"
        }
    }
    
    # Cập nhật created_at và updated_at
    if ($tour.created_at) {
        $date = Parse-Date $tour.created_at
        if ($date) {
            $newDate = $date.AddDays($daysOffset)
            $tour.created_at = Format-Date $newDate "MySQL"
        }
    }
    
    if ($tour.updated_at) {
        # updated_at luôn là thời gian hiện tại
        $tour.updated_at = Format-Date $now "MySQL"
    }
}

# Chuyển đổi lại thành JSON với format đẹp
$updatedJson = $tours | ConvertTo-Json -Depth 100

# Ghi file với UTF8 encoding (không BOM)
$utf8NoBom = New-Object System.Text.UTF8Encoding $false
[System.IO.File]::WriteAllText($jsonPath, $updatedJson, $utf8NoBom)

Write-Host "Cap nhat thanh congcong" -ForegroundColor Green
Write-Host "Tat ca ngay da phu hop voi thoi gian hien taitai: $($now.ToString('yyyy-MM-dd HH:mm:ss'))" -ForegroundColor Green
