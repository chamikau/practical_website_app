<?php

namespace App\Domain\Posts\Models;

use App\Domain\Subscribers\Models\Subscriber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PostSend extends Model
{
    protected $fillable = ['post_id', 'subscriber_id', 'sent_at'];


    protected $dates = ['sent_at'];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }
}
