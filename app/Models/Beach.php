<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beach extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'region_id',
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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function getRegionNameAttribute()
    {
        return $this->region ? $this->region->name : null;
    }

    public function get(){
        
    }

    protected static function booted()
    {
        static::deleting(function ($beach) {
            if ($beach->isForceDeleting()) {
                $beach->tours()->forceDelete();
            } else {
                $beach->tours()->delete();
            }
        });
        static::restoring(function ($beach) {
            $beach->tours()->withTrashed()->restore();
        });
    }
}
