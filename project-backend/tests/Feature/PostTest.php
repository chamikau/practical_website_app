<?php

namespace Tests\Feature;

use App\Domain\Subscribers\Models\Subscriber;
use App\Domain\Websites\Models\Website;
use App\Domain\Posts\Models\Post;
use App\Jobs\SendPostToSubscribers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_post_can_be_created_and_dispatches_job_to_subscribers()
    {
        Queue::fake();

        $website = Website::factory()->create();
        $subscriber = Subscriber::factory()->create();
        $website->subscribers()->attach($subscriber);

        $postData = [
            'website_id' => $website->id,
            'title' => 'Sample Post',
            'description' => 'This is a test post description.',
        ];

        $response = $this->postJson("/api/websites/{$website->id}/posts", $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'post' => [
                    'id',
                    'website_id',
                    'title',
                    'description',
                    'content_hash',
                    'created_at',
                    'updated_at'
                ],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'description' => $postData['description'],
            'website_id' => $website->id
        ]);

        Queue::assertPushed(SendPostToSubscribers::class, function ($job) use ($postData) {
            return $job->post->title === $postData['title'];
        });
    }

    /** @test */
    public function store_returns_409_if_duplicate_post_is_created()
    {
        $website = Website::factory()->create();

        $postData = [
            'website_id' => $website->id,
            'title' => 'Duplicate Post',
            'description' => 'Duplicate description',
        ];

        // First creation
        $this->postJson("/api/websites/{$website->id}/posts", $postData);

        // Attempt duplicate
        $response = $this->postJson("/api/websites/{$website->id}/posts", $postData);

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'Duplicate post'
            ]);
    }

    /** @test */
    public function store_fails_validation_if_fields_missing()
    {
        $website = Website::factory()->create();

        $response = $this->postJson("/api/websites/{$website->id}/posts", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description', 'website_id']);
    }
}
