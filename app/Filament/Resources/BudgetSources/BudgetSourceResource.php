<?php

namespace App\Filament\Resources\BudgetSources;

use App\Domain\Content\Models\BudgetSource;
use App\Filament\Resources\BudgetSources\Pages\CreateBudgetSource;
use App\Filament\Resources\BudgetSources\Pages\EditBudgetSource;
use App\Filament\Resources\BudgetSources\Pages\ListBudgetSources;
use App\Filament\Resources\BudgetSources\Schemas\BudgetSourceForm;
use App\Filament\Resources\BudgetSources\Tables\BudgetSourcesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BudgetSourceResource extends Resource
{
    protected static ?string $model = BudgetSource::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Sumber Anggaran';

    protected static ?string $modelLabel = 'Sumber Anggaran';

    protected static ?string $pluralModelLabel = 'Sumber Anggaran';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return BudgetSourceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BudgetSourcesTable::configure($table);
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
            'index' => ListBudgetSources::route('/'),
            'create' => CreateBudgetSource::route('/create'),
            'edit' => EditBudgetSource::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperadmin() ?? false;
    }
}
