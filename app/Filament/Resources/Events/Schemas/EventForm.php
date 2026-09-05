<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Kegiatan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Nama Agenda / Kegiatan')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', $state ? Str::slug($state).'-'.strtolower(Str::random(5)) : '')),
                            Hidden::make('slug')
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
                                ->relationship('unit', 'unit_name', fn (Builder $query) => $query->forUser())
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->afterStateUpdated(function (Set $set, ?int $state) {
                                    if ($state) {
                                        $unit = KarangTarunaUnit::find($state);
                                        if ($unit) {
                                            $set('district_id', $unit->district_id);
                                            $set('village_id', $unit->village_id);
                                        }
                                    }
                                })
                                ->required(),
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->relationship('district', 'name', fn (Builder $query) => auth()->user()?->isAdminKecamatan() && auth()->user()?->unit?->district_id ? $query->where('id', auth()->user()->unit->district_id) : $query)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn () => auth()->user()?->unit?->district_id)
                                ->disabled(fn () => auth()->user()?->isAdminKecamatan() || auth()->user()?->isAdminDesa())
                                ->dehydrated(),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan')
                                ->options(function (Get $get) {
                                    $districtId = $get('district_id') ?: auth()->user()?->unit?->district_id;
                                    $user = auth()->user();

                                    return RefVillage::query()
                                        ->when($districtId, fn ($query, $dId) => $query->where('district_id', $dId))
                                        ->when($user?->isAdminDesa() && $user?->unit?->village_id, fn ($query) => $query->where('id', $user->unit->village_id))
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->searchable()
                                ->default(fn () => auth()->user()?->unit?->village_id)
                                ->disabled(fn () => auth()->user()?->isAdminDesa())
                                ->dehydrated(),
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
