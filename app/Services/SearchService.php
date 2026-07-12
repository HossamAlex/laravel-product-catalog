<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class SearchService
{
    public function search(?string $keyword): array
    {
        if (blank($keyword) || mb_strlen($keyword) < 2) {
            return [
                'products' => [],
                'categories' => [],
                'brands' => [],
            ];
        }

        return [
            'products' => Product::query()
                ->where('status', true)
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('sku', 'like', "%{$keyword}%")
                        ->orWhereHas('brand', fn ($q) => $q->where('title', 'like', "%{$keyword}%"))
                        ->orWhereHas('categories', fn ($q) => $q->where('title', 'like', "%{$keyword}%"));
                })
                ->with(['brand', 'media'])
                ->limit(8)
                ->get(),

            'categories' => Category::query()
                ->where('status', true)
                ->where('title', 'like', "%{$keyword}%")
                ->limit(6)
                ->get(),

            'brands' => Brand::query()
                ->where('status', true)
                ->where('title', 'like', "%{$keyword}%")
                ->limit(6)
                ->get(),
        ];
    }
}