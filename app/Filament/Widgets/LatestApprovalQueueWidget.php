<?php

namespace App\Filament\Widgets;

use App\Domain\Content\Models\ContentApprovalLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestApprovalQueueWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Antrean & Riwayat Moderasi Konten Terbaru';

    public static function canView(): bool
    {
        return auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContentApprovalLog::query()->with(['submitter', 'reviewer'])->latest('action_timestamp')->limit(8)
            )
            ->columns([
                TextColumn::make('content_type')
                    ->label('Tipe Modul')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'article' => 'Berita & Artikel',
                        'event' => 'Agenda Kegiatan',
                        'work_program' => 'Program Kerja',
                        'achievement' => 'Prestasi Pemuda',
                        'download' => 'Dokumen Unduhan',
                        default => ucfirst($state),
                    }),
                TextColumn::make('submitter.name')
                    ->label('Diajukan Oleh')
                    ->default('-')
                    ->description(fn ($record) => $record->submitter?->unit?->unit_name),
                TextColumn::make('action_status')
                    ->label('Aksi Moderasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending_approval', 'submitted' => 'warning',
                        'revision_required' => 'danger',
                        'rejected' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'submitted', 'pending_approval' => 'Diajukan',
                        'revision_required' => 'Diminta Revisi',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    }),
                TextColumn::make('reviewer.name')
                    ->label('Verifikator')
                    ->default('-'),
                TextColumn::make('review_notes')
                    ->label('Catatan Moderasi')
                    ->limit(40)
                    ->default('-'),
                TextColumn::make('action_timestamp')
                    ->label('Waktu Aksi')
                    ->since()
                    ->sortable(),
            ]);
    }
}
