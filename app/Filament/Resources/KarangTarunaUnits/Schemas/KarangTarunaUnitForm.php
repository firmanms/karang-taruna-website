<?php

namespace App\Filament\Resources\KarangTarunaUnits\Schemas;

use App\Domain\Territory\Models\RefVillage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class KarangTarunaUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Wilayah & Level Lembaga')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('unit_level')
                                ->label('Tingkat / Level Unit')
                                ->options([
                                    'kabupaten' => 'Kabupaten',
                                    'kecamatan' => 'Kecamatan',
                                    'desa' => 'Desa / Kelurahan',
                                    'rw' => 'Tingkat RW',
                                ])
                                ->live()
                                ->required(),
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->relationship('district', 'name')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->visible(fn (Get $get): bool => in_array($get('unit_level'), ['kecamatan', 'desa', 'rw']))
                                ->required(fn (Get $get): bool => in_array($get('unit_level'), ['kecamatan', 'desa', 'rw'])),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan')
                                ->options(fn (Get $get): array => RefVillage::query()
                                    ->when($get('district_id'), fn ($query, $districtId) => $query->where('district_id', $districtId))
                                    ->pluck('name', 'id')
                                    ->toArray()
                                )
                                ->searchable()
                                ->live()
                                ->visible(fn (Get $get): bool => in_array($get('unit_level'), ['desa', 'rw']))
                                ->required(fn (Get $get): bool => in_array($get('unit_level'), ['desa', 'rw'])),
                        ]),
                        TextInput::make('rw_number')
                            ->label('Nomor RW (Bila Unit RW)')
                            ->visible(fn (Get $get): bool => $get('unit_level') === 'rw')
                            ->placeholder('Contoh: 05'),
                    ]),

                Section::make('Identitas Lembaga')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('unit_name')
                                ->label('Nama Unit Karang Taruna')
                                ->required()
                                ->placeholder('Contoh: Karang Taruna Kecamatan Soreang'),
                            TextInput::make('unit_code')
                                ->label('Kode Registrasi Unit')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('Contoh: KT-KEC-SOR'),
                        ]),
                        FileUpload::make('logo_path')
                            ->label('Logo Unit')
                            ->disk('public')
                            ->directory('units/logos')
                            ->image()
                            ->maxSize(2048),
                    ]),

                Section::make('Struktur Inti & Kontak')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('chairman_name')
                                ->label('Nama Ketua')
                                ->required(),
                            TextInput::make('secretary_name')
                                ->label('Nama Sekretaris'),
                            TextInput::make('treasurer_name')
                                ->label('Nama Bendahara'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('contact_phone')
                                ->label('Nomor Telepon / WhatsApp')
                                ->tel(),
                            TextInput::make('contact_email')
                                ->label('Email Resmi')
                                ->email(),
                        ]),
                    ]),

                Section::make('Sekretariat & Koordinat GIS')
                    ->schema([
                        Textarea::make('office_address')
                            ->label('Alamat Kantor Sekretariat')
                            ->rows(3),
                        FileUpload::make('office_photo')
                            ->label('Foto Gedung Sekretariat')
                            ->disk('public')
                            ->directory('units/offices')
                            ->image()
                            ->maxSize(3072),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')
                                ->label('Latitude GPS')
                                ->numeric()
                                ->placeholder('-7.02790000'),
                            TextInput::make('longitude')
                                ->label('Longitude GPS')
                                ->numeric()
                                ->placeholder('107.51860000'),
                        ]),
                    ]),

                Section::make('Legalitas SK, Masa Bakti & Status')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('sk_number')
                                ->label('Nomor SK Penetapan'),
                            FileUpload::make('sk_file_path')
                                ->label('Berkas SK (PDF)')
                                ->disk('public')
                                ->directory('units/sk')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(5120),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('period_start_year')
                                ->label('Tahun Mulai Bakti')
                                ->numeric()
                                ->required()
                                ->default((int) date('Y')),
                            TextInput::make('period_end_year')
                                ->label('Tahun Akhir Bakti')
                                ->numeric()
                                ->required()
                                ->default((int) date('Y') + 5),
                            TextInput::make('total_members')
                                ->label('Estimasi Jumlah Anggota')
                                ->numeric()
                                ->default(0),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('status_aktif')
                                ->label('Status Keaktifan Organisasi')
                                ->options([
                                    'Aktif' => 'Aktif',
                                    'Demisioner' => 'Demisioner',
                                    'Restrukturisasi' => 'Restrukturisasi',
                                    'PJS' => 'PJS (Pejabat Sementara)',
                                    'Nonaktif' => 'Nonaktif',
                                ])
                                ->default('Aktif')
                                ->required(),
                            Toggle::make('is_verified')
                                ->label('Terverifikasi Kabupaten')
                                ->default(true)
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
