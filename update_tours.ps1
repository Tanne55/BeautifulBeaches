# Script to update tours.json - cập nhật tất cả ngày tháng dựa trên thời gian hiện tại
# Tương thích với PowerShell 5.1+ và PowerShell 7+

# Kiểm tra phiên bản PowerShell
$psVersion = $PSVersionTable.PSVersion.Major
Write-Host "PowerShell version: $psVersion" -ForegroundColor Cyan

$jsonPath = "P:\LaravelProject\BeautifulBeaches\database\Data\tours.json"

# Kiểm tra file tồn tại
if (-not (Test-Path $jsonPath)) {
    Write-Error "File not found: $jsonPath"
    exit 1
}

# Đọc file JSON với encoding phù hợp
try {
    if ($psVersion -ge 6) {
        # PowerShell 7+ - sử dụng -AsByteStream hoặc -Encoding utf8
        $jsonContent = Get-Content $jsonPath -Raw -Encoding utf8
    } else {
        # PowerShell 5.1 - sử dụng UTF8 encoding
        $jsonContent = Get-Content $jsonPath -Raw -Encoding UTF8
    }
} catch {
    # Fallback: đọc dưới dạng byte và decode
    $bytes = [System.IO.File]::ReadAllBytes($jsonPath)
    $jsonContent = [System.Text.Encoding]::UTF8.GetString($bytes)
}

# Parse JSON với error handling
try {
    $tours = $jsonContent | ConvertFrom-Json
} catch {
    Write-Error "Không thể parse JSON: $_"
    exit 1
}

# Lấy thời gian hiện tại
$now = Get-Date

# Hàm để parse date từ string (hỗ trợ cả format ISO và datetime MySQL)
function Parse-Date {
    param($dateString)
    
    if ([string]::IsNullOrWhiteSpace($dateString)) {
        return $null
    }
    
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
    
    if ($null -eq $date) {
        return $null
    }
    
    if ($format -eq "ISO") {
        return $date.ToString("yyyy-MM-ddTHH:mm:ss")
    }
    else {
        return $date.ToString("yyyy-MM-dd HH:mm:ss")
    }
}

# Duyệt qua từng tour
$tourCount = 0
foreach ($tour in $tours) {
    $tourCount++
    # Tìm ngày sớm nhất trong tour (để tính offset)
    $earliestDate = $null
    
    # Kiểm tra departure_dates
    if ($tour.departure_dates -and $tour.departure_dates.Count -gt 0) {
        foreach ($dateStr in $tour.departure_dates) {
            if ($null -ne $dateStr) {
                $date = Parse-Date $dateStr
                if ($null -ne $date -and ($null -eq $earliestDate -or $date -lt $earliestDate)) {
                    $earliestDate = $date
                }
            }
        }
    }
    
    # Kiểm tra departure_time nếu có
    if ($tour.departure_time) {
        $date = Parse-Date $tour.departure_time
        if ($null -ne $date -and ($null -eq $earliestDate -or $date -lt $earliestDate)) {
            $earliestDate = $date
        }
    }
    
    # Nếu không tìm thấy ngày nào, bỏ qua tour này
    if ($null -eq $earliestDate) {
        Write-Warning "Không tìm thấy ngày trong tour ID: $($tour.id)"
        continue
    }
    
    # Tính offset: đảm bảo ngày sớm nhất mới sẽ là ít nhất 3 ngày trong tương lai
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
            if ($null -ne $dateStr) {
                $date = Parse-Date $dateStr
                if ($null -ne $date) {
                    $newDate = $date.AddDays($daysOffset)
                    $updatedDates += Format-Date $newDate "ISO"
                }
                else {
                    $updatedDates += $dateStr
                }
            }
        }
        $tour.departure_dates = $updatedDates
    }
    
    # Cập nhật departure_time nếu có
    if ($tour.departure_time) {
        $date = Parse-Date $tour.departure_time
        if ($null -ne $date) {
            $newDate = $date.AddDays($daysOffset)
            $tour.departure_time = Format-Date $newDate "ISO"
        }
    }
    
    # Cập nhật return_time
    if ($tour.return_time) {
        $date = Parse-Date $tour.return_time
        if ($null -ne $date) {
            $newDate = $date.AddDays($daysOffset)
            $tour.return_time = Format-Date $newDate "ISO"
        }
    }
    
    # Cập nhật created_at và updated_at
    if ($tour.created_at) {
        $date = Parse-Date $tour.created_at
        if ($null -ne $date) {
            $newDate = $date.AddDays($daysOffset)
            $tour.created_at = Format-Date $newDate "MySQL"
        }
    }
    
    if ($tour.updated_at) {
        # updated_at luôn là thời gian hiện tại
        $tour.updated_at = Format-Date $now "MySQL"
    }
}

Write-Host "Đã xử lý $tourCount tours" -ForegroundColor Cyan

# Chuyển đổi lại thành JSON với format đẹp
# Sử dụng cách tương thích cho cả PowerShell 5.1 và 7+
try {
    # PowerShell 7+ hỗ trợ -AsArray và format tốt hơn
    if ($psVersion -ge 7) {
        $updatedJson = $tours | ConvertTo-Json -Depth 100 -AsArray
    } else {
        # PowerShell 5.1 - cần xử lý đặc biệt
        $updatedJson = $tours | ConvertTo-Json -Depth 100
        
        # PowerShell 5.1 có thể không format array đúng, cần fix
        if (-not $updatedJson.TrimStart().StartsWith('[')) {
            $updatedJson = "[`n" + $updatedJson + "`n]"
        }
    }
} catch {
    Write-Error "Không thể convert sang JSON: $_"
    exit 1
}

# Ghi file với UTF8 encoding (không BOM) - cách tương thích
try {
    $utf8NoBom = New-Object System.Text.UTF8Encoding $false
    [System.IO.File]::WriteAllText($jsonPath, $updatedJson, $utf8NoBom)
} catch {
    Write-Error "Không thể ghi file: $_"
    exit 1
}

Write-Host "Cap nhat thanh cong" -ForegroundColor Green
Write-Host "Tat ca ngay dat da phu hop: $($now.ToString('yyyy-MM-dd HH:mm:ss'))" -ForegroundColor Green
