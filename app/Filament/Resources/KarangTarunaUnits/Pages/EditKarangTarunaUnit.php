<?php

namespace App\Filament\Resources\KarangTarunaUnits\Pages;

use App\Filament\Resources\KarangTarunaUnits\KarangTarunaUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKarangTarunaUnit extends EditRecord
{
    protected static string $resource = KarangTarunaUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
