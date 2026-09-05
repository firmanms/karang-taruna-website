<?php

namespace App\Filament\Resources\UnitMembers\Pages;

use App\Filament\Resources\UnitMembers\UnitMemberResource;
use App\Services\ExcelImportExportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUnitMembers extends ListRecords
{
    protected static string $resource = UnitMemberResource::class;

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
                        'unit_code',
                        'unit_name',
                        'full_name',
                        'position_role',
                        'division_section',
                        'phone',
                        'email',
                        'order_index',
                    ];
                    $sampleRows = [
                        [
                            'KT-KAB-BDG',
                            'Karang Taruna Kabupaten Bandung',
                            'Riki Ganesa, S.Hut',
                            'Ketua',
                            'Pengurus Harian',
                            '081234567890',
                            'ketua@karangtarunabandungkab.or.id',
                            1,
                        ],
                        [
                            'KT-KAB-BDG',
                            'Karang Taruna Kabupaten Bandung',
                            'Nama Wakil Ketua',
                            'Wakil Ketua',
                            'Pengurus Harian',
                            '081234567891',
                            'wakil@karangtarunabandungkab.or.id',
                            2,
                        ],
                    ];

                    return ExcelImportExportService::downloadTemplateXlsx('template-master-pengurus-unit.xlsx', $headers, $sampleRows);
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
                        ->helperText('Gunakan template XLSX yang telah didownload. Pastikan unit_code atau unit_name sesuai dengan Master Unit. Pengurus yang sudah ada akan diperbarui, pengurus baru akan ditambahkan tanpa duplikasi.'),
                ])
                ->action(function (array $data) {
                    try {
                        $count = ExcelImportExportService::importUnitMembers($data['file']);
                        Notification::make()
                            ->title('Import Berhasil')
                            ->body("Berhasil memproses {$count} data pengurus unit.")
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
