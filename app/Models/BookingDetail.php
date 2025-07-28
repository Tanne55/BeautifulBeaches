<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_booking_id',
        'full_name',
        'age',
        'gender',
        'special_requests',
    ];

    public function tourBooking()
    {
        return $this->belongsTo(TourBooking::class);
    }

    public function getGenderTextAttribute()
    {
        return match($this->gender) {
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác',
            default => 'Không xác định'
        };
    }
} 