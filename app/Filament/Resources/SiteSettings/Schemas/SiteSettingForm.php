<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Konfigurasi Pengaturan Situs')
                    ->description('Kelola parameter konfigurasi global website')
                    ->schema([
                        TextInput::make('setting_group')
                            ->label('Grup Pengaturan')
                            ->required()
                            ->default('general')
                            ->maxLength(100),
                        TextInput::make('setting_key')
                            ->label('Kunci Pengaturan (Key)')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('description')
                            ->label('Keterangan / Deskripsi Pengaturan')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('setting_value')
                            ->label('Nilai Pengaturan (Value)')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
