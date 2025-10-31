<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostToSubscribers;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display all posts for a specific website
     */
    public function show($websiteId): JsonResponse
    {
        $website = Website::findOrFail($websiteId);

        $posts = Post::where('website_id', $website->id)
            ->with('website')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'website' => $website,
            'posts' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);


        $hash = hash('sha256', $data['title'].'|'.$data['description']);

        try {
            $post = Post::create([
                'website_id' => $data['website_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'content_hash' => $hash,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Duplicate post'], 409);
        }

        SendPostToSubscribers::dispatch($post);

        return response()->json(['message' => 'Post created', 'post' => $post], 201);
    }
}
