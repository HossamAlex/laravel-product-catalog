<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Product;

class CollectionService
{
    public function activeWithProducts(int $productsLimit = 4)
    {
        return Collection::query()
            ->where('status', true)
            ->with(['products' => function ($query) use ($productsLimit) {
                $query->where('status', true)
                    ->latest()
                    ->take($productsLimit);
            }])
            ->orderBy('sort')
            ->get();
    }

    public function getHomeSections()
    {
        return Collection::where('show_on_home', true)
            ->orderBy('sort')
            ->get()
            ->map(function ($section) {

                $section->products = match ($section->type) {

                    'manual' => $section->products()
                        ->take($section->limit)
                        ->get(),

                    'latest' => Product::latest('published_at')
                        ->take($section->limit)
                        ->get(),

                    'most_viewed' => Product::orderByDesc('visit_counts')
                        ->take($section->limit)
                        ->get(),

                    'brand' => Product::where('brand_id', $section->brand_id)
                        ->take($section->limit)
                        ->get(),

                    'category' => Product::whereHas('categories', function ($q) use ($section) {
                            $q->where('categories.id', $section->category_id);
                        })
                        ->take($section->limit)
                        ->get(),

                    default => collect(),
                };

                return $section;
            });
    }

    public function findBySlug(string $slug): Collection
    {
        return Collection::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with('products')
            ->firstOrFail();
    }
}