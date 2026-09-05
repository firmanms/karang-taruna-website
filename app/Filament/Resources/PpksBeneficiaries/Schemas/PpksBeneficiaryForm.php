<?php

namespace App\Filament\Resources\PpksBeneficiaries\Schemas;

use App\Domain\Territory\Models\RefVillage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PpksBeneficiaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identitas Warga PPKS')
                    ->description('Pastikan data NIK dan Nama sesuai dengan KTP-el atau KK resmi.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nik')
                                ->label('Nomor Induk Kependudukan (NIK)')
                                ->placeholder('16 digit NIK')
                                ->required()
                                ->length(16)
                                ->numeric()
                                ->unique(ignoreRecord: true),
                            TextInput::make('full_name')
                                ->label('Nama Lengkap Warga')
                                ->placeholder('Contoh: Ahmad Subagja')
                                ->required()
                                ->maxLength(150),
                        ]),
                        Select::make('category_id')
                            ->label('Kategori / Jenis PPKS')
                            ->relationship('category', 'category_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Lokasi & Wilayah Domisili')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('unit_id')
                                ->label('Unit Pengusul')
                                ->relationship('unit', 'unit_name', fn (Builder $query) => $query->forUser())
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->required(),
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->relationship('district', 'name', fn (Builder $query) => auth()->user()?->isAdminKecamatan() && auth()->user()?->unit?->district_id ? $query->where('id', auth()->user()->unit->district_id) : $query)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn () => auth()->user()?->unit?->district_id)
                                ->required(),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan')
                                ->options(fn (Get $get): array => RefVillage::query()
                                    ->when($get('district_id'), fn ($query, $districtId) => $query->where('district_id', $districtId))
                                    ->pluck('name', 'id')
                                    ->toArray()
                                )
                                ->searchable()
                                ->default(fn () => auth()->user()?->unit?->village_id)
                                ->required(),
                        ]),
                        Textarea::make('address_detail')
                            ->label('Alamat Lengkap & Patokan Rumah (RT/RW)')
                            ->rows(3)
                            ->placeholder('Contoh: Jl. Raya Soreang No. 12, RT 03 / RW 05'),
                    ]),

                Section::make('Kondisi Sosial & Pendampingan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('social_assistance_status')
                                ->label('Status / Jenis Bantuan Sosial')
                                ->placeholder('Contoh: Usulan Bantuan Sembako, BPNT, PKH, Kursi Roda')
                                ->required()
                                ->default('Menunggu Intervensi Bansos'),
                            TextInput::make('mentor_unit')
                                ->label('Unit Lembaga Pendamping')
                                ->placeholder('Contoh: Karang Taruna Desa Soreang')
                                ->default('Karang Taruna Desa Setempat'),
                        ]),
                        DatePicker::make('last_survey_date')
                            ->label('Tanggal Survei Lapangan / Asesmen')
                            ->default(now()),
                    ]),

                Section::make('Status Verifikasi & Validasi Kabupaten')
                    ->schema([
                        Select::make('verification_status')
                            ->label('Status Verifikasi')
                            ->options([
                                'pending_verification' => 'Menunggu Verifikasi (Pending)',
                                'verified' => 'Terverifikasi (Verified)',
                                'rejected' => 'Ditolak / Tidak Memenuhi Syarat (Rejected)',
                            ])
                            ->default('pending_verification')
                            ->disabled(fn () => ! (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator())),
                        Textarea::make('verification_notes')
                            ->label('Catatan Hasil Verifikasi / Alasan Penolakan')
                            ->rows(3)
                            ->disabled(fn () => ! (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator())),
                    ]),
            ]);
    }
}
