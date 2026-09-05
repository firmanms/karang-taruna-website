<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiteSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('setting_group'),
                TextEntry::make('setting_key'),
                TextEntry::make('setting_value')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
