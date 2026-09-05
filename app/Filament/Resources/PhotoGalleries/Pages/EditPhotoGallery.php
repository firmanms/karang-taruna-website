<?php

namespace App\Filament\Resources\PhotoGalleries\Pages;

use App\Filament\Resources\PhotoGalleries\PhotoGalleryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPhotoGallery extends EditRecord
{
    protected static string $resource = PhotoGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
