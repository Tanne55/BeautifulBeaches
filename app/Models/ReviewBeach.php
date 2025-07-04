<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewBeach extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'review_beach';
    protected $fillable = [
        'user_id',
        'beach_id',
        'rating',
        'comment',
        'guest_name',
        'guest_email',
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