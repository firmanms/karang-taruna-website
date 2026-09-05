<?php

namespace App\Filament\Resources\EventCategories;

use App\Domain\Content\Models\EventCategory;
use App\Filament\Resources\EventCategories\Pages\CreateEventCategory;
use App\Filament\Resources\EventCategories\Pages\EditEventCategory;
use App\Filament\Resources\EventCategories\Pages\ListEventCategories;
use App\Filament\Resources\EventCategories\Pages\ViewEventCategory;
use App\Filament\Resources\EventCategories\Schemas\EventCategoryForm;
use App\Filament\Resources\EventCategories\Schemas\EventCategoryInfolist;
use App\Filament\Resources\EventCategories\Tables\EventCategoriesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EventCategoryResource extends Resource
{
    protected static ?string $model = EventCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Kategori Agenda';

    protected static ?string $modelLabel = 'Kategori Agenda';

    protected static ?string $pluralModelLabel = 'Kategori Agenda';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmark;

    public static function form(Schema $schema): Schema
    {
        return EventCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EventCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventCategoriesTable::configure($table);
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
            'index' => ListEventCategories::route('/'),
            'create' => CreateEventCategory::route('/create'),
            'view' => ViewEventCategory::route('/{record}'),
            'edit' => EditEventCategory::route('/{record}/edit'),
        ];
    }
}
