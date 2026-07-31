<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'poster',
        'synopsis',
        'genre',
        'duration',
        'rating',
        'trailer',
        'status',
    ];

    protected function casts(): array {
        return [
            'duration' => 'integer'
        ];
    }
    
    public function scopeComingSoon($query) {
        return $query->where('status', 'coming_soon');
    }

    public function schedules(): HasMany {
        return $this->hasMany(Schedule::class);
    }
}
