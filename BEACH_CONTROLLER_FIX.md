# Sửa lỗi Column not found trong BeachController

## Vấn đề:
- Lỗi SQL: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'region_name' in 'where clause'`
- Nguyên nhân: Sử dụng sai tên cột trong bảng `regions`

## Phân tích:
- Trong model `Region`, tên cột thực tế là `name` không phải `region_name`
- Model `Beach` có accessor `getRegionNameAttribute()` để truy cập `$this->region->name`
- Controller và View sử dụng nhầm `region_name` thay vì `name`

## Các file đã sửa:

### 1. BeachController.php:
- **Line 66**: Sửa `$regionQuery->where('region_name', 'like', "%{$search}%");` 
  → `$regionQuery->where('name', 'like', "%{$search}%");`
- **Line 83**: Sửa `->orderBy('regions.region_name', $sortOrder)` 
  → `->orderBy('regions.name', $sortOrder)`

### 2. admin/beaches/index.blade.php:
- **Line 33**: Sửa `{{ $region ? $region->region_name : request('region_id') }}` 
  → `{{ $region ? $region->name : request('region_id') }}`
- **Line 77**: Sửa `{{ $region->region_name }}` 
  → `{{ $region->name }}`

## Cấu trúc đúng:
```php
// Model Region
class Region extends Model {
    protected $fillable = ['name', 'country']; // Tên cột là 'name'
}

// Model Beach có accessor
public function getRegionNameAttribute() {
    return $this->region ? $this->region->name : null;
}
```

## Kết quả:
- Lỗi SQL đã được sửa
- Search function hoạt động bình thường
- Filter theo region hoạt động
- Sort theo region hoạt động
- View hiển thị đúng tên region

## Kiểm tra:
- [x] PHP syntax check passed
- [x] Routes cached successfully  
- [x] No SQL column errors

Bây giờ trang admin/beaches có thể tìm kiếm và lọc bình thường mà không gặp lỗi SQL nữa.
