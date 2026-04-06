<?php

namespace Tests\Feature\ProfileTest;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_store_profile(): void
    {
        $this->postJson('/api/profiles', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'status' => Profile::STATUS_ACTIVE,
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_store_profile(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $payload = [
            'firstName' => 'Alice',
            'lastName' => 'Martin',
            'image' => 'https://example.test/alice.jpg',
            'status' => Profile::STATUS_WAITING,
        ];

        $response = $this->postJson('/api/profiles', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.firstName', 'Alice')
            ->assertJsonPath('data.lastName', 'Martin')
            ->assertJsonPath('data.image', 'https://example.test/alice.jpg')
            ->assertJsonPath('data.status', Profile::STATUSES[Profile::STATUS_WAITING]);

        $this->assertDatabaseHas('profiles', [
            'firstName' => 'Alice',
            'lastName' => 'Martin',
            'image' => 'https://example.test/alice.jpg',
            'status' => Profile::STATUS_WAITING,
        ]);
    }

    public function test_store_requires_valid_payload(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->postJson('/api/profiles', [
            'firstName' => '',
            'lastName' => '',
            'status' => 999,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['firstName', 'lastName', 'status']);
    }
}
