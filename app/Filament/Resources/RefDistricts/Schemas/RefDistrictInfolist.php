<?php

namespace App\Filament\Resources\RefDistricts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RefDistrictInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kemendagri_code'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('latitude_center')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude_center')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('geojson_boundary')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
