<?php

namespace App\Observers;

use App\Models\Cinema;
use App\Models\Seat;
use App\Models\Studio;

class CinemaObserver
{
    public function created(Cinema $cinema): void {
        $studio = Studio::create([
            'cinema_id' => $cinema->id,
            'name' => 'Studio 1',
        ]);

        $rows = ['A', 'B', 'C', 'D', 'E'];
        $seatsPerRow = 10;

        $seatsToInsert = [];

        foreach ($rows as $row) {
            for ($number = 1; $number <= $seatsPerRow; $number++) {
                $seatsToInsert[] = [
                    'cinema_id' => $cinema->id,
                    'studio_id' => $studio->id,
                    'row' => $row,
                    'number' => $number,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seatsToInsert);
    }
}