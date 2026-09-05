<?php

namespace App\Filament\Resources\RefDistricts\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RefDistrictForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Kecamatan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Kecamatan')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                            TextInput::make('kemendagri_code')
                                ->label('Kode Kemendagri')
                                ->placeholder('Contoh: 32.04.05')
                                ->required(),
                        ]),
                    ]),

                Section::make('Data Spasial GIS')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('latitude_center')
                                ->label('Latitude Pusat')
                                ->numeric(),
                            TextInput::make('longitude_center')
                                ->label('Longitude Pusat')
                                ->numeric(),
                        ]),
                        Textarea::make('geojson_boundary')
                            ->label('GeoJSON Poligon Batas Spasial')
                            ->rows(5)
                            ->placeholder('{"type": "Polygon", "coordinates": [...]}'),
                    ]),
            ]);
    }
}
