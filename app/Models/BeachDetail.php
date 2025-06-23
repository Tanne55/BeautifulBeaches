<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeachDetail extends Model
{
    use HasFactory;
    protected $table = 'beach_details';
    protected $fillable = [
        'beach_id',
        'long_description',
        'highlight_quote',
        'long_description2',
        'tags',
    ];

    public function beach()
    {
        return $this->belongsTo(Beach::class);
    }
} 