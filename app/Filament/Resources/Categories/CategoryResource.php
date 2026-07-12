<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\ManageCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'category';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(fn (?Category $record) => self::getCategoryOptions($record))
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('status')
                    ->required(),
                
            ]);

            


    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('category')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('parent.title')
                    ->label('Parent')
                    ->placeholder('Main Category')
                    ->sortable()
                    ->searchable(),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCategories::route('/'),
        ];
    }

    private static function getCategoryOptions(?Category $record = null): array
    {
        $categories = Category::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('sort')
            ->orderBy('title')
            ->get();

        $options = [];

        foreach ($categories as $category) {
            self::buildCategoryOptions($category, $options, 0, $record?->id);
        }

        return $options;
    }

    private static function buildCategoryOptions(Category $category, array &$options, int $level = 0, ?int $excludeId = null): void
    {
        if ($category->id === $excludeId) {
            return;
        }

        $prefix = str_repeat('— ', $level);

        $options[$category->id] = $prefix . $category->title;

        foreach ($category->children()->orderBy('sort')->orderBy('title')->get() as $child) {
            self::buildCategoryOptions($child, $options, $level + 1, $excludeId);
        }
    }
}
