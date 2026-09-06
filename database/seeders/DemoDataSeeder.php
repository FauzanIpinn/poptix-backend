<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Demo Biasa
        User::firstOrCreate(
            ['email' => 'user@poptix.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        )->assignRole('user');

        // 2. Data Film
        $movies = [
            [
                'title' => 'Dune: Part Two',
                'genre' => 'Sci-Fi, Adventure',
                'duration' => 166,
                'rating' => '13+',
                'status' => 'now_showing',
                'synopsis' => 'Paul Atreides bersatu dengan Chani dan suku Fremen untuk membalas dendam terhadap para konspirator yang telah menghancurkan keluarganya.',
                'poster' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?q=80&w=800&auto=format&fit=crop',
                'trailer' => 'https://www.youtube.com/watch?v=Way9Dexny3w',
            ],
            [
                'title' => 'Oppenheimer',
                'genre' => 'Biography, Drama, History',
                'duration' => 180,
                'rating' => '17+',
                'status' => 'now_showing',
                'synopsis' => 'Kisah fisikawan teoretis J. Robert Oppenheimer yang memimpin Manhattan Project dalam pengembangan bom atom pertama di dunia.',
                'poster' => 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?q=80&w=800&auto=format&fit=crop',
                'trailer' => 'https://www.youtube.com/watch?v=uYPbbksJxIg',
            ],
            [
                'title' => 'Deadpool & Wolverine',
                'genre' => 'Action, Comedy, Sci-Fi',
                'duration' => 128,
                'rating' => '17+',
                'status' => 'now_showing',
                'synopsis' => 'Wade Wilson yang santai terpaksa kembali beraksi bersama Wolverine yang enggan dalam misi menyelamatkan multisemesta.',
                'poster' => 'https://images.unsplash.com/photo-1509281373149-e957c6296406?q=80&w=800&auto=format&fit=crop',
                'trailer' => 'https://www.youtube.com/watch?v=73_1biulkYk',
            ],
            [
                'title' => 'Spider-Man: Beyond the Spider-Verse',
                'genre' => 'Animation, Action, Adventure',
                'duration' => 140,
                'rating' => 'SU',
                'status' => 'coming_soon',
                'synopsis' => 'Petualangan pamungkas Miles Morales melintasi multisemesta untuk menyelamatkan semua orang yang dia cintai.',
                'poster' => 'https://images.unsplash.com/photo-1635805737707-575885ab0820?q=80&w=800&auto=format&fit=crop',
                'trailer' => 'https://www.youtube.com/watch?v=cqGjhVJWtEg',
            ],
        ];

        foreach ($movies as $m) {
            Movie::firstOrCreate(['title' => $m['title']], $m);
        }

        // 3. Bioskop
        $cinemas = [
            [
                'name' => 'Grand Indonesia XXI',
                'brand' => 'XXI',
                'city' => 'Jakarta Pusat',
                'address' => 'Grand Indonesia Mall Lt. 8, Jl. M.H. Thamrin No.1',
            ],
            [
                'name' => 'Pacific Place CGV',
                'brand' => 'CGV',
                'city' => 'Jakarta Selatan',
                'address' => 'Pacific Place Mall Lt. 6, SCBD, Jl. Jend. Sudirman',
            ],
        ];

        foreach ($cinemas as $c) {
            Cinema::firstOrCreate(['name' => $c['name']], $c);
        }

        // 4. Jadwal Tayang (Schedules) - Batch
        $moviesInDb = Movie::where('status', 'now_showing')->get();
        $cinemasInDb = Cinema::with('studios')->get();

        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $times = ['13:00:00', '16:30:00', '19:45:00'];

        foreach ($moviesInDb as $movie) {
            foreach ($cinemasInDb as $cinema) {
                $studio = $cinema->studios->first();
                if (! $studio) {
                    continue;
                }

                foreach ([$today, $tomorrow] as $date) {
                    foreach ($times as $time) {
                        $exists = Schedule::where('movie_id', $movie->id)
                            ->where('studio_id', $studio->id)
                            ->where('show_date', $date)
                            ->where('show_time', $time)
                            ->exists();

                        if (! $exists) {
                            Schedule::create([
                                'movie_id' => $movie->id,
                                'cinema_id' => $cinema->id,
                                'studio_id' => $studio->id,
                                'show_date' => $date,
                                'show_time' => $time,
                                'price' => 50000,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
