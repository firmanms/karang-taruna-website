<?php

namespace App\Filament\Resources\RefDistricts\Pages;

use App\Filament\Resources\RefDistricts\RefDistrictResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRefDistrict extends ViewRecord
{
    protected static string $resource = RefDistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
