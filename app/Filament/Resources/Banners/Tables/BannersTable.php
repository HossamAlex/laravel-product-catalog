<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('desktop_image')
                    ->label('Image')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('desktop_image'))
                    ->size(80)
                    ->square(),

                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('link')
                    ->limit(30),

                IconColumn::make('status')
                    ->boolean(),

                TextColumn::make('sort')
                    ->sortable(),
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
}