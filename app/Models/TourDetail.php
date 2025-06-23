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
        'departure_time',
        'return_time',
        'included_services',
        'excluded_services',
        'highlights',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
} 