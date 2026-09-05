<?php

namespace App\Filament\Resources\PpksBeneficiaries;

use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Filament\Resources\PpksBeneficiaries\Pages\CreatePpksBeneficiary;
use App\Filament\Resources\PpksBeneficiaries\Pages\EditPpksBeneficiary;
use App\Filament\Resources\PpksBeneficiaries\Pages\ListPpksBeneficiaries;
use App\Filament\Resources\PpksBeneficiaries\Pages\ViewPpksBeneficiary;
use App\Filament\Resources\PpksBeneficiaries\Schemas\PpksBeneficiaryForm;
use App\Filament\Resources\PpksBeneficiaries\Tables\PpksBeneficiariesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PpksBeneficiaryResource extends Resource
{
    protected static ?string $model = PpksBeneficiary::class;

    protected static string|UnitEnum|null $navigationGroup = 'Layanan Sosial & PPKS';

    protected static ?string $navigationLabel = 'Data Warga PPKS';

    protected static ?string $modelLabel = 'Warga PPKS';

    protected static ?string $pluralModelLabel = 'Data Pemerlu Pelayanan Kesejahteraan Sosial';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PpksBeneficiaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpksBeneficiariesTable::configure($table);
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
            'index' => ListPpksBeneficiaries::route('/'),
            'create' => CreatePpksBeneficiary::route('/create'),
            'view' => ViewPpksBeneficiary::route('/{record}'),
            'edit' => EditPpksBeneficiary::route('/{record}/edit'),
        ];
    }
}
