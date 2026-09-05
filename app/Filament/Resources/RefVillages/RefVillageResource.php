<?php

namespace App\Filament\Resources\RefVillages;

use App\Domain\Territory\Models\RefVillage;
use App\Filament\Resources\RefVillages\Pages\CreateRefVillage;
use App\Filament\Resources\RefVillages\Pages\EditRefVillage;
use App\Filament\Resources\RefVillages\Pages\ListRefVillages;
use App\Filament\Resources\RefVillages\Pages\ViewRefVillage;
use App\Filament\Resources\RefVillages\Schemas\RefVillageForm;
use App\Filament\Resources\RefVillages\Schemas\RefVillageInfolist;
use App\Filament\Resources\RefVillages\Tables\RefVillagesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RefVillageResource extends Resource
{
    protected static ?string $model = RefVillage::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Wilayah & Kelembagaan';

    protected static ?string $navigationLabel = 'Master Desa / Kelurahan';

    protected static ?string $modelLabel = 'Desa / Kelurahan';

    protected static ?string $pluralModelLabel = 'Master Desa & Kelurahan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return RefVillageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RefVillageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefVillagesTable::configure($table);
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
            'index' => ListRefVillages::route('/'),
            'create' => CreateRefVillage::route('/create'),
            'view' => ViewRefVillage::route('/{record}'),
            'edit' => EditRefVillage::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperadmin() ?? false;
    }
}
