<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', $state ? Str::slug($state).'-'.strtolower(Str::random(5)) : '')),
                            Hidden::make('slug')
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
                                ->live()
                                ->default(fn () => auth()->user()?->unit_id)
                                ->afterStateUpdated(function (Set $set, ?int $state) {
                                    if ($state) {
                                        $unit = KarangTarunaUnit::find($state);
                                        if ($unit) {
                                            $set('district_id', $unit->district_id);
                                            $set('village_id', $unit->village_id);
                                        }
                                    }
                                })
                                ->required(),
                            Select::make('district_id')
                                ->label('Kecamatan Terkait')
                                ->relationship('district', 'name', fn (Builder $query) => auth()->user()?->isAdminKecamatan() && auth()->user()?->unit?->district_id ? $query->where('id', auth()->user()->unit->district_id) : $query)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn () => auth()->user()?->unit?->district_id)
                                ->disabled(fn () => auth()->user()?->isAdminKecamatan() || auth()->user()?->isAdminDesa())
                                ->dehydrated(),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan Terkait')
                                ->options(function (Get $get) {
                                    $districtId = $get('district_id') ?: auth()->user()?->unit?->district_id;
                                    $user = auth()->user();

                                    return RefVillage::query()
                                        ->when($districtId, fn ($query, $dId) => $query->where('district_id', $dId))
                                        ->when($user?->isAdminDesa() && $user?->unit?->village_id, fn ($query) => $query->where('id', $user->unit->village_id))
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->searchable()
                                ->default(fn () => auth()->user()?->unit?->village_id)
                                ->disabled(fn () => auth()->user()?->isAdminDesa())
                                ->dehydrated(),
                        ]),
                        Select::make('news_scope')
                            ->label('Cakupan Berita')
                            ->options(function () {
                                $user = auth()->user();
                                if ($user?->isAdminKecamatan() || $user?->isAdminDesa()) {
                                    return [
                                        'daerah' => 'Tingkat Wilayah / Desa (Daerah)',
                                    ];
                                }

                                return [
                                    'pusat' => 'Tingkat Kabupaten (Pusat)',
                                    'daerah' => 'Tingkat Wilayah / Desa (Daerah)',
                                ];
                            })
                            ->default('daerah')
                            ->disabled(fn () => auth()->user()?->isAdminKecamatan() || auth()->user()?->isAdminDesa())
                            ->dehydrated()
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Jadikan Berita Utama / Pilihan di Beranda')
                            ->default(false)
                            ->visible(fn () => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()),
                    ]),
            ]);
    }
}
