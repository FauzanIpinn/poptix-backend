<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'poster' => $this->poster,
            'synopsis' => $this->synopsis,
            'genre' => $this->genre,
            'duration' => $this->duration,
            'duration_formatted' => floor($this->duration / 60) . 'h ' . ($this->duration % 60) . 'm',
            'rating' => $this->rating,
            'trailer' => $this->trailer,
            'status' => $this->status,
        ];
    }
}
