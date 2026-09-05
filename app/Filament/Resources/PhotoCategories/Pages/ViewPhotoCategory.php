<?php

namespace App\Filament\Resources\PhotoCategories\Pages;

use App\Filament\Resources\PhotoCategories\PhotoCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPhotoCategory extends ViewRecord
{
    protected static string $resource = PhotoCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
