<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Domain\Territory\Models\RefVillage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kegiatan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Nama Agenda / Kegiatan')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('category_id')
                                ->label('Kategori Kegiatan')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('organizer')
                                ->label('Penyelenggara / Panitia')
                                ->required()
                                ->placeholder('Contoh: Karang Taruna Desa Soreang'),
                        ]),
                        Textarea::make('description')
                            ->label('Deskripsi & Rundown Kegiatan')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Jadwal & Lokasi')
                    ->schema([
                        Grid::make(3)->schema([
                            DatePicker::make('event_date')
                                ->label('Tanggal Kegiatan')
                                ->required(),
                            TimePicker::make('start_time')
                                ->label('Waktu Mulai'),
                            TimePicker::make('end_time')
                                ->label('Waktu Selesai'),
                        ]),
                        TextInput::make('location_venue')
                            ->label('Nama Lokasi / Tempat (Venue)')
                            ->required()
                            ->placeholder('Contoh: Gedung Serbaguna Desa Soreang'),
                        Textarea::make('location_address')
                            ->label('Alamat Lengkap Venue')
                            ->rows(2),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')
                                ->label('Latitude Venue (GPS)')
                                ->numeric(),
                            TextInput::make('longitude')
                                ->label('Longitude Venue (GPS)')
                                ->numeric(),
                        ]),
                    ]),

                Section::make('Unit, Registrasi & Media')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('unit_id')
                                ->label('Unit Lembaga Penyelenggara')
                                ->relationship('unit', 'unit_name')
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->required(),
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->relationship('district', 'name')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn () => auth()->user()?->unit?->district_id),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan')
                                ->options(fn (Get $get): array => RefVillage::query()
                                    ->when($get('district_id'), fn ($query, $districtId) => $query->where('district_id', $districtId))
                                    ->pluck('name', 'id')
                                    ->toArray()
                                )
                                ->searchable()
                                ->default(fn () => auth()->user()?->unit?->village_id),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('registration_link')
                                ->label('Link Pendaftaran / Formulir (Opsional)')
                                ->url(),
                            Select::make('event_status')
                                ->label('Status Acara')
                                ->options([
                                    'Mendatang' => 'Mendatang (Upcoming)',
                                    'Sedang Berlangsung' => 'Sedang Berlangsung (Live)',
                                    'Selesai' => 'Selesai (Completed)',
                                    'Dibatalkan' => 'Dibatalkan (Cancelled)',
                                ])
                                ->default('Mendatang')
                                ->required(),
                        ]),
                        FileUpload::make('thumbnail_image')
                            ->label('Banner / Poster Acara')
                            ->disk('public')
                            ->directory('events')
                            ->image()
                            ->maxSize(3072),
                    ]),
            ]);
    }
}
