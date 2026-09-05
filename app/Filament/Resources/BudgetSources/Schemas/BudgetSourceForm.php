<?php

namespace App\Filament\Resources\BudgetSources\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BudgetSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Sumber Anggaran')
                    ->description('Kelola master referensi sumber anggaran pembiayaan program kerja')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Sumber Anggaran')
                                ->placeholder('Contoh: APBD Kabupaten Bandung, BAZNAS, Swadana/Iuran')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            TextInput::make('code')
                                ->label('Kode Sumber (Opsional)')
                                ->placeholder('Contoh: APBD, CSR, SWD')
                                ->maxLength(50),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('order_index')
                                ->label('Nomor Urut')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->required(),
                        ]),
                        Textarea::make('description')
                            ->label('Keterangan / Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
