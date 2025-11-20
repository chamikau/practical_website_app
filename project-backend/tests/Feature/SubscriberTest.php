<?php

namespace Tests\Feature;

use App\Domain\Subscribers\Models\Subscriber;
use App\Domain\Websites\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_new_subscriber_and_attach_websites()
    {
        $websites = Website::factory()->count(2)->create();

        $payload = [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'website_ids' => $websites->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/subscriber', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'subscriber' => [
                    'id',
                    'email',
                    'name',
                    'websites' => [
                        '*' => ['id', 'name', 'slug']
                    ],
                ],
            ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        $subscriber = Subscriber::where('email', 'john@example.com')->first();
        $this->assertCount(2, $subscriber->websites);
    }

    /** @test */
    public function show_returns_subscriber_websites()
    {
        $subscriber = Subscriber::factory()->create();
        $websites = Website::factory()->count(2)->create();
        $subscriber->websites()->attach($websites);

        $response = $this->getJson("/api/subscriber/{$subscriber->id}/get-websites");

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'id' => $websites[0]->id,
                'name' => $websites[0]->name,
            ]);
    }

    /** @test */
    public function show_returns_404_if_subscriber_not_found()
    {
        $response = $this->getJson('/api/subscriber/999/get-websites');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Subscriber not found',
            ]);
    }

    /** @test */
    public function store_requires_email_and_website_ids()
    {
        $response = $this->postJson('/api/subscriber', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'website_ids']);
    }
}
