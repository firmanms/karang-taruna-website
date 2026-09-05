<?php

namespace App\Filament\Resources\HeroSliders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HeroSliderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('subtitle_eyebrow')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image_path'),
                TextEntry::make('button_primary_text')
                    ->placeholder('-'),
                TextEntry::make('button_primary_url')
                    ->placeholder('-'),
                TextEntry::make('button_secondary_text')
                    ->placeholder('-'),
                TextEntry::make('button_secondary_url')
                    ->placeholder('-'),
                TextEntry::make('order_index')
                    ->numeric(),
                IconEntry::make('is_active')
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
