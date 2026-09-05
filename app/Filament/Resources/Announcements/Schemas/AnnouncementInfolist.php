<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AnnouncementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('unit.id')
                    ->label('Unit'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('announcement_number')
                    ->placeholder('-'),
                TextEntry::make('category')
                    ->badge(),
                TextEntry::make('excerpt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('attachment_file')
                    ->placeholder('-'),
                TextEntry::make('valid_until')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('is_pinned')
                    ->boolean(),
                TextEntry::make('approval_status')
                    ->badge(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('views_count')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
