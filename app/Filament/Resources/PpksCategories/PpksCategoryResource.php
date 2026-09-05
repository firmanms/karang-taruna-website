<?php

namespace App\Filament\Resources\PpksCategories;

use App\Domain\PPKS\Models\PpksCategory;
use App\Filament\Resources\PpksCategories\Pages\CreatePpksCategory;
use App\Filament\Resources\PpksCategories\Pages\EditPpksCategory;
use App\Filament\Resources\PpksCategories\Pages\ListPpksCategories;
use App\Filament\Resources\PpksCategories\Schemas\PpksCategoryForm;
use App\Filament\Resources\PpksCategories\Tables\PpksCategoriesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PpksCategoryResource extends Resource
{
    protected static ?string $model = PpksCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Layanan Sosial & PPKS';

    protected static ?string $navigationLabel = 'Kategori PPKS';

    protected static ?string $modelLabel = 'Kategori PPKS';

    protected static ?string $pluralModelLabel = 'Master Kategori PPKS (26 Jenis)';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PpksCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpksCategoriesTable::configure($table);
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
            'index' => ListPpksCategories::route('/'),
            'create' => CreatePpksCategory::route('/create'),
            'edit' => EditPpksCategory::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperadmin() ?? false;
    }
}
