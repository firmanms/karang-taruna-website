<?php

namespace App\Filament\Resources\HeroSliders\Pages;

use App\Filament\Resources\HeroSliders\HeroSliderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHeroSlider extends ViewRecord
{
    protected static string $resource = HeroSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
