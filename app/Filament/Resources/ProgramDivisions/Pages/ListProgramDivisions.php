<?php

namespace App\Filament\Resources\ProgramDivisions\Pages;

use App\Filament\Resources\ProgramDivisions\ProgramDivisionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramDivisions extends ListRecords
{
    protected static string $resource = ProgramDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
