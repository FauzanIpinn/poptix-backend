<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'booking_code' => $this->booking_code,
            'status' => $this->status,
            'total_price' => (float) $this->total_price,
            'total_price_formatted' => 'Rp' . number_format($this->total_price, 0, ',', '.'),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'seats' => $this->whenLoaded('bookingSeats', function () {
                return $this->bookingSeats->map(fn ($bs) => $bs->seat->code);
            }),
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
