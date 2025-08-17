# Sửa lỗi "Indirect modification of overloaded property"

## Vấn đề:
- **Lỗi**: `Indirect modification of overloaded property App\Models\TourDetail::$departure_dates has no effect`
- **Nguyên nhân**: Khi property được cast thành array bởi Laravel, việc modification trực tiếp có thể gây lỗi

## Giải thích lỗi:
Laravel cast `departure_dates` thành array qua magic methods. Khi sử dụng functions như `reset()`, `foreach` với reference, hoặc array modification trực tiếp, PHP báo lỗi vì không thể modify overloaded property trực tiếp.

```php
// Code gây lỗi
return reset($this->departure_dates); // reset() có thể modify array
foreach ($this->departure_dates as $date) { // Có thể gây lỗi với casting
    // ...
}
```

## Giải pháp:
**Tạo copy local của property trước khi sử dụng**

### 1. Sửa TourDetail Model:

#### a) Accessor getFirstDepartureDateAttribute():
```php
// Trước (lỗi)
return reset($this->departure_dates);

// Sau (đúng)
$dates = $this->departure_dates;
return is_array($dates) && count($dates) > 0 ? $dates[0] : null;
```

#### b) Accessor getAvailableDatesAttribute():
```php
// Trước (có thể lỗi)
return array_map(function($datetime) {
    return substr($datetime, 0, 10);
}, $this->departure_dates);

// Sau (an toàn)
$dates = $this->departure_dates;
return array_map(function($datetime) {
    return substr($datetime, 0, 10);
}, $dates);
```

#### c) Method isDateAvailable():
```php
// Trước (có thể lỗi)
foreach ($this->departure_dates as $departureDate) {
    // ...
}

// Sau (an toàn)
$dates = $this->departure_dates;
foreach ($dates as $departureDate) {
    // ...
}
```

### 2. Sửa TourController:

```php
// Trước (có thể lỗi)
foreach ($tour->detail->departure_dates as $departureDate) {
    // ...
}

// Sau (an toàn)
$departureDates = $tour->detail->departure_dates;
foreach ($departureDates as $departureDate) {
    // ...
}
```

## Files đã sửa:

### 1. `app/Models/TourDetail.php`:
- ✅ `getFirstDepartureDateAttribute()`: Dùng array access thay vì `reset()`
- ✅ `getAvailableDatesAttribute()`: Tạo copy trước khi `array_map()`
- ✅ `isDateAvailable()`: Tạo copy trước khi loop
- ✅ `getDepartureTimeForDate()`: Tạo copy trước khi loop

### 2. `app/Http/Controllers/TourController.php`:
- ✅ Update status logic: Tạo copy trước khi foreach

## Best Practices cho Laravel Cast Properties:

### ✅ Đúng:
```php
// Tạo copy local
$array = $this->casted_property;
foreach ($array as $item) { /* ... */ }

// Sử dụng array access
$first = $this->casted_property[0] ?? null;

// Check before access
if (is_array($array) && count($array) > 0) {
    // safe access
}
```

### ❌ Tránh:
```php
// Direct modification functions
reset($this->casted_property);
array_shift($this->casted_property);
sort($this->casted_property);

// Direct foreach (có thể lỗi)
foreach ($this->casted_property as $item) { }
```

## Kết quả:
- ✅ Không còn lỗi "Indirect modification"
- ✅ All accessor methods hoạt động bình thường
- ✅ TourController update status logic ổn định
- ✅ Performance tốt (copy chỉ reference, không deep copy)
- ✅ Code an toàn hơn với null checks

## Lưu ý:
- Lỗi này thường xảy ra với Laravel cast properties (array, json, object)
- Luôn tạo copy local khi cần manipulate cast array
- PHP 8+ strict mode có thể báo lỗi này nhiều hơn
- Best practice: check null và type trước khi access
