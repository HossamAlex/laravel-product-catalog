<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                ->label('Image')
                ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('main_image'))
                ->size(60)
                ->square(),
                TextColumn::make('title')
                    ->searchable(),
                
                TextColumn::make('sku')
                    ->searchable(),
                    
                TextColumn::make('brand.title')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attributeValues.title')
                    ->label('Attributes')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->money('KWD'),
                TextColumn::make('special_price')
                    ->money('KWD'),
                TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('active')
                ->query(fn (Builder $query): Builder => $query->where('status', true)),
                 SelectFilter::make('category')
                    ->relationship('categories', 'title')->multiple()
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ...
            ])
            ->defaultSort('id', 'desc');
    }

}
