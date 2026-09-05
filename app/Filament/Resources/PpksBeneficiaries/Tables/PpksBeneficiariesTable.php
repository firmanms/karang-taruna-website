<?php

namespace App\Filament\Resources\PpksBeneficiaries\Tables;

use App\Domain\PPKS\Models\PpksBeneficiary;
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

class PpksBeneficiariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK (Masked)')
                    ->formatStateUsing(fn (string $state): string => substr($state, 0, 6).'******'.substr($state, -4))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.category_name')
                    ->label('Kategori PPKS')
                    ->badge()
                    ->sortable(),
                TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable(),
                TextColumn::make('village.name')
                    ->label('Desa/Kelurahan')
                    ->sortable(),
                TextColumn::make('verification_status')
                    ->label('Status Verifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'pending_verification' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'verified' => 'Terverifikasi',
                        'pending_verification' => 'Menunggu Verifikasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                TextColumn::make('social_assistance_status')
                    ->label('Bansos/Kebutuhan')
                    ->limit(30),
                TextColumn::make('last_survey_date')
                    ->label('Tgl Survei')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori PPKS')
                    ->relationship('category', 'category_name'),
                SelectFilter::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending_verification' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                // Verifikasi Action (untuk Superadmin & Verifikator)
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PpksBeneficiary $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->verification_status !== 'verified')
                    ->requiresConfirmation()
                    ->action(function (PpksBeneficiary $record) {
                        $record->update([
                            'verification_status' => 'verified',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                        ]);

                        Notification::make()->title('Data Warga PPKS Berhasil Diverifikasi')->success()->send();
                    }),

                // Tolak Verifikasi Action
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (PpksBeneficiary $record): bool => (auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator()) && $record->verification_status === 'pending_verification')
                    ->form([
                        Textarea::make('verification_notes')
                            ->label('Alasan Penolakan / Tidak Memenuhi Syarat')
                            ->required(),
                    ])
                    ->action(function (PpksBeneficiary $record, array $data) {
                        $record->update([
                            'verification_status' => 'rejected',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'verification_notes' => $data['verification_notes'],
                        ]);

                        Notification::make()->title('Usulan PPKS Ditolak')->danger()->send();
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
