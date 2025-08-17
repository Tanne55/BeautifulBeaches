<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function reviews()
    {
        return $this->hasMany(ReviewTour::class);
    }

    public function getCurrentPriceAttribute()
    {
        $today = now()->format('Y-m-d');
        $price = $this->prices()->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
        return $price ? $price->price : 0;
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
