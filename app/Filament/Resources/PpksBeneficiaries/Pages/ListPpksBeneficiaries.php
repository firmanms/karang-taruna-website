<?php

namespace App\Filament\Resources\PpksBeneficiaries\Pages;

use App\Filament\Resources\PpksBeneficiaries\PpksBeneficiaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPpksBeneficiaries extends ListRecords
{
    protected static string $resource = PpksBeneficiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
