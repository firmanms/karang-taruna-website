<?php

namespace App\Filament\Resources\UnitMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class UnitMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Data Pengurus')
                    ->schema([
                        Select::make('unit_id')
                            ->label('Unit Lembaga Karang Taruna')
                            ->relationship('unit', 'unit_name', fn (Builder $query) => $query->forUser())
                            ->searchable()
                            ->preload()
                            ->required(),
                        Grid::make(2)->schema([
                            TextInput::make('full_name')
                                ->label('Nama Lengkap')
                                ->required(),
                            TextInput::make('position_role')
                                ->label('Jabatan / Posisi')
                                ->placeholder('Contoh: Ketua, Sekretaris, Koordinator Bidang')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('division_section')
                                ->label('Bidang / Seksi Kerja')
                                ->placeholder('Contoh: Pemberdayaan Pemuda'),
                            TextInput::make('order_index')
                                ->label('Urutan Tampilan Struktur')
                                ->numeric()
                                ->default(0),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('phone')
                                ->label('No. WhatsApp / HP')
                                ->tel(),
                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email(),
                        ]),
                        FileUpload::make('photo_path')
                            ->label('Foto Pengurus')
                            ->disk('public')
                            ->directory('members')
                            ->image()
                            ->avatar(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
