<?php

namespace App\Filament\Resources\ProfileOrganizations\Pages;

use App\Filament\Resources\ProfileOrganizations\ProfileOrganizationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProfileOrganization extends ViewRecord
{
    protected static string $resource = ProfileOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
