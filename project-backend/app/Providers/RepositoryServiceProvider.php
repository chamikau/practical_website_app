<?php

namespace App\Providers;

use App\Application\Posts\PostRepository;
use App\Application\Subscribers\SubscriberRepository;
use App\Application\Users\UserRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentPostRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentSubscriberRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;
use App\Application\Websites\WebsiteRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentWebsiteRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(WebsiteRepository::class, EloquentWebsiteRepository::class);
        $this->app->bind(PostRepository::class, EloquentPostRepository::class);
        $this->app->bind(SubscriberRepository::class, EloquentSubscriberRepository::class);


    }
}
