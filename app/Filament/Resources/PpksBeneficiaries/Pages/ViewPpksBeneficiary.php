<?php

namespace App\Filament\Resources\PpksBeneficiaries\Pages;

use App\Filament\Resources\PpksBeneficiaries\PpksBeneficiaryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPpksBeneficiary extends ViewRecord
{
    protected static string $resource = PpksBeneficiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
