<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DownloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dokumen Unduhan')
                    ->schema([
                        TextInput::make('document_title')
                            ->label('Judul Berkas / Dokumen')
                            ->required(),
                        Grid::make(2)->schema([
                            Select::make('category_id')
                                ->label('Kategori Dokumen')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            DatePicker::make('release_date')
                                ->label('Tanggal Rilis / Terbit')
                                ->default(now())
                                ->required(),
                        ]),
                        Textarea::make('description')
                            ->label('Deskripsi Singkat Berkas')
                            ->rows(2),
                        FileUpload::make('file_path')
                            ->label('File Berkas (PDF/DOCX/XLSX/ZIP)')
                            ->disk('public')
                            ->directory('downloads')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/zip',
                            ])
                            ->required()
                            ->maxSize(20480),
                        Grid::make(2)->schema([
                            TextInput::make('file_format')
                                ->label('Format File')
                                ->default('PDF')
                                ->required(),
                            TextInput::make('file_size_kb')
                                ->label('Ukuran File (KB)')
                                ->numeric()
                                ->default(0),
                        ]),
                        Toggle::make('is_public')
                            ->label('Tampilkan untuk Publik di Download Center')
                            ->default(true),
                    ]),
            ]);
    }
}
