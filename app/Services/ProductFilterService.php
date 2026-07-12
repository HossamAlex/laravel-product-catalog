<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductFilterService
{
    public function filters(?string $categorySlug = null): array
    {
        $categoryIds = [];

        if ($categorySlug) {
            $category = Category::query()
                ->where('status', true)
                ->where('slug', $categorySlug)
                ->first();

            if ($category) {
                $categoryIds = $this->getAllCategoryIds($category);
            }
        }

        $filterProducts = function ($query) use ($categoryIds) {
            $query->where('status', true);

            if ($categoryIds !== []) {
                $query->whereHas('categories', function ($categoryQuery) use ($categoryIds) {
                    $categoryQuery->whereIn('categories.id', $categoryIds);
                });
            }
        };

        return [
            'categories' => Category::query()
                ->where('status', true)
                ->whereNull('parent_id')
                ->withCount('products')
                ->orderBy('sort')
                ->orderBy('title')
                ->get(['id', 'title', 'slug']),

            'brands' => Brand::query()
                ->where('status', true)
                ->whereHas('products', $filterProducts)
                ->withCount(['products' => $filterProducts])
                ->orderBy('title')
                ->get(['id', 'title', 'slug']),

            'attributes' => Attribute::query()
                ->where('status', true)
                ->with(['values' => function ($query) {
                    $query->where('status', true)
                        ->orderBy('sort')
                        ->orderBy('title')
                        ->select('id', 'attribute_id', 'title', 'slug');
                }])
                ->orderBy('sort')
                ->get(),

            'price' => [
                'min' => Product::query()->where('status', true)->min('price') ?? 0,
                'max' => Product::query()->where('status', true)->max('price') ?? 0,
                'currency' => 'KD',
            ],
        ];
    }

    private function getAllCategoryIds(Category $category): array
    {
        $ids = [$category->id];

        $category->loadMissing('children');

        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }

        return $ids;
    }
}
