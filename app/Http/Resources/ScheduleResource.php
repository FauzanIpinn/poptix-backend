<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'id'              => $this->id,
            'show_date'       => $this->show_date->format('Y-m-d'),
            'show_time'       => Carbon::parse($this->show_time)->format('H:i'),
            'price'           => (float) $this->price,
            'price_formatted' => 'Rp' . number_format($this->price, 0, ',', '.'),

            // Jumlah kursi tersedia — hanya muncul jika di-withCount('availableSeats') atau sejenisnya
            'available_seats_count' => $this->whenNotNull(
                $this->available_seats_count ?? null
            ),

            // Relasi — hanya di-include jika sudah di-eager load
            'movie'  => new MovieResource($this->whenLoaded('movie')),
            'cinema' => new CinemaResource($this->whenLoaded('cinema')),
        ];
    }
}
