<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                ->nullable(),

            TextInput::make('link')
                ->nullable(),

            TextInput::make('button_text')
                ->nullable(),

            SpatieMediaLibraryFileUpload::make('desktop_image')
                ->collection('desktop_image')
                ->disk('public')
                ->image()
                ->required(),

            SpatieMediaLibraryFileUpload::make('mobile_image')
                ->collection('mobile_image')
                ->disk('public')
                ->image()
                ->nullable(),

            DateTimePicker::make('start_at')
                ->nullable(),

            DateTimePicker::make('end_at')
                ->nullable(),

            TextInput::make('sort')
                ->numeric()
                ->default(0),

            Toggle::make('status')
                ->default(true),
            ]);
    }
}
