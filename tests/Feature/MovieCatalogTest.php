<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_melihat_katalog_film(): void {
        $user = User::factory()->create();
        $user->assignRole('user');

        Movie::factory()->create(['title' => 'Avatar 3', 'status' => 'now_showing']);
        Movie::factory()->create(['title' => 'Inception 2', 'status' => 'coming_soon']);

        $response = $this->actingAs($user)->get('/movies');
        $response->assertStatus(200);
        $response->assertSee('Avatar 3');
        $response->assertDontSee('Inception 2');

        $responseComingSoon = $this->actingAs($user)->get('/movies?status=coming_soon');
        $responseComingSoon->assertStatus(200);
        $responseComingSoon->assertSee('Inception 2');
        $responseComingSoon->assertDontSee('Avatar 3');
    }
}
