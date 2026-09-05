<?php

namespace App\Filament\Resources\DownloadCategories;

use App\Domain\Content\Models\DownloadCategory;
use App\Filament\Resources\DownloadCategories\Pages\CreateDownloadCategory;
use App\Filament\Resources\DownloadCategories\Pages\EditDownloadCategory;
use App\Filament\Resources\DownloadCategories\Pages\ListDownloadCategories;
use App\Filament\Resources\DownloadCategories\Pages\ViewDownloadCategory;
use App\Filament\Resources\DownloadCategories\Schemas\DownloadCategoryForm;
use App\Filament\Resources\DownloadCategories\Schemas\DownloadCategoryInfolist;
use App\Filament\Resources\DownloadCategories\Tables\DownloadCategoriesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DownloadCategoryResource extends Resource
{
    protected static ?string $model = DownloadCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Kategori Unduhan';

    protected static ?string $modelLabel = 'Kategori Unduhan';

    protected static ?string $pluralModelLabel = 'Kategori Dokumen Unduhan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderArrowDown;

    public static function form(Schema $schema): Schema
    {
        return DownloadCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DownloadCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DownloadCategoriesTable::configure($table);
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
            'index' => ListDownloadCategories::route('/'),
            'create' => CreateDownloadCategory::route('/create'),
            'view' => ViewDownloadCategory::route('/{record}'),
            'edit' => EditDownloadCategory::route('/{record}/edit'),
        ];
    }
}
