<?php

namespace App\Application\Users;

use App\Domain\Users\Models\User;

interface UserRepository
{
    public function create(array $data): User;
}
