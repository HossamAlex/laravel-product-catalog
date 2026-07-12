<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    public function creating(Product $product): void
    {
        if (blank($product->sku)) {
            $product->sku = $this->generateSku();
        }

        if (blank($product->slug)) {
            $product->slug = Str::slug($product->title);
        }
    }

    public function updating(Product $product): void
    {
        if ($product->isDirty('title') && blank($product->slug)) {
            $product->slug = Str::slug($product->title);
        }
    }

    private function generateSku(): string
    {
        $latestId = Product::query()->withTrashed()->max('id') ?? 0;

        return 'VN-' . str_pad($latestId + 1, 6, '0', STR_PAD_LEFT);
    }
}