<?php

namespace App\Filament\Resources\PhotoGalleries\Pages;

use App\Filament\Resources\PhotoGalleries\PhotoGalleryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPhotoGallery extends ViewRecord
{
    protected static string $resource = PhotoGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
