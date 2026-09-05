<?php

namespace App\Filament\Pages;

use App\Domain\Content\Models\ContentApprovalLog;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ApprovalHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Moderasi & Persetujuan';

    protected static ?string $navigationLabel = 'Riwayat Moderasi';

    protected static ?string $title = 'Log & Riwayat Moderasi Konten';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected string $view = 'filament.pages.approval-history';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContentApprovalLog::query()->with(['submitter', 'reviewer'])->latest('action_timestamp')
            )
            ->columns([
                TextColumn::make('content_type')
                    ->label('Modul Konten')
                    ->badge(),
                TextColumn::make('action_status')
                    ->label('Tindakan Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'submitted' => 'info',
                        'revision_required' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('submitter.name')
                    ->label('Pengusul')
                    ->searchable(),
                TextColumn::make('reviewer.name')
                    ->label('Verifikator')
                    ->placeholder('Menunggu Reviu')
                    ->searchable(),
                TextColumn::make('review_notes')
                    ->label('Catatan / Alasan')
                    ->limit(60)
                    ->tooltip(fn ($record): ?string => $record->review_notes),
                TextColumn::make('action_timestamp')
                    ->label('Waktu Aksi')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('action_status')
                    ->label('Status Aksi')
                    ->options([
                        'submitted' => 'Diajukan (Submitted)',
                        'approved' => 'Disetujui (Approved)',
                        'revision_required' => 'Perlu Revisi (Revision Required)',
                        'rejected' => 'Ditolak (Rejected)',
                    ]),
                SelectFilter::make('content_type')
                    ->label('Tipe Konten')
                    ->options([
                        'article' => 'Berita (Article)',
                        'event' => 'Agenda (Event)',
                        'work_program' => 'Program Kerja',
                        'achievement' => 'Prestasi',
                        'gallery_photo' => 'Galeri Foto',
                        'gallery_video' => 'Galeri Video',
                        'ppks_beneficiary' => 'Warga PPKS',
                        'download' => 'Unduhan Dokumen',
                    ]),
            ]);
    }
}
