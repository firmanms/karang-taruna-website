<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Akun Pengguna')
                    ->description('Kelola data profil, email, dan password login')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            TextInput::make('password')
                                ->label('Kata Sandi (Password)')
                                ->password()
                                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->maxLength(255)
                                ->revealable(),
                            TextInput::make('phone')
                                ->label('Nomor WhatsApp / Telepon')
                                ->tel()
                                ->maxLength(30),
                        ]),
                        FileUpload::make('avatar')
                            ->label('Foto Profil (Avatar)')
                            ->disk('public')
                            ->directory('avatars')
                            ->image()
                            ->avatar()
                            ->maxSize(2048),
                    ]),

                Section::make('Hak Akses & Penugasan Wilayah')
                    ->description('Atur tingkat otorisasi, unit kerja Karang Taruna, dan status keaktifan akun')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('role_id')
                                ->label('Peran / Role')
                                ->relationship('role', 'role_name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('unit_id')
                                ->label('Unit Lembaga Karang Taruna')
                                ->relationship('unit', 'unit_name')
                                ->searchable()
                                ->preload()
                                ->placeholder('Pusat / Tidak Terikat Unit Khusus'),
                        ]),
                        Grid::make(2)->schema([
                            Toggle::make('is_active')
                                ->label('Status Akun Aktif')
                                ->helperText('Jika non-aktif, pengguna tidak dapat masuk ke panel admin')
                                ->default(true),
                            Toggle::make('can_auto_publish')
                                ->label('Hak Publikasi Langsung (Bypass Moderasi)')
                                ->helperText('Jika aktif, konten berita/agenda langsung tayang tanpa approval verifikator')
                                ->default(false),
                        ]),
                    ]),
            ]);
    }
}
