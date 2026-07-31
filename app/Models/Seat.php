<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = [
        'cinema_id',
        'row',
        'number',
    ];

    public function cinema(): BelongsTo {
        return $this->belongsTo(Cinema::class);
    }

    public function code(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn() => "{$this->row}{$this->number}"
        );
    }
}
