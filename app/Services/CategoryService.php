<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function activeTree(): Collection
    {
        return Category::query()
            ->with('children')
            ->whereNull('parent_id')
            ->where('status', true)
            ->orderBy('sort')
            ->orderBy('title')
            ->get();
    }

    public function findBySlug(string $slug): Category
    {
        return Category::query()
            ->with([
                'parent',
                'children' => function ($q) {
                    $q->withCount([
                        'products as products_count' => function ($query) {
                            $query->where('status', true);
                        }
                    ]);
                }
            ])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
    }


    public function productsForCategory(Category $category, array $filters = [])
    {
        $categoryIds = $this->getAllCategoryIds($category);

        return Product::query()
            ->where('status', true)
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->when($filters['brand'] ?? null, function ($query, $slug) {
                $query->whereHas('brand', fn ($q) => $q->where('slug', $slug));
            })
            ->when($filters['price_from'] ?? null, function ($query, $price) {
                $query->where('price', '>=', $price);
            })
            ->when($filters['price_to'] ?? null, function ($query, $price) {
                $query->where('price', '<=', $price);
            })
            ->when($filters['sort'] ?? null, function ($query, $sort) {
                match ($sort) {
                    'price_low' => $query->orderBy('price'),
                    'price_high' => $query->orderByDesc('price'),
                    'popular' => $query->orderByDesc('views_count'),
                    'offers' => $query->whereNotNull('special_price'),
                    default => $query->latest(),
                };
            })
            ->with(['brand', 'categories', 'media']);
            
        }

    public function breadcrumbs(Category $category): array
    {
        $breadcrumbs = [];

        $current = $category;

        while ($current) {
            array_unshift($breadcrumbs, [
                'id' => $current->id,
                'title' => $current->title,
                'slug' => $current->slug,
            ]);

            $current = $current->parent;
        }

        return $breadcrumbs;
    }

    private function getAllCategoryIds(Category $category): array
    {
        $ids = [$category->id];

        $category->loadMissing('children');

        foreach ($category->children as $child) {
            $ids = array_merge(
                $ids,
                $this->getAllCategoryIds($child)
            );
        }

        return $ids;
    }
}