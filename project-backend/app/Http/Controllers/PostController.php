<?php

namespace App\Http\Controllers;

use App\Application\Posts\PostRepository;
use App\Application\Websites\WebsiteRepository;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\WebsiteResource;
use App\Jobs\SendPostToSubscribers;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
        private WebsiteRepository $websiteRepository
    ) {}

    /**
     * Display all posts for a specific website
     */
    public function show(int $websiteId): JsonResponse
    {
        $website = $this->websiteRepository->find($websiteId);
        $posts = $this->postRepository->getByWebsite($websiteId);

        return response()->json([
            'website' => new WebsiteResource($website),
            'posts'   => PostResource::collection($posts),
        ]);
    }

    /**
     * Store a newly created post.
     */
    public function store(PostRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['content_hash'] = hash('sha256', $data['title'].'|'.$data['description']);

        try {
            $post = $this->postRepository->create($data);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Duplicate post'], 409);
        }

        SendPostToSubscribers::dispatch($post);

        return response()->json([
            'message' => 'Post created',
            'post'    => new PostResource($post),
        ], 201);
    }
}
