<?php

namespace App\Filament\Resources\RefVillages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RefVillageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Wilayah Administratif')
                    ->schema([
                        Select::make('district_id')
                            ->label('Kecamatan')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Desa / Kelurahan')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(ignoreRecord: true),
                            TextInput::make('kemendagri_code')
                                ->label('Kode Kemendagri')
                                ->placeholder('Contoh: 32.04.05.2001')
                                ->required(),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('type')
                                ->label('Jenis Wilayah')
                                ->options(['Desa' => 'Desa', 'Kelurahan' => 'Kelurahan'])
                                ->default('Desa')
                                ->required(),
                            TextInput::make('postal_code')
                                ->label('Kode Pos')
                                ->placeholder('40911'),
                            TextInput::make('total_rw')
                                ->label('Jumlah RW')
                                ->numeric()
                                ->default(0),
                        ]),
                    ]),

                Section::make('Koordinat Spasial GIS')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('latitude_center')
                                ->label('Latitude')
                                ->numeric(),
                            TextInput::make('longitude_center')
                                ->label('Longitude')
                                ->numeric(),
                        ]),
                    ]),
            ]);
    }
}
