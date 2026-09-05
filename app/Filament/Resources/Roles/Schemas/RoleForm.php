<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Role & Tingkatan')
                    ->description('Kelola identitas peran dan tingkatan wilayah otorisasi')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('role_name')
                                ->label('Nama Role')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', $state ? Str::slug($state).'-'.strtolower(Str::random(5)) : '')),
                            Hidden::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Select::make('tier_level')
                                ->label('Tingkatan Wilayah')
                                ->options([
                                    'kabupaten' => 'Tingkat Kabupaten',
                                    'kecamatan' => 'Tingkat Kecamatan',
                                    'desa' => 'Tingkat Desa / Kelurahan',
                                ]),
                        ]),
                    ]),

                Section::make('Matriks Hak Akses (Permissions)')
                    ->description('Tentukan modul dan operasi apa saja yang dapat diakses oleh role ini')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Daftar Modul & Izin Akses')
                            ->options([
                                'articles.create' => 'Berita: Buat & Sunting Draf',
                                'articles.publish' => 'Berita: Publikasi Langsung',
                                'articles.delete' => 'Berita: Hapus Data',
                                'events.manage' => 'Agenda Kegiatan: Kelola',
                                'announcements.manage' => 'Pengumuman: Kelola',
                                'ppks.manage' => 'Data Sosial PPKS: Kelola',
                                'work_programs.manage' => 'Program Kerja: Kelola',
                                'moderation.approve' => 'Moderasi: Setujui / Tolak Konten',
                                'units.manage' => 'Unit Karang Taruna: Kelola Data',
                                'members.manage' => 'Pengurus / Anggota: Kelola',
                                'downloads.manage' => 'Pusat Unduhan: Kelola Dokumen',
                                'gallery.manage' => 'Galeri Foto & Video: Kelola',
                                'users.manage' => 'Manajemen Pengguna & Role: Kelola',
                                'settings.manage' => 'Pengaturan Global Website: Kelola',
                            ])
                            ->columns(2)
                            ->gridDirection('row'),
                    ]),
            ]);
    }
}
