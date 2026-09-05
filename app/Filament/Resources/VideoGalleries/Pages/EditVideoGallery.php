<?php

namespace App\Filament\Resources\VideoGalleries\Pages;

use App\Filament\Resources\VideoGalleries\VideoGalleryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVideoGallery extends EditRecord
{
    protected static string $resource = VideoGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
