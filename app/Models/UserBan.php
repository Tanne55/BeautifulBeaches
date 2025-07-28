<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $table = 'user_bans';
    protected $fillable = [
        'user_id',
        'reason',
        'start_date',
        'end_date',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 