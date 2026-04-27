<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_logs';
    protected $guarded = [];
    protected $casts = [
        'raw_response' => 'array',
        'paid_at'      => 'datetime',
        'expired_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cek apakah pembayaran berhasil
    public function isSettled(): bool
    {
        return in_array($this->status, ['settlement', 'capture']);
    }

    // Cek apakah kadaluarsa
    public function isExpired(): bool
    {
        return in_array($this->status, ['expire', 'cancel', 'deny', 'failure']);
    }

    // Badge warna untuk status
    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'settlement', 'capture' => 'success',
            'pending'               => 'warning',
            'expire', 'cancel'      => 'secondary',
            'deny', 'failure'       => 'danger',
            default                 => 'info',
        };
    }

    // Label status dalam Bahasa Indonesia
    public function statusLabel(): string
    {
        return match($this->status) {
            'settlement' => 'Berhasil',
            'capture'    => 'Berhasil (Capture)',
            'pending'    => 'Menunggu Pembayaran',
            'expire'     => 'Kadaluarsa',
            'cancel'     => 'Dibatalkan',
            'deny'       => 'Ditolak',
            'failure'    => 'Gagal',
            'refund'     => 'Dikembalikan',
            default      => ucfirst($this->status),
        };
    }
}
