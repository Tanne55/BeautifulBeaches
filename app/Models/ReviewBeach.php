<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewBeach extends Model
{
    use HasFactory;
    protected $table = 'review_beach';
    protected $fillable = [
        'user_id',
        'beach_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beach()
    {
        return $this->belongsTo(Beach::class);
    }
} 