<?php

namespace App\Filament\Resources\RefVillages\Pages;

use App\Filament\Resources\RefVillages\RefVillageResource;
use App\Services\ExcelImportExportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRefVillages extends ListRecords
{
    protected static string $resource = RefVillageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('download_template')
                ->label('Download Template XLSX')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $headers = [
                        'kemendagri_code',
                        'district_name',
                        'name',
                        'type',
                        'postal_code',
                        'total_rw',
                        'total_rt',
                        'latitude_center',
                        'longitude_center',
                    ];
                    $sampleRows = [
                        ['32.04.01.2001', 'Soreang', 'Soreang', 'Desa', '40911', 18, 72, -7.0289, 107.5186],
                        ['32.04.01.2002', 'Soreang', 'Panyirapan', 'Desa', '40915', 14, 56, -7.0345, 107.5210],
                    ];

                    return ExcelImportExportService::downloadTemplateXlsx('template-master-desa-kelurahan.xlsx', $headers, $sampleRows);
                }),
            Action::make('import_xlsx')
                ->label('Import XLSX')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File Template Excel (.xlsx / .csv)')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'text/plain',
                        ])
                        ->disk('public')
                        ->directory('imports')
                        ->required()
                        ->helperText('Gunakan template XLSX yang telah didownload. Pastikan nama kecamatan sesuai dengan master kecamatan.'),
                ])
                ->action(function (array $data) {
                    try {
                        $count = ExcelImportExportService::importVillages($data['file']);
                        Notification::make()
                            ->title('Import Berhasil')
                            ->body("Berhasil memproses {$count} data desa/kelurahan.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Import')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
