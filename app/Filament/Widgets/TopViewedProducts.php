<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables;

class TopViewedProducts extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->orderByDesc('views_count')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('sku'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('views_count')->label('Views')->sortable(),
            ]);
    }
}
