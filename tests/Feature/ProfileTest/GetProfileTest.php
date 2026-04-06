<?php

namespace Tests\Feature\ProfileTest;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_only_list_active_profiles_without_status_field(): void
    {
        $activeProfile = Profile::factory()->active()->create();
        Profile::factory()->waiting()->create();
        Profile::factory()->inactive()->create();

        $response = $this->getJson('/api/profiles');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $activeProfile->id)
            ->assertJsonMissingPath('data.0.status');
    }

    public function test_authenticated_user_can_list_all_profiles_with_status_field(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $waitingProfile = Profile::factory()->waiting()->create();
        $activeProfile = Profile::factory()->active()->create();
        $inactiveProfile = Profile::factory()->inactive()->create();

        $response = $this->getJson('/api/profiles');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['id' => $waitingProfile->id, 'status' => Profile::STATUSES[Profile::STATUS_WAITING]])
            ->assertJsonFragment(['id' => $activeProfile->id, 'status' => Profile::STATUSES[Profile::STATUS_ACTIVE]])
            ->assertJsonFragment(['id' => $inactiveProfile->id, 'status' => Profile::STATUSES[Profile::STATUS_INACTIVE]]);
    }

    public function test_guest_can_show_profile_but_status_is_hidden(): void
    {
        $profile = Profile::factory()->inactive()->create();

        $response = $this->getJson("/api/profiles/{$profile->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $profile->id)
            ->assertJsonMissingPath('data.status');
    }

    public function test_authenticated_user_can_show_profile_with_status(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $profile = Profile::factory()->active()->create();

        $response = $this->getJson("/api/profiles/{$profile->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $profile->id)
            ->assertJsonPath('data.status', Profile::STATUSES[Profile::STATUS_ACTIVE]);
    }
}
