<?php

namespace App\Filament\Resources\PhotoGalleries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PhotoGalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Dokumentasi Foto')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul / Kegiatan Foto')
                                ->required(),
                            Select::make('category_id')
                                ->label('Album / Kategori Foto')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        FileUpload::make('image_path')
                            ->label('Upload File Foto')
                            ->disk('public')
                            ->directory('galleries/photos')
                            ->image()
                            ->required()
                            ->maxSize(5120),
                        Textarea::make('caption_description')
                            ->label('Keterangan / Caption Foto')
                            ->rows(3),
                    ]),

                Section::make('Wilayah & Informasi Kegiatan')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('unit_id')
                                ->label('Unit Lembaga')
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
                                ->default(fn () => auth()->user()?->unit?->district_id),
                            DatePicker::make('event_date')
                                ->label('Tanggal Kegiatan'),
                        ]),
                        TextInput::make('location')
                            ->label('Lokasi Kegiatan')
                            ->placeholder('Contoh: Lapangan Upakarti Soreang'),
                    ]),
            ]);
    }
}
