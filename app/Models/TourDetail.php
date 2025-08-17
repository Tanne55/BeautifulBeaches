<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourDetail extends Model
{
    use HasFactory;
    protected $table = 'tour_details';
    protected $fillable = [
        'tour_id',
        'departure_dates',
        'return_time',
        'included_services',
        'excluded_services',
        'highlights',
    ];

    protected $casts = [
        'departure_dates' => 'array',
        'return_time' => 'datetime',
        'included_services' => 'array',
        'excluded_services' => 'array',
        'highlights' => 'array',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Check if a specific date is available for departure
     */
    public function isDateAvailable($date)
    {
        if (!$this->departure_dates) {
            return false;
        }
        
        $targetDate = is_string($date) ? $date : $date->format('Y-m-d\TH:i:s');
        
        foreach ($this->departure_dates as $departureDate) {
            if (strpos($departureDate, substr($targetDate, 0, 10)) === 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get all available dates (only dates, not times)
     */
    public function getAvailableDatesAttribute()
    {
        if (!$this->departure_dates) {
            return [];
        }

        return array_map(function($datetime) {
            return substr($datetime, 0, 10); // Extract YYYY-MM-DD part
        }, $this->departure_dates);
    }

    /**
     * Get departure time for a specific date
     */
    public function getDepartureTimeForDate($date)
    {
        if (!$this->departure_dates) {
            return null;
        }

        $targetDate = is_string($date) ? substr($date, 0, 10) : $date->format('Y-m-d');
        
        foreach ($this->departure_dates as $departureDate) {
            if (strpos($departureDate, $targetDate) === 0) {
                return $departureDate;
            }
        }
        
        return null;
    }
} 