<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{


    public function index(CategoryService $categoryService)
    {
        return CategoryResource::collection($categoryService->activeTree());
    }


   public function show(string $slug, CategoryService $categoryService, Request $request)
    {
        $category = $categoryService->findBySlug($slug);

        $products = $categoryService
            ->productsForCategory($category, $request->all())
            ->paginate($request->integer('per_page', 24));

        return response()->json([
            'category' => new CategoryResource($category),
            'breadcrumbs' => $categoryService->breadcrumbs($category),
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }



}
