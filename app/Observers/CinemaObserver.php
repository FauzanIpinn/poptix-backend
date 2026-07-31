<?php

namespace App\Observers;

use App\Models\Cinema;
use App\Models\Seat;

class CinemaObserver
{
    public function created(Cinema $cinema): void
    {
        $rows = ['A', 'B', 'C', 'D', 'E'];
        $seatsPerRow = 10;

        $seatsToInsert = [];

        foreach ($rows as $row) {
            for ($number = 1; $number <= $seatsPerRow; $number++) {
                $seatsToInsert[] = [
                    'cinema_id' => $cinema->id,
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