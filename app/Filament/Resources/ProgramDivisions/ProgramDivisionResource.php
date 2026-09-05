<?php

namespace App\Filament\Resources\ProgramDivisions;

use App\Domain\Content\Models\ProgramDivision;
use App\Filament\Resources\ProgramDivisions\Pages\CreateProgramDivision;
use App\Filament\Resources\ProgramDivisions\Pages\EditProgramDivision;
use App\Filament\Resources\ProgramDivisions\Pages\ListProgramDivisions;
use App\Filament\Resources\ProgramDivisions\Pages\ViewProgramDivision;
use App\Filament\Resources\ProgramDivisions\Schemas\ProgramDivisionForm;
use App\Filament\Resources\ProgramDivisions\Schemas\ProgramDivisionInfolist;
use App\Filament\Resources\ProgramDivisions\Tables\ProgramDivisionsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProgramDivisionResource extends Resource
{
    protected static ?string $model = ProgramDivision::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Bidang Program';

    protected static ?string $modelLabel = 'Bidang Program';

    protected static ?string $pluralModelLabel = 'Bidang Program Kerja';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    public static function form(Schema $schema): Schema
    {
        return ProgramDivisionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgramDivisionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramDivisionsTable::configure($table);
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
            'index' => ListProgramDivisions::route('/'),
            'create' => CreateProgramDivision::route('/create'),
            'view' => ViewProgramDivision::route('/{record}'),
            'edit' => EditProgramDivision::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperadmin() ?? false;
    }
}
