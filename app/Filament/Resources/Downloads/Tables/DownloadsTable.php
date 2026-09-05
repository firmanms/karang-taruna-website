<?php

namespace App\Filament\Resources\Downloads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DownloadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document_title')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(45),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('file_format')
                    ->label('Format')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('file_size_kb')
                    ->label('Ukuran (KB)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('download_count')
                    ->label('Diunduh')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_public')
                    ->label('Publik')
                    ->boolean(),
                TextColumn::make('release_date')
                    ->label('Tanggal Rilis')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
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
