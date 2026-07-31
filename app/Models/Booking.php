<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'schedule_id',
        'total_price',
        'status',
        'expires_at',
    ];

    protected function casts(): array {
        return [
            'total_price' => 'decimal:2',
            'expires_at' => 'datetime',
        ];
    }

    protected static function boot(): void {
        parent::boot();
        static::creating(function (Booking $booking) {
            $booking->booking_code = $booking->booking_code ?? static::generateBookingCode();
        });
    }

    public static function generateBookingCode(): string {
        do {
            $code = 'PPX-' . strtoupper(Str::random(6));
        } while (static::where('booking_code', $code)->exists());

        return $code;
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo {
        return $this->belongsTo(Schedule::class);
    }

    public function bookingSeats(): HasMany {
        return $this->hasMany(BookingSeat::class);
    }

    public function scopePending($query) {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query) {
        return $query->where('status', 'paid');
    }
}