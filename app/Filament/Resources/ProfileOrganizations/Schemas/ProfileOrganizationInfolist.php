<?php

namespace App\Filament\Resources\ProfileOrganizations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProfileOrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('org_name'),
                TextEntry::make('legal_basis')
                    ->placeholder('-'),
                TextEntry::make('history_content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('vision')
                    ->columnSpanFull(),
                TextEntry::make('logo_path')
                    ->placeholder('-'),
                TextEntry::make('sk_number')
                    ->placeholder('-'),
                TextEntry::make('sk_file_path')
                    ->placeholder('-'),
                TextEntry::make('period_years')
                    ->placeholder('-'),
                TextEntry::make('address_office')
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
                TextEntry::make('email_official')
                    ->placeholder('-'),
                TextEntry::make('phone_official')
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
