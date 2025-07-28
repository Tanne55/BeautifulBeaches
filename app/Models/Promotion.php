<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'start_date',
        'end_date',
        'applicable_tours',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'applicable_tours' => 'array',
    ];

    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function isActive()
    {
        $now = now();
        return $now->gte($this->start_date) && $now->lte($this->end_date);
    }

    public function isApplicableToTour($tourId)
    {
        if (!$this->applicable_tours) {
            return true; // Áp dụng cho tất cả tours
        }
        return in_array($tourId, $this->applicable_tours);
    }

    public function getUsageCountAttribute()
    {
        return $this->usages()->count();
    }
} 