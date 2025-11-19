<?php

namespace App\Services\Auth;

use App\Domain\Subscribers\Repositories\SubscriberRepositoryInterface;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private SubscriberRepositoryInterface $subscriberRepo
    ) {}

    public function registerAndSubscribe(array $data)
    {
        $user = $this->userRepo->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $subscriber = $this->subscriberRepo->findOrCreateByEmail($user->email, $user->name);

        $this->subscriberRepo->attachWebsites($subscriber, $data['website_ids']);

        return $user;
    }
}
