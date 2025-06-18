<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beach extends Model
{
    protected $fillable = ['name', 'location', 'description', 'image_url'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}
