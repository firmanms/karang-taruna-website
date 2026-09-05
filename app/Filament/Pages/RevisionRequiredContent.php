<?php

namespace App\Filament\Pages;

use App\Domain\Content\Models\Article;
use App\Services\ApprovalWorkflowService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class RevisionRequiredContent extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Moderasi & Persetujuan';

    protected static ?string $navigationLabel = 'Konten Perlu Revisi';

    protected static ?string $title = 'Daftar Konten Memerlukan Revisi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected string $view = 'filament.pages.revision-required-content';

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->query(
                Article::query()
                    ->forUser($user)
                    ->where('approval_status', 'revision_required')
                    ->latest()
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('rejection_reason')
                    ->label('Catatan Instruksi Perbaikan dari Kabupaten')
                    ->wrap()
                    ->color('danger')
                    ->weight('semibold'),
                TextColumn::make('updated_at')
                    ->label('Waktu Dikembalikan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('resubmit')
                    ->label('Ajukan Ulang (Resubmit)')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Ajukan Ulang Konten yang Telah Diperbaiki')
                    ->modalDescription('Pastikan Anda telah menyunting dan melengkapi seluruh catatan revisi sebelum mengajukan ulang ke Kabupaten.')
                    ->action(function (Article $record) {
                        app(ApprovalWorkflowService::class)->submitForApproval($record, auth()->user(), 'article');
                        Notification::make()->title('Konten Telah Diajukan Ulang')->success()->send();
                    }),
            ]);
    }
}
