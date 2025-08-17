<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourBookingGroup extends Model
{
    use HasFactory;

    /**
     * Đảm bảo booking_ids luôn là mảng phẳng khi truy cập từ view hoặc controller
     */
    public function getBookingIdsAttribute($value)
    {
        $ids = $value;
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        return Arr::flatten($ids ?? []);
    }

    protected $fillable = [
        'tour_id',
        'group_code',
        'total_people',
        'booking_ids',
        'total_amount',
        'selected_departure_date',
    ];

    protected $casts = [
        'booking_ids' => 'array',
        'total_amount' => 'float',
        'selected_departure_date' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Thiết lập quan hệ với các TourBooking
     * Cách tốt nhất là không sử dụng relation này trực tiếp
     * Thay vào đó nên dùng getBookingsAttribute()
     */
    public function bookings()
    {
        // Để tránh lỗi, chúng ta không thể dùng booking_ids trực tiếp trong Eloquent relation
        // Luôn luôn sử dụng getBookingsAttribute() thay vì relation này
        return $this->hasOne(TourBooking::class, 'tour_id', 'tour_id')
            ->where('id', '0'); // Trả về empty relation để tránh lỗi
    }

    public function getBookingListAttribute()
    {
        $ids = Arr::flatten($this->booking_ids ?? []);
        if (empty($ids)) {
            return collect(); // Trả về empty collection
        }
        return TourBooking::whereIn('id', $ids)->get();
    }

    public function getTotalTicketsAttribute()
    {
        // Sử dụng booking_list thay vì bookings để tránh conflict
        return $this->booking_list->sum(function ($booking) {
            return $booking->tickets()->count();
        });
    }

    public function getTotalAmountAttribute()
    {
        // Sử dụng booking_list thay vì bookings để tránh conflict  
        return $this->booking_list->sum('total_amount');
    }
} 