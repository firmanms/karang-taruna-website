<?php

namespace App\Filament\Resources\RefVillages\Pages;

use App\Filament\Resources\RefVillages\RefVillageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRefVillage extends EditRecord
{
    protected static string $resource = RefVillageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
