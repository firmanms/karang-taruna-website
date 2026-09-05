<?php

namespace App\Filament\Resources\KarangTarunaUnits\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KarangTarunaUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('unit_level')
                    ->badge(),
                TextEntry::make('district.name')
                    ->label('District')
                    ->placeholder('-'),
                TextEntry::make('village.name')
                    ->label('Village')
                    ->placeholder('-'),
                TextEntry::make('rw_number')
                    ->placeholder('-'),
                TextEntry::make('unit_name'),
                TextEntry::make('unit_code'),
                TextEntry::make('chairman_name'),
                TextEntry::make('secretary_name')
                    ->placeholder('-'),
                TextEntry::make('treasurer_name')
                    ->placeholder('-'),
                TextEntry::make('contact_phone')
                    ->placeholder('-'),
                TextEntry::make('contact_email')
                    ->placeholder('-'),
                TextEntry::make('office_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('office_photo')
                    ->placeholder('-'),
                TextEntry::make('latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('sk_number')
                    ->placeholder('-'),
                TextEntry::make('sk_file_path')
                    ->placeholder('-'),
                TextEntry::make('period_start_year'),
                TextEntry::make('period_end_year'),
                TextEntry::make('total_members')
                    ->numeric(),
                TextEntry::make('status_aktif')
                    ->badge(),
                TextEntry::make('logo_path')
                    ->placeholder('-'),
                IconEntry::make('is_verified')
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
