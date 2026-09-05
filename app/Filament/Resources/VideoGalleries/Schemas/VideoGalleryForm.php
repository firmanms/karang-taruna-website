<?php

namespace App\Filament\Resources\VideoGalleries\Schemas;

use Filament\Forms\Components\FileUpload;
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

class VideoGalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Video Liputan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul Video Liputan')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('video_platform')
                                ->label('Platform Video')
                                ->options([
                                    'youtube' => 'YouTube',
                                    'vimeo' => 'Vimeo',
                                    'local_mp4' => 'File MP4 Lokal',
                                ])
                                ->default('youtube')
                                ->required(),
                            TextInput::make('video_url')
                                ->label('URL Video / Embed Link')
                                ->placeholder('https://www.youtube.com/watch?v=...')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('video_id')
                                ->label('Video ID (Opsional)')
                                ->placeholder('Contoh: dQw4w9WgXcQ'),
                            TextInput::make('duration_text')
                                ->label('Durasi Video')
                                ->placeholder('Contoh: 05:24'),
                        ]),
                        Textarea::make('description')
                            ->label('Deskripsi Video')
                            ->rows(3),
                    ]),

                Section::make('Media Thumbnail & Pengaturan')
                    ->schema([
                        FileUpload::make('thumbnail_path')
                            ->label('Custom Thumbnail Image')
                            ->disk('public')
                            ->directory('galleries/videos')
                            ->image()
                            ->maxSize(3072),
                        Select::make('unit_id')
                            ->label('Unit Lembaga Pengunggah')
                            ->relationship('unit', 'unit_name', fn (Builder $query) => $query->forUser())
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->user()?->unit_id)
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai Video Utama di Beranda')
                            ->default(false),
                    ]),
            ]);
    }
}
