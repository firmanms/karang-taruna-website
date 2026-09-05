<?php

namespace App\Filament\Resources\PpksBeneficiaries\Pages;

use App\Filament\Resources\PpksBeneficiaries\PpksBeneficiaryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPpksBeneficiary extends EditRecord
{
    protected static string $resource = PpksBeneficiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
