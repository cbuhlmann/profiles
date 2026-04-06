<?php

namespace Tests\Feature\ProfileTest;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PutProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_profile(): void
    {
        $profile = Profile::factory()->active()->create();

        $this->putJson("/api/profiles/{$profile->id}", [
            'firstName' => 'Jane',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $profile = Profile::factory()->waiting()->create();

        $response = $this->putJson("/api/profiles/{$profile->id}", [
            'firstName' => 'Updated',
            'status' => Profile::STATUS_ACTIVE,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $profile->id)
            ->assertJsonPath('data.firstName', 'Updated')
            ->assertJsonPath('data.status', Profile::STATUSES[Profile::STATUS_ACTIVE]);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'firstName' => 'Updated',
            'status' => Profile::STATUS_ACTIVE,
        ]);
    }

    public function test_authenticated_user_gets_not_found_when_updating_non_existing_profile(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->putJson('/api/profiles/999999', [
            'firstName' => 'Updated',
            'status' => Profile::STATUS_ACTIVE,
        ]);

        $response->assertNotFound();
    }

    public function test_update_requires_valid_status_when_present(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $profile = Profile::factory()->active()->create();

        $response = $this->putJson("/api/profiles/{$profile->id}", [
            'status' => 999,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }
}
