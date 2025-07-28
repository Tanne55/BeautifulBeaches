<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id',
        'dob',
        'nationality',
        'preferences',
    ];
    protected $casts = [
        'dob' => 'date',
        'preferences' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 