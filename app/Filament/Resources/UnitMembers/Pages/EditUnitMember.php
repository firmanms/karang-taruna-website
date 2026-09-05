<?php

namespace App\Filament\Resources\UnitMembers\Pages;

use App\Filament\Resources\UnitMembers\UnitMemberResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUnitMember extends EditRecord
{
    protected static string $resource = UnitMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
