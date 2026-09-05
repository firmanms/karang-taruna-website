<?php

namespace App\Filament\Resources\ProgramDivisions\Pages;

use App\Filament\Resources\ProgramDivisions\ProgramDivisionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramDivision extends EditRecord
{
    protected static string $resource = ProgramDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
