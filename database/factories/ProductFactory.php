<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $titles = [
            'Women Heel Sandal',
            'Luxury Handbag',
            'Comfortable Slippers',
            'Summer Sandals',
            'Luxury Wallet',
            'Modern Glasses',
            'Luxury Perfumes',
        ];

        $title = fake()->randomElement($titles) . ' ' . fake()->numberBetween(100, 999);
        $price = fake()->randomFloat(3, 3, 35);
        $hasOffer = fake()->boolean(30);

        return [
            'sku' => null,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => fake()->paragraph(),
            'brand_id' => Brand::query()->inRandomOrder()->value('id'),
            'price' => $price,
            'special_price' => $hasOffer ? max(1, $price - fake()->randomFloat(3, 1, 5)) : null,
            'status' => true,
            'sort' => fake()->numberBetween(0, 100),
            'published_at' => now()->subDays(fake()->numberBetween(0, 60)),
            'views_count' => fake()->numberBetween(0, 900),
        ];
    }
}