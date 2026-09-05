<?php

namespace App\Filament\Resources\ProfileOrganizations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfileOrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identitas & Legalitas Organisasi')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('org_name')
                                ->label('Nama Organisasi')
                                ->default('Karang Taruna Kabupaten Bandung')
                                ->required(),
                            TextInput::make('legal_basis')
                                ->label('Dasar Hukum / Landasan Hukum')
                                ->placeholder('Permensos No. 25 Tahun 2019'),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('sk_number')
                                ->label('Nomor SK Pengesahan Bupati'),
                            FileUpload::make('sk_file_path')
                                ->label('Berkas SK Bupati (PDF)')
                                ->disk('public')
                                ->directory('sk')
                                ->acceptedFileTypes(['application/pdf']),
                            TextInput::make('period_years')
                                ->label('Masa Bakti Periode')
                                ->placeholder('Contoh: Masa Bakti 2024 - 2029'),
                        ]),
                        FileUpload::make('logo_path')
                            ->label('Logo Resmi Organisasi')
                            ->disk('public')
                            ->directory('organization')
                            ->image(),
                    ]),

                Section::make('Visi & Sejarah')
                    ->schema([
                        Textarea::make('vision')
                            ->label('Pernyataan Visi')
                            ->rows(3)
                            ->required(),
                        RichEditor::make('history_content')
                            ->label('Sejarah & Kilas Balik')
                            ->columnSpanFull(),
                    ]),

                Section::make('Sekretariat & Kontak Resmi')
                    ->schema([
                        Textarea::make('address_office')
                            ->label('Alamat Sekretariat Kabupaten')
                            ->rows(2),
                        FileUpload::make('office_photo')
                            ->label('Foto Gedung Sekretariat')
                            ->disk('public')
                            ->directory('organization')
                            ->image(),
                        Grid::make(2)->schema([
                            TextInput::make('email_official')
                                ->label('Email Resmi')
                                ->email(),
                            TextInput::make('phone_official')
                                ->label('Telepon Resmi / Call Center'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')
                                ->label('Latitude GPS')
                                ->numeric(),
                            TextInput::make('longitude')
                                ->label('Longitude GPS')
                                ->numeric(),
                        ]),
                    ]),
            ]);
    }
}
