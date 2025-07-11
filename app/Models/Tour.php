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
        'price',
        'original_price',
        'capacity',
        'duration_days',
        'status',
    ];

    public function ceo()
    {
        return $this->belongsTo(User::class, 'ceo_id');
    }

    public function beach()
    {
        return $this->belongsTo(Beach::class);
    }

    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    public function detail()
    {
        return $this->hasOne(TourDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewTour::class);
    }
}
