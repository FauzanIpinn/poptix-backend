<?php

namespace App\Http\Requests;

use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('user');
    }

    public function rules(): array
    {
        return [
            'schedule_id'     => ['required', 'integer', 'exists:schedules,id'],
            'seat_ids'        => ['required', 'array', 'min:1', 'max:6'],
            'seat_ids.*'      => ['integer', 'exists:seats,id', 'distinct'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
        ];
    }

    /**
     * Validasi tambahan: pastikan semua seat yang dipilih
     * memang milik cinema dari schedule yang bersangkutan.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Hanya jalankan jika validasi dasar sudah lolos
            if ($validator->errors()->has('schedule_id') || $validator->errors()->has('seat_ids')) {
                return;
            }

            $schedule = Schedule::find($this->schedule_id);
            if (! $schedule) {
                return;
            }

            $invalidSeatIds = Seat::whereIn('id', $this->seat_ids)
                ->where('cinema_id', '!=', $schedule->cinema_id)
                ->pluck('id')
                ->toArray();

            if (! empty($invalidSeatIds)) {
                $validator->errors()->add(
                    'seat_ids',
                    'Beberapa kursi yang kamu pilih tidak tersedia untuk jadwal ini.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'schedule_id.required' => 'Jadwal wajib dipilih.',
            'schedule_id.exists'   => 'Jadwal yang dipilih tidak valid.',
            'seat_ids.required'    => 'Pilih minimal 1 kursi.',
            'seat_ids.min'         => 'Pilih minimal 1 kursi.',
            'seat_ids.max'         => 'Maksimal 6 kursi per booking.',
            'seat_ids.*.exists'    => 'Salah satu kursi yang dipilih tidak valid.',
            'seat_ids.*.distinct'  => 'Tidak boleh memilih kursi yang sama lebih dari satu kali.',
        ];
    }
}