<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Posts\PostRepository;
use App\Domain\Posts\Models\Post;
use Illuminate\Support\Collection;

class EloquentPostRepository implements PostRepository
{
    public function getByWebsite(int $websiteId): Collection
    {
        return Post::where('website_id', $websiteId)->latest()->get();
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }
}
