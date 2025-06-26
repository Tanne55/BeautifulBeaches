<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    protected $fillable = [
        'user_id',
        'tour_id',
        'full_name',
        'contact_phone',
        'contact_email',
        'booking_date',
        'status',
        'note',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
