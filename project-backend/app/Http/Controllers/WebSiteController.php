<?php

namespace App\Http\Controllers;

use App\Application\Websites\WebsiteRepository;
use App\Http\Requests\WebSiteRequest;
use App\Http\Resources\WebSiteResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WebSiteController extends Controller
{
    public function __construct(private WebsiteRepository $repository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return WebsiteResource::collection(
            $this->repository->all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WebSiteRequest $request)
    {
        $website = $this->repository->create($request->validated());

        return (new WebsiteResource($website))
            ->response()
            ->setStatusCode(201);
    }
}
