<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'cinema_id',
        'show_date',
        'show_time',
        'price',
    ];

    protected function casts(): array {
        return [
            'show_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function movie(): BelongsTo {
        return $this->belongsTo(Movie::class);
    }

    public function cinema(): BelongsTo {
        return $this->belongsTo(Cinema::class);
    }

    public function bookings(): HasMany {
        return $this->hasMany(Booking::class);
    }

    public function bookingSeats(): HasMany {
        return $this->hasMany(BookingSeat::class);
    }
}
