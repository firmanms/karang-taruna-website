<?php

namespace App\Filament\Resources\KarangTarunaUnits\Pages;

use App\Filament\Resources\KarangTarunaUnits\KarangTarunaUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKarangTarunaUnits extends ListRecords
{
    protected static string $resource = KarangTarunaUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
