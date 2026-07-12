<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Shoes' => [
                'Heels',
                'Slipper',
                'Flats',
            ],
            'Bags' => [
                'Handbags',
                'Shoulder Bags',
                'Backpacks',
                'Wallets',
            ],
            'Accessories' => [
                'Belts',
                'Glasses',
                'Jewelry',
                'Scarves',
            ],
            'Perfumes' => [
                'Women Perfumes',
                'Men Perfumes',
            ],
            'Electronics' => [],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = Category::updateOrCreate(
                ['slug' => Str::slug($parent)],
                [
                    'title' => $parent,
                    'parent_id' => null,
                    'sort' => 0,
                    'status' => true,
                ]
            );

            foreach ($children as $index => $child) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($child)],
                    [
                        'title' => $child,
                        'parent_id' => $parentCategory->id,
                        'sort' => $index,
                        'status' => true,
                    ]
                );
            }
        }
    }
}