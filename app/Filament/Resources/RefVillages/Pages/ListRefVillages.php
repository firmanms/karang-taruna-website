<?php

namespace App\Filament\Resources\RefVillages\Pages;

use App\Filament\Resources\RefVillages\RefVillageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRefVillages extends ListRecords
{
    protected static string $resource = RefVillageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
