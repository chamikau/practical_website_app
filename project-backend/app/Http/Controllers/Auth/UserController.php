<?php

namespace App\Http\Controllers\Auth;

use App\Application\Subscribers\SubscriberRepository;
use App\Application\Users\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterSubscribeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private SubscriberRepository $subscriberRepository
    ) {}

    /**
     * Register a new user and subscribe to websites.
     */
    public function registerAndSubscribe(RegisterSubscribeRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Create the user
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Create or get subscriber
        $subscriber = $this->subscriberRepository->firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name]
        );

        $this->subscriberRepository->syncWebsites($subscriber, $data['website_ids']);

        return response()->json([
            'message' => 'User registered and subscribed successfully',
            'user' => $user,
        ], 201);
    }
}
