<?php

namespace App\Filament\Resources\ProfileOrganizations\Pages;

use App\Filament\Resources\ProfileOrganizations\ProfileOrganizationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProfileOrganization extends EditRecord
{
    protected static string $resource = ProfileOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
