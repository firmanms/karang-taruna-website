<?php

namespace App\Filament\Pages;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\WorkProgram;
use App\Services\ApprovalWorkflowService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class ApprovalQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $model = Article::class;

    protected static string|UnitEnum|null $navigationGroup = 'Moderasi & Persetujuan';

    protected static ?string $navigationLabel = 'Antrean Moderasi';

    protected static ?string $title = 'Antrean Moderasi Konten Wilayah';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected string $view = 'filament.pages.approval-queue';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && ($user->isSuperadmin() || $user->isVerifikator());
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Article::where('approval_status', 'pending_approval')->count()
            + Event::where('approval_status', 'pending_approval')->count()
            + WorkProgram::where('approval_status', 'pending_approval')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Article::query()
                    ->where('approval_status', 'pending_approval')
                    ->latest()
            )
            ->heading('Usulan Berita & Liputan Menunggu Persetujuan')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('unit.unit_name')
                    ->label('Unit Pengusul')
                    ->searchable(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui (Approve)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui dan Terbitkan Konten')
                    ->modalDescription('Konten akan langsung tampil aktif pada website resmi publik.')
                    ->action(function (Article $record) {
                        app(ApprovalWorkflowService::class)->approveContent($record, auth()->user(), 'article', 'Disetujui melalui antrean moderasi.');
                        Notification::make()->title('Konten Berhasil Disetujui')->success()->send();
                    }),
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->form([
                        Textarea::make('revision_notes')
                            ->label('Catatan Instruksi Perbaikan')
                            ->placeholder('Jelaskan poin-poin yang perlu diperbaiki oleh pembuat...')
                            ->required(),
                    ])
                    ->action(function (Article $record, array $data) {
                        app(ApprovalWorkflowService::class)->requestRevision($record, auth()->user(), 'article', $data['revision_notes']);
                        Notification::make()->title('Instruksi Revisi Dikirimkan')->warning()->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan alasan mengapa konten ini ditolak...')
                            ->required(),
                    ])
                    ->action(function (Article $record, array $data) {
                        app(ApprovalWorkflowService::class)->rejectContent($record, auth()->user(), 'article', $data['rejection_reason']);
                        Notification::make()->title('Konten Ditolak')->danger()->send();
                    }),
            ]);
    }
}
