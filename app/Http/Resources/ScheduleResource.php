<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'show_date' => $this->show_date->format('Y-m-d'),
            'show_time' => \Illuminate\Support\Carbon::parse($this->show_time)->format('H:i'),
            'price' => (float) $this->price,
            'price_formatted' => 'Rp' . number_format($this->price, 0, ',', '.'),
            'movie' => new MovieResource($this->whenLoaded('movie')),
            'cinema' => new CinemaResource($this->whenLoaded('cinema')),
        ];
    }
}
