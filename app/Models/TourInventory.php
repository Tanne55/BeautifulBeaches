<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourInventory extends Model
{
    use HasFactory;

    protected $table = 'tour_inventory';

    protected $fillable = [
        'tour_id',
        'date',
        'available_slots',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function getBookedSlotsAttribute()
    {
        return $this->tour->bookings()
                         ->where('booking_date', $this->date)
                         ->sum('number_of_people');
    }

    public function getRemainingSlotsAttribute()
    {
        return $this->available_slots - $this->booked_slots;
    }
} 