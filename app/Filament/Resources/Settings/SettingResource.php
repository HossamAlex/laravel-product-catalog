<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\ManageSettings;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;


class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'setting';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('key')
                ->required()
                ->disabledOn('edit')
                ->unique(ignoreRecord: true),

            Select::make('type')
                ->options([
                    'text' => 'Text',
                    'phone' => 'Phone',
                    'url' => 'URL',
                    'file' => 'File',
                    'textarea' => 'Textarea',
                ])
                ->required(),

            TextInput::make('value')
                ->visible(fn ($get) => in_array($get('type'), ['text', 'phone', 'url']))
                ->nullable(),

            Textarea::make('value')
                ->visible(fn ($get) => $get('type') === 'textarea')
                ->nullable(),

            FileUpload::make('value')
                ->visible(fn ($get) => $get('type') === 'file')
                ->disk('public')
                ->directory('settings')
                ->image()
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('setting')
            ->columns([
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('value')
                    ->searchable(),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
                //DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                   // DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSettings::route('/'),
        ];
    }
}
