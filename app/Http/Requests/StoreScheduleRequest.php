<?php

namespace App\Http\Requests;

use App\Models\Movie;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool {
        return $this->user()->can('create', Schedule::class);
    }

    public function rules(): array {
        return [
            'movie_id' => ['required', 'exists:movies,id'],
            'cinema_id' => ['required', 'exists:cinemas,id'],
            'show_date' => ['required', 'date', 'after_or_equal:today'],
            'show_time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function withValidator($validator): void {
        $validator->after(function ($validator) {
            if (! $this->filled(['movie_id', 'cinema_id', 'show_date', 'show_time'])) {
                return;
            }

            $movie = Movie::find($this->movie_id);
            if (! $movie) {
                return;
            }

            $newStart = Carbon::parse("{$this->show_date} {$this->show_time}");
            $newEnd = $newStart->copy()->addMinutes($movie->duration + 30);

            $overlapping = Schedule::with('movie')
                ->where('cinema_id', $this->cinema_id)
                ->whereDate('show_date', $newStart->toDateString())
                ->get()
                ->contains(function (Schedule $schedule) use ($newStart, $newEnd) {
                    $existingStart = Carbon::parse($schedule->show_date->format('Y-m-d') . ' ' . $schedule->show_time);
                    $existingEnd = $existingStart->copy()->addMinutes(($schedule->movie->duration ?? 0) + 30);

                    return $newStart->lt($existingEnd) && $newEnd->gt($existingStart);
                });

            if ($overlapping) {
                $validator->errors()->add('show_time', 'Jadwal ini bentrok dengan jadwal film lain di bioskop yang sama.');
            }
        });
    }
}