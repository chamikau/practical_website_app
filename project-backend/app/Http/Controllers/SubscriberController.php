<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'website_ids' => 'required|array|min:1',
            'website_ids.*' => 'exists:websites,id'
        ]);

        $subscriber = Subscriber::firstOrCreate(['email' => $data['email']], ['name' => $data['name'] ?? null]);
        $subscriber->websites()->syncWithoutDetaching($data['website_ids']);

        return response()->json(['message' => 'Subscribed', 'subscriber' => $subscriber->load('websites')], 201);
    }

    /**
     * show a function.
     */
    public function show($subscriber_id): JsonResponse
    {
        $subscriber = Subscriber::with('websites')
        ->find($subscriber_id);

        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }

        return response()->json($subscriber->websites);
    }
}
