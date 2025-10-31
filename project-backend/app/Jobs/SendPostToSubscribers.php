<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\SubscriberPost;
use App\Mail\PostPublishedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPostToSubscribers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        $website = $this->post->website;
        $subscribers = $website->subscribers;

        foreach ($subscribers as $subscriber) {
            $alreadySent = SubscriberPost::where('post_id', $this->post->id)
                ->where('subscriber_id', $subscriber->id)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            Mail::to($subscriber->email)->send(
                new PostPublishedMail($this->post, $website)
            );

            SubscriberPost::create([
                'post_id' => $this->post->id,
                'subscriber_id' => $subscriber->id,
            ]);
        }
    }
}
