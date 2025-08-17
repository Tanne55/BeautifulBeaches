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
        'selected_departure_date',
        'number_of_people',
        'status',
        'note',
        'booking_code',
        'total_amount',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'selected_departure_date' => 'date',
        'total_amount' => 'decimal:2',
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

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function promotionUsages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function cancellationRequests()
    {
        return $this->hasMany(CancellationRequest::class, 'booking_id');
    }

    public function isPastTour()
    {
        if (!$this->selected_departure_date) {
            return false;
        }
        return now()->gt($this->selected_departure_date);
    }

    public function isUpcoming()
    {
        if (!$this->selected_departure_date) {
            return false;
        }
        return now()->lt($this->selected_departure_date);
    }

    public function canBeCancelled()
    {
        // Có thể hủy nếu status cho phép và chưa quá ngày khởi hành
        $allowedStatuses = ['pending', 'confirmed'];
        $isStatusValid = in_array($this->status, $allowedStatuses);
        $isNotPast = $this->isUpcoming();
        
        return $isStatusValid && $isNotPast;
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->selected_departure_date) {
            return null;
        }
        
        $days = now()->diffInDays($this->selected_departure_date, false);
        return $days > 0 ? $days : 0;
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'grouped' => 'Đã nhóm',
            'cancelled' => 'Đã hủy',
            'partially_cancelled' => 'Hủy một phần',
            default => 'Không xác định'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'grouped' => 'info',
            'cancelled' => 'danger',
            'partially_cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    public function getTotalAmountAttribute()
    {
        // Nếu đã có giá trị trong DB thì ưu tiên lấy
        if (!is_null($this->attributes['total_amount'])) {
            return (float) $this->attributes['total_amount'];
        }
        // Nếu chưa có thì tính lại
        if (!$this->tour) return 0;
        $price = $this->tour->prices()
            ->where('start_date', '<=', $this->booking_date)
            ->where('end_date', '>=', $this->booking_date)
            ->first();
        $basePrice = $price ? $price->price : 0;
        return $basePrice * $this->number_of_people;
    }
}
