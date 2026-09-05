<?php

namespace App\Filament\Resources\PpksCategories\Pages;

use App\Filament\Resources\PpksCategories\PpksCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPpksCategories extends ListRecords
{
    protected static string $resource = PpksCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
