<?php

namespace App\Filament\Resources\WorkPrograms\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WorkProgramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('division.id')
                    ->label('Division'),
                TextEntry::make('unit.id')
                    ->label('Unit'),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('program_name'),
                TextEntry::make('slug'),
                TextEntry::make('program_code')
                    ->placeholder('-'),
                TextEntry::make('target_participants'),
                TextEntry::make('output_indicators')
                    ->columnSpanFull(),
                TextEntry::make('budget_amount')
                    ->numeric(),
                TextEntry::make('budget_source'),
                TextEntry::make('execution_time'),
                ImageEntry::make('poster_image')
                    ->placeholder('-'),
                TextEntry::make('short_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('detailed_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('progress_status')
                    ->badge(),
                TextEntry::make('approval_status')
                    ->badge(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('rejection_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_featured_home')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
