<?php

namespace App\Filament\Resources\BudgetSources\Pages;

use App\Filament\Resources\BudgetSources\BudgetSourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBudgetSources extends ListRecords
{
    protected static string $resource = BudgetSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
