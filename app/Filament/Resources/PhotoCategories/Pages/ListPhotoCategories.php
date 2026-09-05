<?php

namespace App\Filament\Resources\PhotoCategories\Pages;

use App\Filament\Resources\PhotoCategories\PhotoCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhotoCategories extends ListRecords
{
    protected static string $resource = PhotoCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
