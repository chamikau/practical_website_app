<?php

namespace Tests\Feature;

use App\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterAndSubscribeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        DB::statement('PRAGMA foreign_keys = ON;');
    }

    /** @test */
    public function test_user_register_and_subscribe()
    {
        $websites = Website::factory()->count(2)->create();

        $response = $this->postJson('/api/register-subscribe', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'website_ids' => $websites->pluck('id')->toArray(),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function user_can_register_and_subscribe_to_websites()
    {
        $websites = Website::factory()->count(2)->create();

        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'website_ids' => $websites->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/register-subscribe', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email']
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('subscribers', ['email' => 'test@example.com']);

        foreach ($websites as $website) {
            $this->assertDatabaseHas('subscriber_website', [
                'website_id' => $website->id,
            ]);
        }
    }

    /** @test */
    public function registration_requires_at_least_one_website_subscription()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'website_ids' => []
        ];

        $response = $this->postJson('/api/register-subscribe', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('website_ids');
    }

    /** @test */
    public function password_must_match_confirmation()
    {
        $website = Website::factory()->create();

        $payload = [
            'name' => 'Mismatch Password User',
            'email' => 'mismatch@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong_password',
            'website_ids' => [$website->id],
        ];

        $response = $this->postJson('/api/register-subscribe', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }
}
