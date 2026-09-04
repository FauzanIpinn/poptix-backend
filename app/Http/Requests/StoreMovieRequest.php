<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return $this->user()->can('create', \App\Models\Movie::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'    => ['required', 'string', 'max:255'],
            'poster'   => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'synopsis' => ['required', 'string'],
            'genre'    => ['required', 'string', 'max:100'],
            'duration' => ['required', 'integer', 'min:1', 'max:600'],
            'rating'   => ['nullable', 'string', 'max:10'],
            'trailer'  => ['nullable', 'url'],
            'status'   => ['required', 'in:now_showing,coming_soon'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Judul film wajib diisi.',
            'poster.required'   => 'Poster film wajib diunggah.',
            'poster.image'      => 'File poster harus berupa gambar.',
            'poster.mimes'      => 'Format poster harus JPG, JPEG, PNG, atau WEBP.',
            'poster.max'        => 'Ukuran poster maksimal 2MB.',
            'synopsis.required' => 'Sinopsis film wajib diisi.',
            'genre.required'    => 'Genre film wajib diisi.',
            'duration.required' => 'Durasi film wajib diisi.',
            'duration.integer'  => 'Durasi harus berupa angka (dalam menit).',
            'duration.min'      => 'Durasi minimal 1 menit.',
            'duration.max'      => 'Durasi maksimal 600 menit (10 jam).',
            'trailer.url'       => 'URL trailer tidak valid.',
            'status.required'   => 'Status film wajib dipilih.',
            'status.in'         => 'Status film harus \'now_showing\' atau \'coming_soon\'.',
        ];
    }
}