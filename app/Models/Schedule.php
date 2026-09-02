<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $appends = ['available_seats_count'];

    protected $fillable = [
        'movie_id',
        'studio_id',
        'cinema_id',
        'show_date',
        'show_time',
        'price',
    ];

    protected function casts(): array {
        return [
            'show_date' => 'date',
            'price'     => 'decimal:2',
        ];
    }

    protected static function boot(): void {
        parent::boot();
        static::saving(function (Schedule $schedule) {
            if ($schedule->studio_id && $schedule->isDirty('studio_id')) {
                $schedule->cinema_id = Studio::find($schedule->studio_id)?->cinema_id ?? $schedule->cinema_id;
            }
        });
    }

    public function movie(): BelongsTo {
        return $this->belongsTo(Movie::class);
    }

    public function studio(): BelongsTo {
        return $this->belongsTo(Studio::class);
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
    
    protected function availableSeatsCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalSeats = \Illuminate\Support\Facades\Cache::remember(
                    "studio:{$this->studio_id}:seats-count",
                    now()->addHours(6),
                    fn () => $this->studio()->withCount('seats')->first()->seats_count ?? 0
                );

                $bookedSeats = \Illuminate\Support\Facades\Cache::remember(
                    "schedule:{$this->id}:booked-seat-ids",
                    now()->addSeconds(10),
                    fn () => $this->bookingSeats()
                        ->whereHas('booking', fn ($q) => $q->active())
                        ->pluck('seat_id')
                        ->all()
                );

                return max(0, $totalSeats - count($bookedSeats));
            }
        );
    }
}