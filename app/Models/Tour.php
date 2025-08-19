<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'beach_id',
        'ceo_id',
        'title',
        'image',
        'capacity',
        'max_people',
        'duration_days',
        'status',
    ];

    public function beach()
    {
        return $this->belongsTo(Beach::class);
    }

    public function ceo()
    {
        return $this->belongsTo(User::class, 'ceo_id');
    }

    public function detail()
    {
        return $this->hasOne(TourDetail::class);
    }

    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    public function prices()
    {
        return $this->hasMany(TourPrice::class, 'tour_id');
    }


    public function images()
    {
        return $this->hasMany(TourImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(TourImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewTour::class);
    }

    public function getCurrentPriceAttribute()
    {
        $today = now();
        
        // Lấy price record có discount trong khoảng thời gian hiện tại
        $discountPrice = $this->prices()
            ->where('discount', '>', 0)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest()
            ->first();
        
        if ($discountPrice) {
            // Có discount trong khoảng thời gian: giá = price - (price * discount/100)
            return $discountPrice->price - ($discountPrice->price * $discountPrice->discount / 100);
        }
        
        // Không có discount hoặc ngoài khoảng thời gian: lấy price gốc
        $normalPrice = $this->prices()
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest()
            ->first();
        
        if (!$normalPrice) {
            $normalPrice = $this->prices()->latest()->first();
        }
        
        return $normalPrice ? $normalPrice->price : 0;
    }

    public function getCurrentPriceDetailsAttribute()
    {
        $today = now();
        
        // Lấy price record có discount trong khoảng thời gian hiện tại
        $discountPrice = $this->prices()
            ->where('discount', '>', 0)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest()
            ->first();
        
        if ($discountPrice) {
            $finalPrice = $discountPrice->price - ($discountPrice->price * $discountPrice->discount / 100);
            return [
                'original_price' => $discountPrice->price,
                'final_price' => $finalPrice,
                'discount' => $discountPrice->discount,
                'is_discounted' => true
            ];
        }
        
        // Không có discount: lấy price gốc
        $normalPrice = $this->prices()
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest()
            ->first();
        
        if (!$normalPrice) {
            $normalPrice = $this->prices()->latest()->first();
        }
        
        if (!$normalPrice) {
            return [
                'original_price' => 0,
                'final_price' => 0,
                'discount' => 0,
                'is_discounted' => false
            ];
        }
        
        return [
            'original_price' => $normalPrice->price,
            'final_price' => $normalPrice->price,
            'discount' => 0,
            'is_discounted' => false
        ];
    }

    public function getCurrentPriceObjectAttribute()
    {
        $today = now()->format('Y-m-d');
        $price = $this->prices()->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->latest()
            ->first();
        
        if (!$price) {
            // Nếu không có giá theo ngày, lấy giá gần nhất
            $price = $this->prices()->latest()->first();
        }
        
        return $price;
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
    }

    public function getAvailableSlotsAttribute()
    {
        $today = now()->format('Y-m-d');
        $inventory = $this->inventory()->where('date', $today)->first();
        $totalBooked = $this->bookings()->where('booking_date', $today)->sum('number_of_people');

        return $inventory ? $inventory->available_slots - $totalBooked : 0;
    }

    /**
     * Get available departure dates for this tour
     */
    public function getAvailableDepartureDatesAttribute()
    {
        return $this->detail ? $this->detail->available_dates : [];
    }

    /**
     * Check if tour has departure on specific date
     */
    public function hasDateAvailable($date)
    {
        return $this->detail ? $this->detail->isDateAvailable($date) : false;
    }

    /**
     * Get departure time for specific date
     */
    public function getDepartureTimeForDate($date)
    {
        return $this->detail ? $this->detail->getDepartureTimeForDate($date) : null;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }
}
