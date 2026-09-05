<?php

namespace App\Filament\Resources\WorkPrograms;

use App\Domain\Content\Models\WorkProgram;
use App\Filament\Resources\WorkPrograms\Pages\CreateWorkProgram;
use App\Filament\Resources\WorkPrograms\Pages\EditWorkProgram;
use App\Filament\Resources\WorkPrograms\Pages\ListWorkPrograms;
use App\Filament\Resources\WorkPrograms\Pages\ViewWorkProgram;
use App\Filament\Resources\WorkPrograms\Schemas\WorkProgramForm;
use App\Filament\Resources\WorkPrograms\Schemas\WorkProgramInfolist;
use App\Filament\Resources\WorkPrograms\Tables\WorkProgramsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class WorkProgramResource extends Resource
{
    protected static ?string $model = WorkProgram::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Program Kerja';

    protected static ?string $modelLabel = 'Program Kerja';

    protected static ?string $pluralModelLabel = 'Program Kerja Wilayah';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    public static function form(Schema $schema): Schema
    {
        return WorkProgramForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkProgramInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkProgramsTable::configure($table);
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
            'index' => ListWorkPrograms::route('/'),
            'create' => CreateWorkProgram::route('/create'),
            'view' => ViewWorkProgram::route('/{record}'),
            'edit' => EditWorkProgram::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->forUser();
    }
}
