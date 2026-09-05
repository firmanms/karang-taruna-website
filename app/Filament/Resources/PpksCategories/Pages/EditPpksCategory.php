<?php

namespace App\Filament\Resources\PpksCategories\Pages;

use App\Filament\Resources\PpksCategories\PpksCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPpksCategory extends EditRecord
{
    protected static string $resource = PpksCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
