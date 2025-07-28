<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancellationRequest extends Model
{
    protected $table = 'cancellation_requests';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'user_id',
        'cancellation_type',
        'cancelled_slots',
        'cancelled_detail_ids',
        'reason',
        'status',
        'reject_reason',
        'created_at',
    ];

    protected $casts = [
        'cancelled_detail_ids' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(TourBooking::class, 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
