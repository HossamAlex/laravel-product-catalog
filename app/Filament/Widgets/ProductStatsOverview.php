<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Products', Product::count()),
            Stat::make('Categories', Category::count()),
            Stat::make('Brands', Brand::count()),
            Stat::make('Total Views', Product::sum('views_count')),
        ];
    }
}
