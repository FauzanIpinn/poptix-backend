<?php

namespace App\Http\Requests;

use App\Models\Movie;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool {
        return $this->user()->can('create', \App\Models\Schedule::class);
    }

    public function rules(): array {
        return [
            'movie_id' => ['required', 'exists:movies,id'],
            'cinema_id' => ['required', 'exists:cinemas,id'],
            'start_time' => ['required', 'date', 'after_or_equal:now',
                function ($attribute, $value, $fail) {
                    $movie = Movie::find($this->movie_id);
                    if (!$movie) return;

                    $newStartTime = Carbon::parse($value);
                    $newEndTime = $newStartTime->copy()->addMinutes($movie->duration + 30); 

                    $overlapping = Schedule::where('cinema_id', $this->cinema_id)
                        ->where(function ($query) use ($newStartTime, $newEndTime) {
                            $query->whereBetween('start_time', [$newStartTime, $newEndTime])
                                  ->orWhereBetween('end_time', [$newStartTime, $newEndTime])
                                  ->orWhere(function ($q) use ($newStartTime, $newEndTime) {
                                      $q->where('start_time', '<=', $newStartTime)
                                        ->where('end_time', '>=', $newEndTime);
                                  });
                        })->exists();

                    if ($overlapping) {
                        $fail('Jadwal ini bentrok dengan jadwal film lain di studio yang sama.');
                    }
                },
            ],
        ];
    }
}
