<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cinema extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'brand',
        'city',
        'address',
    ];

    public function scopeBrand ($query, $brand) {
        return $query->where('brand', $brand);
    }

    public function scopeCity ($query, $city) {
        return $query->where('city', $city);
    }

    public function schedules(): HasMany {
        return $this->HasMany(Schedule::class);
    }

    public function studios(): HasMany {
        return $this->hasMany(Studio::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
