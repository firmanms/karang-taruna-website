<?php

namespace App\Filament\Resources\ProfileOrganizations\Pages;

use App\Filament\Resources\ProfileOrganizations\ProfileOrganizationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfileOrganizations extends ListRecords
{
    protected static string $resource = ProfileOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
