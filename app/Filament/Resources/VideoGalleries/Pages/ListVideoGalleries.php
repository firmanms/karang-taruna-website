<?php

namespace App\Filament\Resources\VideoGalleries\Pages;

use App\Filament\Resources\VideoGalleries\VideoGalleryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideoGalleries extends ListRecords
{
    protected static string $resource = VideoGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
