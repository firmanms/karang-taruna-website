<?php

namespace App\Filament\Resources\Events\Tables;

use App\Domain\Content\Models\Event;
use App\Services\ApprovalWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_image')
                    ->label('Poster')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('event_date')
                    ->label('Tanggal Pelaksanaan')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('location_venue')
                    ->label('Lokasi / Venue')
                    ->searchable(),
                TextColumn::make('unit.unit_name')
                    ->label('Penyelenggara')
                    ->searchable(),
                TextColumn::make('event_status')
                    ->label('Status Acara')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Mendatang' => 'info',
                        'Sedang Berlangsung' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('approval_status')
                    ->label('Moderasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending_approval' => 'warning',
                        'revision_required' => 'danger',
                        'rejected' => 'secondary',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('event_status')
                    ->label('Status Acara')
                    ->options([
                        'Mendatang' => 'Mendatang',
                        'Sedang Berlangsung' => 'Sedang Berlangsung',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
                SelectFilter::make('approval_status')
                    ->label('Status Moderasi')
                    ->options([
                        'draft' => 'Draft',
                        'pending_approval' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'revision_required' => 'Perlu Revisi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('submit')
                    ->label('Ajukan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn (Event $record): bool => in_array($record->approval_status, ['draft', 'revision_required']))
                    ->requiresConfirmation()
                    ->action(function (Event $record) {
                        app(ApprovalWorkflowService::class)->submitForApproval($record, auth()->user(), 'event');
                        Notification::make()->title('Agenda Kegiatan Telah Diajukan')->success()->send();
                    }),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Event $record): bool => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator())
                    ->requiresConfirmation()
                    ->action(function (Event $record) {
                        app(ApprovalWorkflowService::class)->approveContent($record, auth()->user(), 'event');
                        Notification::make()->title('Agenda Kegiatan Telah Disetujui')->success()->send();
                    }),
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (Event $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->approval_status === 'pending_approval')
                    ->form([
                        Textarea::make('revision_notes')
                            ->label('Catatan Revisi Agenda')
                            ->required(),
                    ])
                    ->action(function (Event $record, array $data) {
                        app(ApprovalWorkflowService::class)->requestRevision($record, auth()->user(), 'event', $data['revision_notes']);
                        Notification::make()->title('Instruksi Revisi Agenda Dikirimkan')->warning()->send();
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
