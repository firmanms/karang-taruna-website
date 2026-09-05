<?php

namespace App\Filament\Resources\RefDistricts\Pages;

use App\Filament\Resources\RefDistricts\RefDistrictResource;
use App\Services\ExcelImportExportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRefDistricts extends ListRecords
{
    protected static string $resource = RefDistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('download_template')
                ->label('Download Template XLSX')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $headers = ['kemendagri_code', 'name', 'latitude_center', 'longitude_center'];
                    $sampleRows = [
                        ['32.04.01', 'Soreang', -7.0289, 107.5186],
                        ['32.04.02', 'Banjaran', -7.0425, 107.5892],
                    ];

                    return ExcelImportExportService::downloadTemplateXlsx('template-master-kecamatan.xlsx', $headers, $sampleRows);
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
                        ->helperText('Gunakan template XLSX yang telah didownload. Data yang sudah ada akan diperbarui, data baru akan ditambahkan tanpa duplikasi.'),
                ])
                ->action(function (array $data) {
                    try {
                        $count = ExcelImportExportService::importDistricts($data['file']);
                        Notification::make()
                            ->title('Import Berhasil')
                            ->body("Berhasil memproses {$count} data kecamatan.")
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
