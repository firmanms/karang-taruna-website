<?php

namespace App\Filament\Resources\Achievements\Tables;

use App\Domain\Content\Models\Achievement;
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

class AchievementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('certificate_image')
                    ->label('Sertifikat')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Nama Prestasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(35),
                TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable(),
                TextColumn::make('achievement_level')
                    ->label('Tingkat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Internasional' => 'danger',
                        'Nasional' => 'warning',
                        'Provinsi' => 'primary',
                        'Kabupaten' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('rank_position')
                    ->label('Juara'),
                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('unit.unit_name')
                    ->label('Unit Asal')
                    ->searchable(),
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
                SelectFilter::make('achievement_level')
                    ->label('Tingkat Kejuaraan')
                    ->options([
                        'Kabupaten' => 'Kabupaten',
                        'Provinsi' => 'Provinsi',
                        'Nasional' => 'Nasional',
                        'Internasional' => 'Internasional',
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
                    ->visible(fn (Achievement $record): bool => in_array($record->approval_status, ['draft', 'revision_required']))
                    ->requiresConfirmation()
                    ->action(function (Achievement $record) {
                        app(ApprovalWorkflowService::class)->submitForApproval($record, auth()->user(), 'achievement');
                        Notification::make()->title('Data Prestasi Telah Diajukan')->success()->send();
                    }),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Achievement $record): bool => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator())
                    ->requiresConfirmation()
                    ->action(function (Achievement $record) {
                        app(ApprovalWorkflowService::class)->approveContent($record, auth()->user(), 'achievement');
                        Notification::make()->title('Data Prestasi Telah Disetujui')->success()->send();
                    }),
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (Achievement $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->approval_status === 'pending_approval')
                    ->form([
                        Textarea::make('revision_notes')
                            ->label('Catatan Revisi Prestasi')
                            ->required(),
                    ])
                    ->action(function (Achievement $record, array $data) {
                        app(ApprovalWorkflowService::class)->requestRevision($record, auth()->user(), 'achievement', $data['revision_notes']);
                        Notification::make()->title('Instruksi Revisi Dikirimkan')->warning()->send();
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
