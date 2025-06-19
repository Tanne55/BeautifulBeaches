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
        'long_description',
        'long_description_2',
        'highlight_quote',
        'tags',
        'price',
        'original_price',
        'capacity',
        'duration',
        'rating',
        'reviews'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
    public function get(){
        
    }
}
