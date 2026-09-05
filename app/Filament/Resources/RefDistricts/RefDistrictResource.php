<?php

namespace App\Filament\Resources\RefDistricts;

use App\Domain\Territory\Models\RefDistrict;
use App\Filament\Resources\RefDistricts\Pages\CreateRefDistrict;
use App\Filament\Resources\RefDistricts\Pages\EditRefDistrict;
use App\Filament\Resources\RefDistricts\Pages\ListRefDistricts;
use App\Filament\Resources\RefDistricts\Pages\ViewRefDistrict;
use App\Filament\Resources\RefDistricts\Schemas\RefDistrictForm;
use App\Filament\Resources\RefDistricts\Schemas\RefDistrictInfolist;
use App\Filament\Resources\RefDistricts\Tables\RefDistrictsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RefDistrictResource extends Resource
{
    protected static ?string $model = RefDistrict::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Wilayah & Kelembagaan';

    protected static ?string $navigationLabel = 'Master Kecamatan';

    protected static ?string $modelLabel = 'Kecamatan';

    protected static ?string $pluralModelLabel = 'Master Kecamatan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    public static function form(Schema $schema): Schema
    {
        return RefDistrictForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RefDistrictInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefDistrictsTable::configure($table);
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
            'index' => ListRefDistricts::route('/'),
            'create' => CreateRefDistrict::route('/create'),
            'view' => ViewRefDistrict::route('/{record}'),
            'edit' => EditRefDistrict::route('/{record}/edit'),
        ];
    }
}
