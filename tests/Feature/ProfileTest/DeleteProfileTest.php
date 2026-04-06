<?php

namespace Tests\Feature\ProfileTest;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_delete_profile(): void
    {
        $profile = Profile::factory()->active()->create();

        $this->deleteJson("/api/profiles/{$profile->id}")
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_delete_profile(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $profile = Profile::factory()->active()->create();

        $this->deleteJson("/api/profiles/{$profile->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }

    public function test_authenticated_user_gets_not_found_when_deleting_non_existing_profile(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $this->deleteJson('/api/profiles/999999')
            ->assertNotFound();
    }
}
