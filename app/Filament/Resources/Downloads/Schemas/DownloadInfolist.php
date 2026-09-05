<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DownloadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('document_title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('file_path'),
                TextEntry::make('file_format'),
                TextEntry::make('file_size_kb')
                    ->numeric(),
                TextEntry::make('download_count')
                    ->numeric(),
                TextEntry::make('release_date')
                    ->date(),
                TextEntry::make('approval_status')
                    ->badge(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_public')
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
