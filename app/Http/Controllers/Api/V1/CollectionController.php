<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionResource;
use App\Services\CollectionService;
use App\Traits\ApiResponse;

class CollectionController extends Controller
{
    use ApiResponse;

    public function show(string $slug, CollectionService $collectionService)
    {
        return $this->success(
            new CollectionResource($collectionService->findBySlug($slug))
        );
    }
}