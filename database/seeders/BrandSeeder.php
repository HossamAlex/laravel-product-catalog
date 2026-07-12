<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'San Lauren',
            'Chanel',
            'Coach',
            'Kate Spade',
            'Michael Kors',
            'Kate Hudson',
            'Coco Chanel',
            'Prada',
            'Louis Vuitton',
            'Dolce & Gabbana',
            'Fendi',
            'Tory Burch',
            'Hermes',
            'Louboutin',
            'Dior',
            'Miu Miu',
            'Aquazurra',
            'Adidas',
            'Nike',
            'Puma',
            'New Balance',
        ];

        foreach ($brands as $index => $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand)],
                [
                    'title' => $brand,
                    'sort' => $index,
                    'status' => true,
                ]
            );
        }
    }
}