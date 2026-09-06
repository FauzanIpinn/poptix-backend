<?php

namespace Tests\Feature;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        return $admin;
    }

    public function test_admin_bisa_akses_halaman_index_dan_create_cinema(): void {
        $admin = $this->createAdmin();
        $cinema = Cinema::factory()->create(['name' => 'Senayan XXI']);

        $this->actingAs($admin)
            ->get(route('admin.cinemas.index'))
            ->assertStatus(200)
            ->assertSee('Senayan XXI');

        $this->actingAs($admin)
            ->get(route('admin.cinemas.create'))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.cinemas.edit', $cinema))
            ->assertStatus(200)
            ->assertSee('Senayan XXI');
    }

    public function test_admin_bisa_tambah_dan_update_cinema(): void {
        $admin = $this->createAdmin();

        $postResponse = $this->actingAs($admin)->post(route('admin.cinemas.store'), [
            'name' => 'PIM XXI',
            'brand' => 'XXI',
            'city' => 'Jakarta Selatan',
            'address' => 'Jl. Metro Pondok Indah',
        ]);
        $postResponse->assertRedirect(route('admin.cinemas.index'));
        $this->assertDatabaseHas('cinemas', ['name' => 'PIM XXI']);

        $cinema = Cinema::where('name', 'PIM XXI')->first();

        $putResponse = $this->actingAs($admin)->put(route('admin.cinemas.update', $cinema), [
            'name' => 'Pondok Indah Mall XXI',
            'brand' => 'XXI',
            'city' => 'Jakarta Selatan',
            'address' => 'Jl. Metro Pondok Indah Blok III',
        ]);
        $putResponse->assertRedirect(route('admin.cinemas.index'));
        $this->assertDatabaseHas('cinemas', ['name' => 'Pondok Indah Mall XXI']);
    }

    public function test_admin_bisa_akses_halaman_edit_movie(): void {
        $admin = $this->createAdmin();
        $movie = Movie::factory()->create(['title' => 'Interstellar 2']);

        $this->actingAs($admin)
            ->get(route('admin.movies.edit', $movie))
            ->assertStatus(200)
            ->assertSee('Interstellar 2');
    }

    public function test_admin_bisa_akses_halaman_schedules(): void {
        $admin = $this->createAdmin();
        $movie = Movie::factory()->create();
        $cinema = Cinema::factory()->create();
        $studio = $cinema->studios()->first();

        $schedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'studio_id' => $studio->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.schedules.index'))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.schedules.create'))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.schedules.edit', $schedule))
            ->assertStatus(200);
    }
}
