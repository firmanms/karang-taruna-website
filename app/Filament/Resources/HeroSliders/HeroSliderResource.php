<?php

namespace App\Filament\Resources\HeroSliders;

use App\Domain\Settings\Models\HeroSlider;
use App\Filament\Resources\HeroSliders\Pages\CreateHeroSlider;
use App\Filament\Resources\HeroSliders\Pages\EditHeroSlider;
use App\Filament\Resources\HeroSliders\Pages\ListHeroSliders;
use App\Filament\Resources\HeroSliders\Pages\ViewHeroSlider;
use App\Filament\Resources\HeroSliders\Schemas\HeroSliderForm;
use App\Filament\Resources\HeroSliders\Schemas\HeroSliderInfolist;
use App\Filament\Resources\HeroSliders\Tables\HeroSlidersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeroSliderResource extends Resource
{
    protected static ?string $model = HeroSlider::class;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Situs & CMS';

    protected static ?string $navigationLabel = 'Hero Slider Beranda';

    protected static ?string $modelLabel = 'Hero Slider';

    protected static ?string $pluralModelLabel = 'Hero Slider Banner';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    public static function form(Schema $schema): Schema
    {
        return HeroSliderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HeroSliderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroSlidersTable::configure($table);
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
            'index' => ListHeroSliders::route('/'),
            'create' => CreateHeroSlider::route('/create'),
            'view' => ViewHeroSlider::route('/{record}'),
            'edit' => EditHeroSlider::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperadmin() ?? false;
    }
}
