<?php

namespace App\Filament\Resources\PpksBeneficiaries\Pages;

use App\Filament\Resources\PpksBeneficiaries\PpksBeneficiaryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePpksBeneficiary extends CreateRecord
{
    protected static string $resource = PpksBeneficiaryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['submitted_by'] = auth()->id();

        return $data;
    }
}
