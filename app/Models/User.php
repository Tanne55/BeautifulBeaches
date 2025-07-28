<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Review;
use App\Models\UserProfile;
use App\Models\UserBan;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
        'language',
        'last_login',
        'email_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'email_verified' => 'boolean',
        ];
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, 'ceo_id');
    }

    public function tourBookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    public function supportRequests()
    {
        return $this->hasMany(SupportRequest::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCEO()
    {
        return $this->role === 'ceo';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function reviewBeaches()
    {
        return $this->hasMany(\App\Models\ReviewBeach::class);
    }

    public function reviewTours()
    {
        return $this->hasMany(\App\Models\ReviewTour::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function bans()
    {
        return $this->hasMany(UserBan::class);
    }

    public function isCurrentlyBanned()
    {
        $now = now();
        return $this->bans()->where('start_date', '<=', $now)->where(function($q) use ($now) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
        })->exists();
    }
}
