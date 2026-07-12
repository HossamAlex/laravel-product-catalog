<?php

namespace App\Services;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function query(): Builder
    {
        return Product::query()
            ->with([
                'brand',
                'categories',
                'collections',
                'media',
                'attributeValues.attribute',
            ])
            ->where('status', true);
    }

    public function filter(array $filters): Builder
    {
        $query = $this->query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('brand', fn ($brandQuery) =>
                            $brandQuery->where('title', 'like', "%{$search}%")
                        )
                        ->orWhereHas('categories', fn ($categoryQuery) =>
                            $categoryQuery->where('title', 'like', "%{$search}%")
                        );
                });
            })
            ->when($filters['category'] ?? null, function ($query, $slug) {
                $query->whereHas('categories', fn ($categoryQuery) =>
                    $categoryQuery->where('slug', $slug)
                );
            })
            ->when($filters['brand'] ?? null, function ($query, $slug) {
                $query->whereHas('brand', fn ($brandQuery) =>
                    $brandQuery->where('slug', $slug)
                );
            })
            ->when($filters['price_from'] ?? null, fn ($query, $price) =>
                $query->where('price', '>=', $price)
            )
            ->when($filters['price_to'] ?? null, fn ($query, $price) =>
                $query->where('price', '<=', $price)
            )
            ->when($filters['attributes'] ?? null, function ($query, $attributes) {
                $query->whereHas('attributeValues', fn ($attributeQuery) =>
                    $attributeQuery->whereIn('attribute_values.id', (array) $attributes)
                );
            });

        return match ($filters['sort'] ?? 'latest') {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('views_count'),
            'offers' => $query->whereNotNull('special_price')->latest(),
            default => $query->latest(),
        };
    }
   
    public function findBySlug(string $slug): Product
    {
        $product = $this->query()
            ->with(['categories.parent'])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('views_count');

        return $product;
    }

    public function related(Product $product, int $limit = 8)
    {
        return $this->query()
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('brand_id', $product->brand_id)
                    ->orWhereHas('categories', fn ($q) =>
                        $q->whereIn('categories.id', $product->categories->pluck('id'))
                    );
            })
            ->take($limit)
            ->get();
    }
}
