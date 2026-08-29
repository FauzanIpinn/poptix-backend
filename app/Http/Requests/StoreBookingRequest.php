<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('user');
    }

    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'seat_ids' => ['required', 'array', 'min:1', 'max:6'],
            'seat_ids.*' => ['integer', 'exists:seats,id'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'seat_ids.max' => 'Maksimal 6 kursi per booking.',
            'seat_ids.min' => 'Pilih minimal 1 kursi.',
        ];
    }
}