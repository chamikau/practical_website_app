<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Websites\WebsiteRepository;
use App\Domain\Websites\Models\Website;

class EloquentWebsiteRepository implements WebsiteRepository
{
    public function all()
    {
        return Website::all();
    }

    public function find(int $id): Website
    {
        return Website::findOrFail($id);
    }

    public function create(array $data): Website
    {
        return Website::create($data);
    }

}
