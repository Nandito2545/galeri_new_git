<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription',
        'subscription_ends_at',
        'foto_profil',
        'no_telepon',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
    ];

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class)->orderBy('created_at', 'desc');
    }

    public function isPremium()
    {
        return $this->subscription === 'premium' && $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function isSubscriptionExpiringSoon($days = 7)
    {
        if (!$this->isPremium()) {
            return false;
        }
        return $this->subscription_ends_at->diffInDays(now()) <= $days;
    }
}