<?php

namespace App\Filament\Resources\RefDistricts\Pages;

use App\Filament\Resources\RefDistricts\RefDistrictResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRefDistricts extends ListRecords
{
    protected static string $resource = RefDistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
