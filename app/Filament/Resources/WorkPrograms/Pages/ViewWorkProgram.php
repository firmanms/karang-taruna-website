<?php

namespace App\Filament\Resources\WorkPrograms\Pages;

use App\Filament\Resources\WorkPrograms\WorkProgramResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkProgram extends ViewRecord
{
    protected static string $resource = WorkProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
