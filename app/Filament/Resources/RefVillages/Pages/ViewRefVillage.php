<?php

namespace App\Filament\Resources\RefVillages\Pages;

use App\Filament\Resources\RefVillages\RefVillageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRefVillage extends ViewRecord
{
    protected static string $resource = RefVillageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
