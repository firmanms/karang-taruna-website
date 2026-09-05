<?php

namespace App\Filament\Resources\HeroSliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Banner Slide Beranda')
                    ->schema([
                        TextInput::make('subtitle_eyebrow')
                            ->label('Teks Pengantar / Eyebrow')
                            ->placeholder('Contoh: Selamat Datang di Portal Resmi'),
                        TextInput::make('title')
                            ->label('Judul Utama Slide')
                            ->required()
                            ->placeholder('Contoh: Karang Taruna Kabupaten Bandung'),
                        Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(2),
                        FileUpload::make('image_path')
                            ->label('Gambar Latar Banner')
                            ->disk('public')
                            ->directory('sliders')
                            ->image()
                            ->required()
                            ->maxSize(5120),
                        Grid::make(2)->schema([
                            TextInput::make('button_primary_text')
                                ->label('Label Tombol Utama')
                                ->placeholder('Jelajahi Profil'),
                            TextInput::make('button_primary_url')
                                ->label('URL Link Tombol Utama')
                                ->placeholder('/tentang-kami'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('button_secondary_text')
                                ->label('Label Tombol Kedua')
                                ->placeholder('Cek PPKS'),
                            TextInput::make('button_secondary_url')
                                ->label('URL Link Tombol Kedua')
                                ->placeholder('/cek-ppks'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('order_index')
                                ->label('Urutan Slide')
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }
}
