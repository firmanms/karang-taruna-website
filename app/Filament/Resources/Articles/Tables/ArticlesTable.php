<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Domain\Content\Models\Article;
use App\Services\ApprovalWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(45),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('unit.unit_name')
                    ->label('Unit Pengusul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('approval_status')
                    ->label('Status Moderasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending_approval' => 'warning',
                        'revision_required' => 'danger',
                        'rejected' => 'secondary',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
                TextColumn::make('views_count')
                    ->label('Dilihat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('approval_status')
                    ->label('Status Moderasi')
                    ->options([
                        'draft' => 'Draft',
                        'pending_approval' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'revision_required' => 'Perlu Revisi',
                        'rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                // 1. Submit Action (untuk Creator jika status draft / revision_required)
                Action::make('submit')
                    ->label('Ajukan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn (Article $record): bool => in_array($record->approval_status, ['draft', 'revision_required']))
                    ->requiresConfirmation()
                    ->action(function (Article $record) {
                        app(ApprovalWorkflowService::class)->submitForApproval($record, auth()->user(), 'article');
                        Notification::make()->title('Berita Telah Diajukan ke Kabupaten')->success()->send();
                    }),

                // 2. Approve Action (untuk Superadmin & Verifikator)
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Article $record): bool => auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator())
                    ->requiresConfirmation()
                    ->action(function (Article $record) {
                        app(ApprovalWorkflowService::class)->approveContent($record, auth()->user(), 'article');
                        Notification::make()->title('Berita Berhasil Disetujui & Diterbitkan')->success()->send();
                    }),

                // 3. Request Revision Action (untuk Superadmin & Verifikator)
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (Article $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->approval_status === 'pending_approval')
                    ->form([
                        Textarea::make('revision_notes')
                            ->label('Catatan Instruksi Perbaikan')
                            ->required(),
                    ])
                    ->action(function (Article $record, array $data) {
                        app(ApprovalWorkflowService::class)->requestRevision($record, auth()->user(), 'article', $data['revision_notes']);
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
