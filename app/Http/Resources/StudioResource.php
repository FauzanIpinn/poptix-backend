<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudioResource extends JsonResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cinema' => new CinemaResource($this->whenLoaded('cinema')),
            'seats' => SeatResource::collection($this->whenLoaded('seats')),
        ];
    }
}