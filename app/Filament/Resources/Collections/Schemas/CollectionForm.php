<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('status')
                    ->required(),
                Select::make('type')
                    ->options([
            'manual' => 'Manual',
            'latest' => 'Latest',
            'most_viewed' => 'Most viewed',
            'offers' => 'Offers',
            'brand' => 'Brand',
            'category' => 'Category',
        ])
                    ->default('manual')
                    ->required(),
                TextInput::make('limit')
                    ->required()
                    ->numeric()
                    ->default(12),
                TextInput::make('brand_id')
                    ->numeric(),
                TextInput::make('category_id')
                    ->numeric(),
                Toggle::make('show_on_home')
                    ->required(),
            ]);
    }
}
