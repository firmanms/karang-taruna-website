<?php

namespace App\Filament\Resources\UnitMembers;

use App\Domain\Units\Models\UnitMember;
use App\Filament\Resources\UnitMembers\Pages\CreateUnitMember;
use App\Filament\Resources\UnitMembers\Pages\EditUnitMember;
use App\Filament\Resources\UnitMembers\Pages\ListUnitMembers;
use App\Filament\Resources\UnitMembers\Pages\ViewUnitMember;
use App\Filament\Resources\UnitMembers\Schemas\UnitMemberForm;
use App\Filament\Resources\UnitMembers\Schemas\UnitMemberInfolist;
use App\Filament\Resources\UnitMembers\Tables\UnitMembersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class UnitMemberResource extends Resource
{
    protected static ?string $model = UnitMember::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Wilayah & Kelembagaan';

    protected static ?string $navigationLabel = 'Pengurus Unit';

    protected static ?string $modelLabel = 'Pengurus Unit';

    protected static ?string $pluralModelLabel = 'Pengurus Unit Karang Taruna';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function form(Schema $schema): Schema
    {
        return UnitMemberForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnitMemberInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitMembersTable::configure($table);
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
            'index' => ListUnitMembers::route('/'),
            'create' => CreateUnitMember::route('/create'),
            'view' => ViewUnitMember::route('/{record}'),
            'edit' => EditUnitMember::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->forUser();
    }
}
