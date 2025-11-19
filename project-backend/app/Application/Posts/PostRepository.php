<?php

namespace App\Application\Posts;

use App\Domain\Posts\Models\Post;
use Illuminate\Support\Collection;

interface PostRepository
{
    public function getByWebsite(int $websiteId): Collection;
    public function create(array $data): Post;
}
