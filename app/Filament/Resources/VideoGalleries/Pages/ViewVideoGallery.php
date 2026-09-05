<?php

namespace App\Filament\Resources\VideoGalleries\Pages;

use App\Filament\Resources\VideoGalleries\VideoGalleryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVideoGallery extends ViewRecord
{
    protected static string $resource = VideoGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
