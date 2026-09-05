<?php

namespace App\Filament\Resources\KarangTarunaUnits;

use App\Domain\Units\Models\KarangTarunaUnit;
use App\Filament\Resources\KarangTarunaUnits\Pages\CreateKarangTarunaUnit;
use App\Filament\Resources\KarangTarunaUnits\Pages\EditKarangTarunaUnit;
use App\Filament\Resources\KarangTarunaUnits\Pages\ListKarangTarunaUnits;
use App\Filament\Resources\KarangTarunaUnits\Pages\ViewKarangTarunaUnit;
use App\Filament\Resources\KarangTarunaUnits\Schemas\KarangTarunaUnitForm;
use App\Filament\Resources\KarangTarunaUnits\Schemas\KarangTarunaUnitInfolist;
use App\Filament\Resources\KarangTarunaUnits\Tables\KarangTarunaUnitsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KarangTarunaUnitResource extends Resource
{
    protected static ?string $model = KarangTarunaUnit::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Wilayah & Kelembagaan';

    protected static ?string $navigationLabel = 'Unit Karang Taruna';

    protected static ?string $modelLabel = 'Unit Karang Taruna';

    protected static ?string $pluralModelLabel = 'Unit Karang Taruna';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return KarangTarunaUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KarangTarunaUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KarangTarunaUnitsTable::configure($table);
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
            'index' => ListKarangTarunaUnits::route('/'),
            'create' => CreateKarangTarunaUnit::route('/create'),
            'view' => ViewKarangTarunaUnit::route('/{record}'),
            'edit' => EditKarangTarunaUnit::route('/{record}/edit'),
        ];
    }
}
