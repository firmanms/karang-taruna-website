<?php

namespace App\Filament\Resources\PhotoCategories\Pages;

use App\Filament\Resources\PhotoCategories\PhotoCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPhotoCategory extends EditRecord
{
    protected static string $resource = PhotoCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
