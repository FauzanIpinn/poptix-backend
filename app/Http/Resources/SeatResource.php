<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
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
            'row' => $this->row,
            'number' => $this->number,
            'code' => $this->code, // accessor yang udah kita bikin di Tahap 5
            'is_booked' => $this->when(isset($this->is_booked), $this->is_booked)
        ];
    }
}
