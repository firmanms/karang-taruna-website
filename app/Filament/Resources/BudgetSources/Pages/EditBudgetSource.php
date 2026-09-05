<?php

namespace App\Filament\Resources\BudgetSources\Pages;

use App\Filament\Resources\BudgetSources\BudgetSourceResource;
use Filament\Resources\Pages\EditRecord;

class EditBudgetSource extends EditRecord
{
    protected static string $resource = BudgetSourceResource::class;
}
