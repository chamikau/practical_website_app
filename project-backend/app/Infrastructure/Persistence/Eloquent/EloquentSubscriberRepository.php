<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Subscribers\SubscriberRepository;
use App\Domain\Subscribers\Models\Subscriber;

class EloquentSubscriberRepository implements SubscriberRepository
{
    public function findByIdWithWebsites(int $id): ?Subscriber
    {
        return Subscriber::with('websites')->find($id);
    }

    public function findByEmail(string $email): ?Subscriber
    {
        return Subscriber::where('email', $email)->first();
    }

    public function create(array $data): Subscriber
    {
        return Subscriber::create($data);
    }

    public function firstOrCreate(array $attributes, array $values = []): Subscriber
    {
        return Subscriber::firstOrCreate($attributes, $values);
    }

    public function attachWebsites(Subscriber $subscriber, array $websiteIds): void
    {
        $subscriber->websites()->syncWithoutDetaching($websiteIds);
    }

    public function syncWebsites(Subscriber $subscriber, array $websiteIds): void
    {
        $subscriber->websites()->syncWithoutDetaching($websiteIds);
    }
}
