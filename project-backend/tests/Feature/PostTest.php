<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendPostToSubscribers;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_post_creation_sends_email_to_subscribers()
    {
        Queue::fake();

        $website = Website::factory()->create();
        $subscriber = Subscriber::factory()->create();
        $website->subscribers()->attach($subscriber);

        $postData = [
            'website_id' => $website->id,
            'title' => 'Hello',
            'description' => 'Test post'
        ];

        $response = $this->postJson("/api/websites/{$website->id}/posts", $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'post' => [
                    'id', 'website_id', 'title', 'description', 'content_hash'
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Hello',
            'description' => 'Test post'
        ]);

        Queue::assertPushed(SendPostToSubscribers::class, function ($job) use ($postData) {
            return $job->post->title === $postData['title'];
        });
    }
}
