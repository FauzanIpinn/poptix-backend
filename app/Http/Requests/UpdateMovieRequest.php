<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return $this->user()->can('update', $this->route('movie'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'synopsis' => ['required', 'string'],
            'genre' => ['required', 'string', 'max:100'],
            'duration' => ['required', 'integer', 'min:1'],
            'rating' => ['nullable', 'string', 'max:10'],
            'trailer' => ['nullable', 'url'],
            'status' => ['required', 'in:now_showing,coming_soon'],
        ];
    }
}
