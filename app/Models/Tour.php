<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = ['ceo_id', 'beach_id', 'title', 'price', 'schedule', 'description'];

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
}
