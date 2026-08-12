<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return $this->user()->can('update', $this->route('schedule'));
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'integer', 'exists:movies,id'],
            'cinema_id' => ['required', 'integer', 'exists:cinemas,id'],
            'show_date' => ['required', 'date'],
            'show_time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
