<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'start_date',
        'end_date',
        'price',
        'discount',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }
} 