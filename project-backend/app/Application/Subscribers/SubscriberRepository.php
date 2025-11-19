<?php

namespace App\Application\Subscribers;

use App\Domain\Subscribers\Models\Subscriber;

interface SubscriberRepository
{
    public function findByIdWithWebsites(int $id): ?Subscriber;
    public function findByEmail(string $email): ?Subscriber;
    public function create(array $data): Subscriber;
    public function firstOrCreate(array $attributes, array $values = []): Subscriber;
    public function attachWebsites(Subscriber $subscriber, array $websiteIds): void;
    public function syncWebsites(Subscriber $subscriber, array $websiteIds): void;
}
