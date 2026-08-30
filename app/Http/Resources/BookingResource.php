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
            'id'                    => $this->id,
            'booking_code'          => $this->booking_code,
            'status'                => $this->status,
            'total_price'           => (float) $this->total_price,
            'total_price_formatted' => 'Rp' . number_format($this->total_price, 0, ',', '.'),
            'expires_at'            => $this->expires_at?->toIso8601String(),

            // Field pembayaran — hanya tersedia setelah proses checkout
            'payment_type'          => $this->payment_type,
            'paid_at'               => $this->paid_at?->toIso8601String(),

            // Snap token untuk Midtrans — hanya ditampilkan jika tersedia
            'snap_token'            => $this->when(
                $this->status === 'pending' && $this->snap_token,
                $this->snap_token
            ),

            // Relasi — hanya di-include jika sudah di-eager load
            'booking_seats' => BookingSeatResource::collection(
                $this->whenLoaded('bookingSeats')
            ),
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'user'     => new UserResource($this->whenLoaded('user')),

            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
