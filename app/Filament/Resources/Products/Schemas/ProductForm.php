<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        config('media-library.disk_name');
config('filesystems.default');
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(false),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('brand_id')
                    ->relationship('brand', 'title')
                    ->searchable()
                    ->preload(),

                Select::make('categories')
                    ->relationship('categories', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Select::make('attributeValues')
                    ->label('Attributes')
                    ->relationship(
                        name: 'attributeValues',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->with('attribute')->orderBy('attribute_id')->orderBy('sort')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_title)
                    ->multiple()
                    ->searchable()
                    ->preload(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('KD'),
                TextInput::make('special_price')
                    ->numeric()
                    ->prefix('KD'),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Toggle::make('status')
                    ->default(true),
                Select::make('collections')
                    ->relationship('collections', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SpatieMediaLibraryFileUpload::make('main_image')
                    ->collection('main_image')
                    ->disk('public')
                    ->image()
                    ->imagePreviewHeight('120')
                    ->openable()
                    ->downloadable(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->collection('gallery')
                    ->disk('public')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('videos')
                    ->collection('videos')
                    ->multiple()
                    ->reorderable()
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/quicktime',
                        'video/webm',
                    ])
                    ->maxSize(51200)
                    ->columnSpanFull(),
            ]);
    }
}
