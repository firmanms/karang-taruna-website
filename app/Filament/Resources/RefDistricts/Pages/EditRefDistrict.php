<?php

namespace App\Filament\Resources\RefDistricts\Pages;

use App\Filament\Resources\RefDistricts\RefDistrictResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRefDistrict extends EditRecord
{
    protected static string $resource = RefDistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
