<?php

namespace App\Filament\Resources\RefVillages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RefVillageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('district.name')
                    ->label('District'),
                TextEntry::make('kemendagri_code'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('postal_code')
                    ->placeholder('-'),
                TextEntry::make('total_rw')
                    ->numeric(),
                TextEntry::make('total_rt')
                    ->numeric(),
                TextEntry::make('latitude_center')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude_center')
                    ->numeric()
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
