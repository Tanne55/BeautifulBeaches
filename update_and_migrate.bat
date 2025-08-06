@echo off
echo ========================================
echo Beautiful Beaches - Data Update & Migration
echo ========================================
echo.

echo Step 1: Updating data files with current timestamps...

php update_tours_dates.php
php pre_migrate_update.php

echo.
echo Step 2: Running database migrations...and Running seeders...
php artisan migrate:fresh --seed

echo.
echo Step 3: Running vite...
npm run dev

echo.
echo Step 4: Running serve...
php artisan serve

echo.
echo ========================================
echo Migration completed successfully!
echo ========================================
pause 