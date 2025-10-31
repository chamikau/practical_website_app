<?php

namespace Tests\Feature;

use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_all_websites_with_data()
    {
        $websites = Website::factory()->count(3)->create();

        $response = $this->getJson('/api/websites');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => ['id', 'name', 'slug', 'created_at', 'updated_at']
        ]);

        foreach ($websites as $website) {
            $response->assertJsonFragment([
                'id' => $website->id,
                'name' => $website->name,
                'slug' => $website->slug,
            ]);
        }

        $this->assertCount(3, $response->json());
    }

    /** @test */
    public function store_creates_a_new_website()
    {
        $payload = [
            'name' => 'Test Website',
            'slug' => 'https://example.com',
        ];

        $response = $this->postJson('/api/websites', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Test Website',
                'slug' => 'https://example.com',
            ]);

        $this->assertDatabaseHas('websites', [
            'name' => 'Test Website',
            'slug' => 'https://example.com',
        ]);
    }

    /** @test */
    public function store_fails_if_name_is_missing()
    {
        $payload = [
            'slug' => 'https://example.com',
        ];

        $response = $this->postJson('/api/websites', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

}
