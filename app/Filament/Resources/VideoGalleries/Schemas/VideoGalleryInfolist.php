<?php

namespace App\Filament\Resources\VideoGalleries\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VideoGalleryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('unit.id')
                    ->label('Unit'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('category'),
                TextEntry::make('video_platform')
                    ->badge(),
                TextEntry::make('video_url'),
                TextEntry::make('video_id')
                    ->placeholder('-'),
                TextEntry::make('thumbnail_path')
                    ->placeholder('-'),
                TextEntry::make('duration_text')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_featured')
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
