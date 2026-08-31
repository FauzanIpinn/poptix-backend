<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = [
        'cinema_id',
        'studio_id',
        'row',
        'number',
    ];

    protected static function boot(): void {
        parent::boot();
        static::saving(function (Seat $seat) {
            if ($seat->studio_id && $seat->isDirty('studio_id')) {
                $seat->cinema_id = Studio::find($seat->studio_id)?->cinema_id ?? $seat->cinema_id;
            }
        });
    }

    public function cinema(): BelongsTo {
        return $this->belongsTo(Cinema::class);
    }

    public function studio(): BelongsTo {
        return $this->belongsTo(Studio::class);
    }
}
