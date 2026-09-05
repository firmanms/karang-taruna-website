<?php

namespace App\Filament\Resources\Announcements\Tables;

use App\Domain\Content\Models\Announcement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Pengumuman')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('unit.unit_name')
                    ->label('Penerbit')
                    ->searchable(),
                IconColumn::make('is_pinned')
                    ->label('Pinned')
                    ->boolean(),
                TextColumn::make('approval_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending_approval' => 'warning',
                        'rejected' => 'danger',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Edaran Resmi' => 'Edaran Resmi',
                        'Beasiswa' => 'Beasiswa',
                        'Seleksi' => 'Seleksi',
                        'Bantuan Sosial' => 'Bantuan Sosial',
                        'Umum' => 'Umum',
                    ]),
                SelectFilter::make('approval_status')
                    ->label('Status Moderasi')
                    ->options([
                        'draft' => 'Draft',
                        'pending_approval' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Announcement $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->approval_status !== 'approved')
                    ->requiresConfirmation()
                    ->action(function (Announcement $record) {
                        $record->update(['approval_status' => 'approved', 'approved_by' => auth()->id()]);
                        Notification::make()->title('Pengumuman Disetujui')->success()->send();
                    }),
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
