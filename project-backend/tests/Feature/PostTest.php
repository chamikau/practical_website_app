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
    public function a_post_can_be_created_and_dispatches_job_to_subscribers()
    {
        Queue::fake();

        $website = Website::factory()->create();
        $subscriber = Subscriber::factory()->create();
        $website->subscribers()->attach($subscriber);

        // Post data
        $postData = [
            'website_id' => $website->id,
            'title' => 'Sample Post',
            'description' => 'This is a test post description.',
        ];

        // Call API
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
}
