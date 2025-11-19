<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Users\UserRepository;
use App\Domain\Users\Models\User;

class EloquentUserRepository implements UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function find(int $id): User
    {
        return User::findOrFail($id);
    }
}
