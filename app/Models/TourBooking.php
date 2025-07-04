<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tour_id',
        'full_name',
        'contact_phone',
        'contact_email',
        'booking_date',
        'number_of_people',
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

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
