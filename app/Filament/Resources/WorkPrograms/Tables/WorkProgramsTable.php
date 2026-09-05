<?php

namespace App\Filament\Resources\WorkPrograms\Tables;

use App\Domain\Content\Models\WorkProgram;
use App\Services\ApprovalWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('program_name')
                    ->label('Program Kerja')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('division.division_name')
                    ->label('Bidang')
                    ->badge(),
                TextColumn::make('unit.unit_name')
                    ->label('Unit Pengusul')
                    ->searchable(),
                TextColumn::make('budget_amount')
                    ->label('Anggaran')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('execution_time')
                    ->label('Waktu')
                    ->searchable(),
                TextColumn::make('progress_status')
                    ->label('Progres')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Rencana' => 'info',
                        'Sedang Berjalan' => 'warning',
                        'Selesai' => 'success',
                        'Ditunda' => 'danger',
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
                SelectFilter::make('division_id')
                    ->label('Bidang')
                    ->relationship('division', 'division_name'),
                SelectFilter::make('progress_status')
                    ->label('Progres')
                    ->options([
                        'Rencana' => 'Rencana',
                        'Sedang Berjalan' => 'Sedang Berjalan',
                        'Selesai' => 'Selesai',
                        'Ditunda' => 'Ditunda',
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
                    ->visible(fn (WorkProgram $record): bool => in_array($record->approval_status, ['draft', 'revision_required']))
                    ->requiresConfirmation()
                    ->action(function (WorkProgram $record) {
                        app(ApprovalWorkflowService::class)->submitForApproval($record, auth()->user(), 'work_program');
                        Notification::make()->title('Program Kerja Telah Diajukan')->success()->send();
                    }),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (WorkProgram $record): bool => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator())
                    ->requiresConfirmation()
                    ->action(function (WorkProgram $record) {
                        app(ApprovalWorkflowService::class)->approveContent($record, auth()->user(), 'work_program');
                        Notification::make()->title('Program Kerja Telah Disetujui')->success()->send();
                    }),
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (WorkProgram $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->approval_status === 'pending_approval')
                    ->form([
                        Textarea::make('revision_notes')
                            ->label('Catatan Revisi Program')
                            ->required(),
                    ])
                    ->action(function (WorkProgram $record, array $data) {
                        app(ApprovalWorkflowService::class)->requestRevision($record, auth()->user(), 'work_program', $data['revision_notes']);
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
