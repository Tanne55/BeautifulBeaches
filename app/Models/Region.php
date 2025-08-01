<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $fillable = [
        'name',
        'country',
    ];
    public function beaches()
    {
        return $this->hasMany(Beach::class);
    }
} 