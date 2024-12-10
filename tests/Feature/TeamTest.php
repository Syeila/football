<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Team;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TeamTest extends TestCase
{
    public function test_can_create_team()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/teams', [
            'name' => 'Team A',
            'logo' => 'https://example.com/logo.png',
            'founded_year' => 2000,
            'address' => 'Jl. Contoh No. 1',
            'city' => 'Jakarta',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'name',
                     'logo',
                     'founded_year',
                     'address',
                     'city',
                 ]);
    }

    public function test_can_update_team()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson("/api/teams/{$team->id}", [
            'name' => 'Updated Team',
            'logo' => 'https://example.com/updated_logo.png',
            'founded_year' => 2005,
            'address' => 'Jl. Baru No. 2',
            'city' => 'Bandung',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'name' => 'Updated Team',
                     'founded_year' => 2005,
                 ]);
    }

    public function test_can_delete_team()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/teams/{$team->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Team soft-deleted successfully',
                 ]);
    }
}

