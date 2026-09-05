<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Domain\Territory\Models\RefVillage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Utama & Konten Berita')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul Berita')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Select::make('category_id')
                            ->label('Kategori Berita')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan Singkat (Excerpt)')
                            ->rows(3)
                            ->placeholder('Ringkasan 1-2 kalimat untuk preview portal publik...'),
                        RichEditor::make('content')
                            ->label('Isi Lengkap Berita')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & Metadata')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Gambar Utama (Featured Image)')
                            ->disk('public')
                            ->directory('articles')
                            ->image()
                            ->required()
                            ->maxSize(3072),
                        TextInput::make('image_caption')
                            ->label('Keterangan Gambar (Caption)')
                            ->placeholder('Foto: Dokumentasi Karang Taruna'),
                        TagsInput::make('tags')
                            ->label('Tag / Kata Kunci')
                            ->placeholder('Tambahkan tag...'),
                    ]),

                Section::make('Unit Pengunggah & Wilayah')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('unit_id')
                                ->label('Unit Lembaga Pengusul')
                                ->relationship('unit', 'unit_name', fn (Builder $query) => $query->forUser())
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->required(),
                            Select::make('district_id')
                                ->label('Kecamatan Terkait')
                                ->relationship('district', 'name', fn (Builder $query) => auth()->user()?->isAdminKecamatan() && auth()->user()?->unit?->district_id ? $query->where('id', auth()->user()->unit->district_id) : $query)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn () => auth()->user()?->unit?->district_id),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan Terkait')
                                ->options(fn (Get $get): array => RefVillage::query()
                                    ->when($get('district_id'), fn ($query, $districtId) => $query->where('district_id', $districtId))
                                    ->pluck('name', 'id')
                                    ->toArray()
                                )
                                ->searchable()
                                ->default(fn () => auth()->user()?->unit?->village_id),
                        ]),
                        Select::make('news_scope')
                            ->label('Cakupan Berita')
                            ->options([
                                'pusat' => 'Tingkat Kabupaten (Pusat)',
                                'daerah' => 'Tingkat Wilayah / Desa (Daerah)',
                            ])
                            ->default('daerah')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Jadikan Berita Utama / Pilihan di Beranda')
                            ->default(false),
                    ]),
            ]);
    }
}
