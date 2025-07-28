<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionUsage extends Model
{
    use HasFactory;

    protected $table = 'promotion_usages';

    protected $fillable = [
        'promotion_id',
        'user_id',
        'tour_booking_id',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tourBooking()
    {
        return $this->belongsTo(TourBooking::class);
    }
} 