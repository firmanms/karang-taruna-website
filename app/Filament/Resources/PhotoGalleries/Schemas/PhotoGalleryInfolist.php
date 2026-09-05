<?php

namespace App\Filament\Resources\PhotoGalleries\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PhotoGalleryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('unit.id')
                    ->label('Unit'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('district.name')
                    ->label('District')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('caption_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image_path'),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('event_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('order_index')
                    ->numeric(),
                TextEntry::make('approval_status')
                    ->badge(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
