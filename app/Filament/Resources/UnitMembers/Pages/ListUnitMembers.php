<?php

namespace App\Filament\Resources\UnitMembers\Pages;

use App\Filament\Resources\UnitMembers\UnitMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnitMembers extends ListRecords
{
    protected static string $resource = UnitMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
