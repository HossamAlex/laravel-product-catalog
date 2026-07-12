<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\V1\ProductIndexRequest;

class ProductController extends Controller
{
    use ApiResponse;
    // public function index(ProductIndexRequest $request, ProductService $productService)
    // {
    //     $query = $productService->filter($request->validated());

    //     $products = $query->paginate($request->integer('per_page', 12));

    //     return $this->success(ProductResource::collection($products));
    // }

    public function index(ProductIndexRequest $request, ProductService $productService)
    {
        $query = $productService->filter($request->validated());

        $products = $query->paginate($request->integer('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(string $slug, ProductService $productService)
    {
        $product = $productService->findBySlug($slug);

        $category = $product->categories->first();

        $breadcrumbs = [];

        if ($category) {
            $current = $category;

            while ($current) {
                array_unshift($breadcrumbs, [
                    'id' => $current->id,
                    'title' => $current->title,
                    'slug' => $current->slug,
                ]);

                $current = $current->parent;
            }
        }

        return $this->success([
            'product' => new ProductResource($product),
            'breadcrumbs' => $breadcrumbs,
            'related_products' => ProductResource::collection(
                $productService->related($product)
            ),
        ]);
    }
}
