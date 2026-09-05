<?php

namespace App\Filament\Resources\Achievements;

use App\Domain\Content\Models\Achievement;
use App\Filament\Resources\Achievements\Pages\CreateAchievement;
use App\Filament\Resources\Achievements\Pages\EditAchievement;
use App\Filament\Resources\Achievements\Pages\ListAchievements;
use App\Filament\Resources\Achievements\Pages\ViewAchievement;
use App\Filament\Resources\Achievements\Schemas\AchievementForm;
use App\Filament\Resources\Achievements\Schemas\AchievementInfolist;
use App\Filament\Resources\Achievements\Tables\AchievementsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi & Informasi';

    protected static ?string $navigationLabel = 'Prestasi Pemuda';

    protected static ?string $modelLabel = 'Prestasi Pemuda';

    protected static ?string $pluralModelLabel = 'Prestasi Pemuda Wilayah';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function form(Schema $schema): Schema
    {
        return AchievementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AchievementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AchievementsTable::configure($table);
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
            'index' => ListAchievements::route('/'),
            'create' => CreateAchievement::route('/create'),
            'view' => ViewAchievement::route('/{record}'),
            'edit' => EditAchievement::route('/{record}/edit'),
        ];
    }
}
