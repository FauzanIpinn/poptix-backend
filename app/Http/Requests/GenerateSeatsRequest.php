<?php

namespace App\Http\Requests;

use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;

class GenerateSeatsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Seat::class);
    }

    public function rules(): array
    {
        return [
            'rows'          => ['required', 'array', 'min:1'],
            'rows.*'        => ['required', 'distinct', 'string', 'max:1'],
            'seats_per_row' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'rows.required'          => 'Minimal satu baris kursi wajib diisi (contoh: A, B, C).',
            'rows.*.distinct'        => 'Nama baris kursi tidak boleh ada yang duplikat.',
            'seats_per_row.required' => 'Jumlah kursi per baris wajib diisi.',
        ];
    }
}