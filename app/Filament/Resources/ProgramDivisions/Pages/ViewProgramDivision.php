<?php

namespace App\Filament\Resources\ProgramDivisions\Pages;

use App\Filament\Resources\ProgramDivisions\ProgramDivisionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramDivision extends ViewRecord
{
    protected static string $resource = ProgramDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
