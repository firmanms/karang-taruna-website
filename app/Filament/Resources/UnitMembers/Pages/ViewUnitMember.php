<?php

namespace App\Filament\Resources\UnitMembers\Pages;

use App\Filament\Resources\UnitMembers\UnitMemberResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUnitMember extends ViewRecord
{
    protected static string $resource = UnitMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
