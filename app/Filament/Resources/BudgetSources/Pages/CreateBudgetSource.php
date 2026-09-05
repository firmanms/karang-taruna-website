<?php

namespace App\Filament\Resources\BudgetSources\Pages;

use App\Filament\Resources\BudgetSources\BudgetSourceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBudgetSource extends CreateRecord
{
    protected static string $resource = BudgetSourceResource::class;
}
