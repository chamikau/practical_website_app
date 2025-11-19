<?php

namespace App\Application\Websites;

use App\Domain\Websites\Models\Website;

interface WebsiteRepository
{
    public function all();
    public function find(int $id): Website;
    public function create(array $data): Website;
}
