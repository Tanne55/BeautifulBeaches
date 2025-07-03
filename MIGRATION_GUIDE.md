# Migration Guide - Beautiful Beaches

## Tự động cập nhật timestamp trước khi migrate

Để đảm bảo dữ liệu không bị outdated khi migrate, chúng ta đã tạo các script tự động cập nhật timestamp.

### Cách sử dụng:

#### 1. Sử dụng script tự động (Khuyến nghị)

**Windows (Batch):**
```bash
update_and_migrate.bat
```

**Windows (PowerShell):**
```powershell
.\update_and_migrate.ps1
```

#### 2. Chạy từng bước thủ công

**Bước 1: Cập nhật timestamp**
```bash
php pre_migrate_update.php
```

**Bước 2: Chạy migration**
```bash
php artisan migrate
```

**Bước 3: Chạy seeder**
```bash
php artisan db:seed
```

### Các file được tạo:

1. **`pre_migrate_update.php`** - Script chính để cập nhật timestamp
2. **`update_tours_dates.php`** - Script chỉ cập nhật tours.json
3. **`update_and_migrate.bat`** - Batch script cho Windows
4. **`update_and_migrate.ps1`** - PowerShell script cho Windows

### Lợi ích:

- ✅ Tự động cập nhật `created_at` và `updated_at` với thời gian hiện tại
- ✅ Không cần chỉnh sửa thủ công từng file
- ✅ Dữ liệu luôn fresh khi migrate
- ✅ Hỗ trợ cả Windows và PowerShell

### Lưu ý:

- Script sẽ cập nhật tất cả các file trong thư mục `database/Data/`
- Timestamp sẽ được set theo thời gian hiện tại của máy
- Đảm bảo chạy script trước khi migrate để có dữ liệu mới nhất 