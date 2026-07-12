<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            'Recently Added',
            'Offers',
            'Summer Collection',
        ];

        foreach ($collections as $index => $title) {
            Collection::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'description' => null,
                    'sort' => $index,
                    'status' => true,
                ]
            );
        }
    }
}