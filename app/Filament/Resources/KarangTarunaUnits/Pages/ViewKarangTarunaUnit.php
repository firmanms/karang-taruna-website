<?php

namespace App\Filament\Resources\KarangTarunaUnits\Pages;

use App\Filament\Resources\KarangTarunaUnits\KarangTarunaUnitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKarangTarunaUnit extends ViewRecord
{
    protected static string $resource = KarangTarunaUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
