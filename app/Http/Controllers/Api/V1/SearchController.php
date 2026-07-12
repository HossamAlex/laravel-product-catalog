<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Services\SearchService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request, SearchService $searchService)
    {
        $results = $searchService->search($request->get('q'));

        return $this->success([
            'products' => ProductResource::collection($results['products']),
            'categories' => CategoryResource::collection($results['categories']),
            'brands' => BrandResource::collection($results['brands']),
        ]);
    }
}