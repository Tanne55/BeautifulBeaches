# Beautiful Beaches - Data Update & Migration Script
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Beautiful Beaches - Data Update & Migration" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Step 1: Updating data files with current timestamps..." -ForegroundColor Yellow
php pre_migrate_update.php

Write-Host ""
Write-Host "Step 2: Running database migrations..." -ForegroundColor Yellow
php artisan migrate

Write-Host ""
Write-Host "Step 3: Running seeders..." -ForegroundColor Yellow
php artisan db:seed

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Migration completed successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

Read-Host "Press Enter to continue" 