<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'property_id',
        'guest_name', 'guest_email', 'guest_phone',
        'checkin_date', 'checkout_date', 'nights', 'rooms', 'guests', 'special_request',
        'price_per_night', 'subtotal', 'tax_amount', 'total_amount',
        'payment_method', 'status',
        'midtrans_order_id', 'midtrans_snap_token', 'midtrans_transaction_id', 'midtrans_payment_type',
        'transfer_proof', 'transfer_uploaded_at',
        'paid_at', 'confirmed_at', 'confirmed_by', 'admin_notes',
        'expired_at',
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'checkout_date' => 'date',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expired_at' => 'datetime',
        'transfer_uploaded_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'price_per_night' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function property() { return $this->belongsTo(Property::class); }
    public function testimonial() { return $this->hasOne(Testimonial::class); }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast()
            && in_array($this->status, ['pending', 'waiting_payment']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'          => 'Menunggu Pembayaran',
            'waiting_payment'  => 'Menunggu Konfirmasi Transfer',
            'paid_unverified'  => 'Pembayaran Diproses',
            'confirmed'        => 'Dikonfirmasi',
            'completed'        => 'Selesai',
            'expired'          => 'Kadaluarsa',
            'cancelled'        => 'Dibatalkan',
            default            => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'          => 'yellow',
            'waiting_payment'  => 'orange',
            'paid_unverified'  => 'blue',
            'confirmed'        => 'green',
            'completed'        => 'teal',
            'expired'          => 'gray',
            'cancelled'        => 'red',
            default            => 'gray',
        };
    }

    public static function generateCode(): string
    {
        do {
            $code = 'NGINEPYUK' . strtoupper(uniqid());
        } while (self::where('booking_code', $code)->exists());
        return $code;
    }
}
