<?php

namespace Tests\Feature;

use App\Domain\Subscribers\Models\Subscriber;
use App\Domain\Websites\Models\Website;
use App\Jobs\SendPostToSubscribers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

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
