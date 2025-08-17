<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_booking_id',
        'ticket_code',
        'full_name',
        'email',
        'phone',
        'status',
        'unit_price',
    ];

    protected $casts = [
        'status' => 'string',
        'unit_price' => 'decimal:2',
    ];

    public function tourBooking()
    {
        return $this->belongsTo(TourBooking::class);
    }
} 