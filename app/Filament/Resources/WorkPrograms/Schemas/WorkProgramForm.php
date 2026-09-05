<?php

namespace App\Filament\Resources\WorkPrograms\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class WorkProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Program Kerja')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('program_name')
                                ->label('Nama Program Kerja')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('division_id')
                                ->label('Bidang / Seksi Kerja')
                                ->relationship('division', 'division_name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('program_code')
                                ->label('Kode Program')
                                ->placeholder('Contoh: PROG-01/2026'),
                        ]),
                        TextInput::make('target_participants')
                            ->label('Target Sasaran / Peserta')
                            ->required()
                            ->placeholder('Contoh: 100 Pemuda Usia Produktif Desa Soreang'),
                        Textarea::make('output_indicators')
                            ->label('Indikator Capaian / Target Output')
                            ->required()
                            ->rows(3),
                        RichEditor::make('detailed_description')
                            ->label('Uraian Lengkap Rencana Kerja')
                            ->columnSpanFull(),
                    ]),

                Section::make('RAB, Pelaksanaan & Status')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('budget_amount')
                                ->label('Anggaran Biaya (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0.00),
                            TextInput::make('budget_source')
                                ->label('Sumber Anggaran')
                                ->default('APBD / Swadana'),
                            TextInput::make('execution_time')
                                ->label('Waktu / Jadwal Pelaksanaan')
                                ->required()
                                ->placeholder('Contoh: Triwulan II (Mei 2026)'),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('unit_id')
                                ->label('Unit Lembaga Pengusul')
                                ->relationship('unit', 'unit_name', fn (Builder $query) => $query->forUser())
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->required(),
                            Select::make('progress_status')
                                ->label('Status Progres')
                                ->options([
                                    'Rencana' => 'Rencana (Planning)',
                                    'Sedang Berjalan' => 'Sedang Berjalan (Ongoing)',
                                    'Selesai' => 'Selesai (Completed)',
                                    'Ditunda' => 'Ditunda (Postponed)',
                                ])
                                ->default('Rencana')
                                ->required(),
                        ]),
                        FileUpload::make('poster_image')
                            ->label('Poster / Dokumen Rencana')
                            ->disk('public')
                            ->directory('programs')
                            ->image()
                            ->maxSize(3072),
                        Toggle::make('is_featured_home')
                            ->label('Tampilkan sebagai Program Unggulan di Beranda')
                            ->default(false)
                            ->visible(fn () => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()),
                    ]),
            ]);
    }
}
