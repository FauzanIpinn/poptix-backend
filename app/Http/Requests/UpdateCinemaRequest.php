<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCinemaRequest extends FormRequest
{
    public function authorize(): bool {
        return $this->user()->can('update', $this->route('cinema'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'in:XXI,CGV,Cinepolis'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
        ];
    }
}
