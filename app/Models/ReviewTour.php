<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewTour extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'review_tour';
    protected $fillable = [
        'user_id',
        'tour_id',
        'rating',
        'comment',
        'guest_name',
        'guest_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
} 