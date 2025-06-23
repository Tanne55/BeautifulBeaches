<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beach extends Model
{
    protected $fillable = [
        'region',
        'image',
        'title',
        'short_description',
    ];

    public function reviews()
    {
        return $this->hasMany(ReviewBeach::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function detail()
    {
        return $this->hasOne(BeachDetail::class);
    }

    public function get(){
        
    }
}
