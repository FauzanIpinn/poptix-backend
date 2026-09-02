<?php

namespace App\Http\Requests;

use App\Models\Studio;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Studio::class);
    }

    public function rules(): array
    {
        return [
            'cinema_id'     => ['required', 'exists:cinemas,id'],
            'name'          => ['required', 'string', 'max:100'],
            'rows'          => ['nullable', 'array'],
            'rows.*'        => ['string', 'max:1'],
            'seats_per_row' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'cinema_id.required' => 'Bioskop wajib dipilih.',
            'cinema_id.exists'   => 'Bioskop yang dipilih tidak valid.',
            'name.required'      => 'Nama studio wajib diisi.',
        ];
    }
}