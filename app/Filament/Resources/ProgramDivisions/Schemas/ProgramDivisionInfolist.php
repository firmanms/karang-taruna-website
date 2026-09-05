<?php

namespace App\Filament\Resources\ProgramDivisions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgramDivisionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('division_name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('order_index')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
