<?php

namespace App\Filament\Resources\KarangTarunaUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KarangTarunaUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('unit_name')
                    ->label('Nama Unit')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('unit_code')
                    ->label('Kode Unit')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('unit_level')
                    ->label('Level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'kabupaten' => 'danger',
                        'kecamatan' => 'warning',
                        'desa' => 'success',
                        'rw' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('village.name')
                    ->label('Desa / Kelurahan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('chairman_name')
                    ->label('Ketua')
                    ->searchable(),
                TextColumn::make('contact_phone')
                    ->label('Kontak')
                    ->searchable(),
                TextColumn::make('status_aktif')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Demisioner' => 'warning',
                        'Restrukturisasi' => 'info',
                        'PJS' => 'secondary',
                        'Nonaktif' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_verified')
                    ->label('Verifikasi')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('unit_level')
                    ->label('Level Unit')
                    ->options([
                        'kabupaten' => 'Kabupaten',
                        'kecamatan' => 'Kecamatan',
                        'desa' => 'Desa / Kelurahan',
                        'rw' => 'Tingkat RW',
                    ]),
                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status_aktif')
                    ->label('Status Keaktifan')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Demisioner' => 'Demisioner',
                        'Restrukturisasi' => 'Restrukturisasi',
                        'PJS' => 'PJS',
                        'Nonaktif' => 'Nonaktif',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
