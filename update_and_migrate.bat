@echo off
echo ========================================
echo Beautiful Beaches - Data Update & Migration
echo ========================================
echo.

echo Step 1: Updating data files with current timestamps...
php pre_migrate_update.php

echo.
echo Step 2: Running database migrations...
php artisan migrate

echo.
echo Step 3: Running seeders...
php artisan db:seed

echo.
echo ========================================
echo Migration completed successfully!
echo ========================================
pause 