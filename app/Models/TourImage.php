<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'image_url',
        'alt_text',
        'caption',
        'is_primary',
        'sort_order',
        'image_type',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function getFullImageUrlAttribute()
    {
        // Nếu đã là URL đầy đủ thì trả về, nếu không thì tạo asset URL
        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }
        return asset('storage/' . $this->image_url);
    }

    // Scope để lấy ảnh chính
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Scope để sắp xếp theo thứ tự
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    // Scope để lấy theo loại ảnh
    public function scopeByType($query, $type)
    {
        return $query->where('image_type', $type);
    }
} 