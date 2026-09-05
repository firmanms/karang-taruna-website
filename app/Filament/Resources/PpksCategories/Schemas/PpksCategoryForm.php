<?php

namespace App\Filament\Resources\PpksCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PpksCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori PPKS')
                    ->schema([
                        TextInput::make('category_code')
                            ->label('Kode Kategori PPKS')
                            ->placeholder('Contoh: PPKS-01, ADK, DIS')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        TextInput::make('category_name')
                            ->label('Nama Kategori / Jenis PPKS')
                            ->placeholder('Contoh: Anak Balita Terlantar, Penyandang Disabilitas')
                            ->required()
                            ->maxLength(150),
                        Textarea::make('description')
                            ->label('Deskripsi / Kriteria PPKS')
                            ->rows(4)
                            ->placeholder('Kriteria atau batasan pemerlu pelayanan kesejahteraan sosial...'),
                    ]),
            ]);
    }
}
