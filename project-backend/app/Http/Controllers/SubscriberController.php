<?php

namespace App\Http\Controllers;

use App\Application\Subscribers\SubscriberRepository;
use App\Http\Requests\SubscriberRequest;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
    ) {}

    /**
     * Store a newly created subscriber.
     */
    public function store(SubscriberRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Check if subscriber exists or create
        $subscriber = $this->subscriberRepository->findByEmail($data['email'])
            ?? $this->subscriberRepository->create([
                'email' => $data['email'],
                'name' => $data['name'] ?? null,
            ]);

        // Attach website relations
        $this->subscriberRepository->attachWebsites($subscriber, $data['website_ids']);

        return response()->json([
            'message' => 'Subscribed successfully',
            'subscriber' => $subscriber->load('websites'),
        ], 201);
    }

    /**
     * Show a subscriber with its websites.
     */
    public function show(int $subscriberId): JsonResponse
    {
        $subscriber = $this->subscriberRepository->findByIdWithWebsites($subscriberId);

        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }

        return response()->json($subscriber->websites);
    }
}
