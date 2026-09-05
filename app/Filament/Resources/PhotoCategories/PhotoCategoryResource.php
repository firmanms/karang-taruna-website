<?php

namespace App\Filament\Resources\PhotoCategories;

use App\Domain\Content\Models\PhotoCategory;
use App\Filament\Resources\PhotoCategories\Pages\CreatePhotoCategory;
use App\Filament\Resources\PhotoCategories\Pages\EditPhotoCategory;
use App\Filament\Resources\PhotoCategories\Pages\ListPhotoCategories;
use App\Filament\Resources\PhotoCategories\Pages\ViewPhotoCategory;
use App\Filament\Resources\PhotoCategories\Schemas\PhotoCategoryForm;
use App\Filament\Resources\PhotoCategories\Schemas\PhotoCategoryInfolist;
use App\Filament\Resources\PhotoCategories\Tables\PhotoCategoriesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PhotoCategoryResource extends Resource
{
    protected static ?string $model = PhotoCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Galeri & Media';

    protected static ?string $navigationLabel = 'Album Foto';

    protected static ?string $modelLabel = 'Album Foto';

    protected static ?string $pluralModelLabel = 'Album Galeri Foto';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    public static function form(Schema $schema): Schema
    {
        return PhotoCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PhotoCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhotoCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPhotoCategories::route('/'),
            'create' => CreatePhotoCategory::route('/create'),
            'view' => ViewPhotoCategory::route('/{record}'),
            'edit' => EditPhotoCategory::route('/{record}/edit'),
        ];
    }
}
