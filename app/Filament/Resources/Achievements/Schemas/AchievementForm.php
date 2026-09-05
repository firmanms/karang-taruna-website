<?php

namespace App\Filament\Resources\Achievements\Schemas;

use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Prestasi / Penghargaan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Nama Penghargaan / Prestasi')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('recipient_name')
                                ->label('Nama Penerima / Delegasi')
                                ->required()
                                ->placeholder('Nama perorangan atau tim pemuda'),
                            Select::make('achievement_level')
                                ->label('Tingkat Kejuaraan')
                                ->options([
                                    'Kabupaten' => 'Tingkat Kabupaten',
                                    'Provinsi' => 'Tingkat Provinsi',
                                    'Nasional' => 'Tingkat Nasional',
                                    'Internasional' => 'Tingkat Internasional',
                                ])
                                ->default('Kabupaten')
                                ->required(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('category_field')
                                ->label('Bidang Prestasi')
                                ->placeholder('Contoh: Inovasi Teknologi, Olahraga, Lingkungan')
                                ->required(),
                            TextInput::make('year')
                                ->label('Tahun Perolehan')
                                ->numeric()
                                ->default((int) date('Y'))
                                ->required(),
                            TextInput::make('rank_position')
                                ->label('Peringkat / Juara')
                                ->placeholder('Contoh: Juara 1, Medali Emas')
                                ->required(),
                        ]),
                        TextInput::make('awarded_by')
                            ->label('Pemberi Penghargaan / Penyelenggara')
                            ->required()
                            ->placeholder('Contoh: Kementerian Pemuda dan Olahraga RI'),
                        Textarea::make('description')
                            ->label('Deskripsi / Cerita Singkat Prestasi')
                            ->rows(3),
                    ]),

                Section::make('Bukti Sertifikat & Unit')
                    ->schema([
                        FileUpload::make('certificate_image')
                            ->label('Foto Sertifikat / Piagam / Dokumentasi')
                            ->disk('public')
                            ->directory('achievements')
                            ->image()
                            ->maxSize(4096),
                        Grid::make(3)->schema([
                            Select::make('unit_id')
                                ->label('Unit Lembaga Asal')
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
                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai Prestasi Unggulan di Beranda')
                            ->default(false)
                            ->visible(fn () => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()),
                    ]),
            ]);
    }
}
