<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::query()->exists()) {
            Product::query()
                ->with('categories')
                ->get()
                ->each(function (Product $product): void {
                    $category = $product->categories->first();

                    if ($category && ! $product->hasMedia('main_image')) {
                        $this->addImages($product, $category);
                    }
                });

            return;
        }

        $leafCategories = Category::query()
            ->whereDoesntHave('children')
            ->get();

        $colors = AttributeValue::query()
            ->whereHas('attribute', fn ($q) => $q->where('title', 'Color'))
            ->get();

        $sizes = AttributeValue::query()
            ->whereHas('attribute', fn ($q) => $q->where('title', 'Size'))
            ->get();

        $collections = Collection::query()->get();

        Product::factory()
            ->count(50)
            ->create()
            ->each(function (Product $product) use ($leafCategories, $colors, $sizes, $collections) {
                $category = $leafCategories->random();

                $product->categories()->sync([$category->id]);

                if ($colors->isNotEmpty()) {
                    $product->attributeValues()->attach(
                        $colors->random(fake()->numberBetween(1, min(3, $colors->count())))->pluck('id')->toArray()
                    );
                }

                if ($collections->isNotEmpty()) {
                    $product->collections()->attach(
                        $collections->random(fake()->numberBetween(1, min(3, $collections->count())))->pluck('id')->toArray()
                    );
                }

                //$this->addImages($product);
                $this->addImages($product, $category);

                $product->generateSku();
                $product->refresh();
                
            });
    }
    
    private function addImages(Product $product, Category $category): void
    {
        $folder = $this->getDemoImageFolder($category->title);

        $path = storage_path("app/demo/{$folder}");

        if (! is_dir($path)) {
            $path = storage_path('app/demo/default');
        }

        $files = collect(glob($path . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE));

        

        if ($files->isEmpty()) {
            dump("No images in: {$path}");
            return;
        }

        $selectedFiles = $files->random(min(3, $files->count()));

        if (is_string($selectedFiles)) {
            $selectedFiles = collect([$selectedFiles]);
        }

        $mainImage = $product
            ->addMedia($selectedFiles->first())
            ->preservingOriginal()
            ->toMediaCollection('main_image', 'public');

        $this->ensureMediaHasUuid($mainImage);

        foreach ($selectedFiles->slice(1) as $file) {
            $galleryImage = $product
                ->addMedia($file)
                ->preservingOriginal()
                ->toMediaCollection('gallery', 'public');

            $this->ensureMediaHasUuid($galleryImage);
        }
        
    }
    private function getDemoImageFolder(string $categoryTitle): string
    {
        return match ($categoryTitle) {
            'Heels' => 'heels',
            'Slipper', 'Slippers' => 'slippers',
            'Flats' => 'flats',
            'Sandals' => 'sandals',

            'Handbags', 'Shoulder Bags', 'Backpacks' => 'bags',
            'Wallets' => 'wallets',

            'Belts', 'Glasses', 'Jewelry', 'Scarves' => 'accessories',

            'Women Perfumes', 'Men Perfumes' => 'perfumes',

            default => 'default',
        };
    }

    private function ensureMediaHasUuid($media): void
    {
        if (blank($media->uuid)) {
            $media->forceFill(['uuid' => (string) Str::uuid()])->saveQuietly();
        }
    }
}
