<?php

namespace App\Filament\Resources\PhotoCategories\Pages;

use App\Filament\Resources\PhotoCategories\PhotoCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePhotoCategory extends CreateRecord
{
    protected static string $resource = PhotoCategoryResource::class;
}
