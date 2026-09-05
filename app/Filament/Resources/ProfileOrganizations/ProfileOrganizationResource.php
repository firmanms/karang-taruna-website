<?php

namespace App\Filament\Resources\ProfileOrganizations;

use App\Domain\Settings\Models\ProfileOrganization;
use App\Filament\Resources\ProfileOrganizations\Pages\CreateProfileOrganization;
use App\Filament\Resources\ProfileOrganizations\Pages\EditProfileOrganization;
use App\Filament\Resources\ProfileOrganizations\Pages\ListProfileOrganizations;
use App\Filament\Resources\ProfileOrganizations\Pages\ViewProfileOrganization;
use App\Filament\Resources\ProfileOrganizations\Schemas\ProfileOrganizationForm;
use App\Filament\Resources\ProfileOrganizations\Schemas\ProfileOrganizationInfolist;
use App\Filament\Resources\ProfileOrganizations\Tables\ProfileOrganizationsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProfileOrganizationResource extends Resource
{
    protected static ?string $model = ProfileOrganization::class;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Situs & CMS';

    protected static ?string $navigationLabel = 'Profil Organisasi';

    protected static ?string $modelLabel = 'Profil Organisasi';

    protected static ?string $pluralModelLabel = 'Profil Organisasi Kabupaten';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    public static function form(Schema $schema): Schema
    {
        return ProfileOrganizationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProfileOrganizationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfileOrganizationsTable::configure($table);
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
            'index' => ListProfileOrganizations::route('/'),
            'create' => CreateProfileOrganization::route('/create'),
            'view' => ViewProfileOrganization::route('/{record}'),
            'edit' => EditProfileOrganization::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperadmin() ?? false;
    }
}
