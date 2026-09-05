<?php

namespace App\Filament\Resources\PhotoGalleries\Pages;

use App\Filament\Resources\PhotoGalleries\PhotoGalleryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePhotoGallery extends CreateRecord
{
    protected static string $resource = PhotoGalleryResource::class;
}
