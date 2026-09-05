<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DatePicker;
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
use Illuminate\Support\Str;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengumuman / Edaran')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul Pengumuman')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('announcement_number')
                                ->label('Nomor Surat / Edaran')
                                ->placeholder('Contoh: 005/KT-KAB/VI/2026'),
                            Select::make('category')
                                ->label('Kategori Pengumuman')
                                ->options([
                                    'Edaran Resmi' => 'Edaran Resmi',
                                    'Beasiswa' => 'Beasiswa & Pelatihan',
                                    'Seleksi' => 'Seleksi & Rekrutmen',
                                    'Bantuan Sosial' => 'Bantuan Sosial',
                                    'Umum' => 'Informasi Umum',
                                ])
                                ->default('Umum')
                                ->required(),
                        ]),
                        Textarea::make('excerpt')
                            ->label('Ringkasan Singkat')
                            ->rows(2),
                        RichEditor::make('content')
                            ->label('Isi Lengkap Pengumuman')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Lampiran & Masa Berlaku')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('valid_until')
                                ->label('Berlaku Hingga (Opsional)'),
                            Select::make('unit_id')
                                ->label('Unit Lembaga Penerbit')
                                ->relationship('unit', 'unit_name')
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->required(),
                        ]),
                        FileUpload::make('attachment_file')
                            ->label('Berkas Dokumen Lampiran (PDF)')
                            ->disk('public')
                            ->directory('announcements')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                        Toggle::make('is_pinned')
                            ->label('Sematkan di Baris Teratas (Pinned)')
                            ->default(false),
                    ]),
            ]);
    }
}
