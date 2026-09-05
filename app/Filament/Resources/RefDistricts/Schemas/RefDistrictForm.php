<?php

namespace App\Filament\Resources\RefDistricts\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RefDistrictForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identitas Kecamatan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Kecamatan')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', $state ? Str::slug($state).'-'.strtolower(Str::random(5)) : '')),
                            Hidden::make('slug')
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
