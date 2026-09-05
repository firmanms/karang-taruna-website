<?php

namespace App\Filament\Resources\NewsCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Kategori Berita')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Kategori')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Select::make('type')
                            ->label('Cakupan Kategori')
                            ->options([
                                'pusat' => 'Berita Pusat (Kabupaten)',
                                'daerah' => 'Kabar Daerah (Kecamatan / Desa)',
                                'umum' => 'Umum & Sosial',
                            ])
                            ->default('umum')
                            ->required(),
                    ]),
            ]);
    }
}
