<?php

namespace App\Filament\Resources\KarangTarunaUnits\Pages;

use App\Filament\Resources\KarangTarunaUnits\KarangTarunaUnitResource;
use App\Services\ExcelImportExportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListKarangTarunaUnits extends ListRecords
{
    protected static string $resource = KarangTarunaUnitResource::class;

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
                        'unit_level',
                        'unit_name',
                        'district_name',
                        'village_name',
                        'chairman_name',
                        'secretary_name',
                        'treasurer_name',
                        'contact_phone',
                        'contact_email',
                        'office_address',
                        'period_start_year',
                        'period_end_year',
                        'total_members',
                        'status_aktif',
                        'latitude',
                        'longitude',
                    ];
                    $sampleRows = [
                        [
                            'KT-KAB-BDG',
                            'kabupaten',
                            'Karang Taruna Kabupaten Bandung',
                            '',
                            '',
                            'Riki Ganesa, S.Hut',
                            'Sekretaris Kabupaten',
                            'Bendahara Kabupaten',
                            '081234567890',
                            'kabupaten@karangtarunabandungkab.or.id',
                            'Jl. Raya Soreang KM 17',
                            2020,
                            2025,
                            50,
                            'Aktif',
                            -7.0289,
                            107.5186,
                        ],
                        [
                            'KT-KEC-SOR',
                            'kecamatan',
                            'Karang Taruna Kecamatan Soreang',
                            'Soreang',
                            '',
                            'Nama Ketua Kecamatan',
                            'Nama Sekretaris',
                            'Nama Bendahara',
                            '081298765432',
                            'soreang@karangtaruna.id',
                            'Kantor Camat Soreang',
                            2021,
                            2026,
                            35,
                            'Aktif',
                            -7.0295,
                            107.5190,
                        ],
                        [
                            'KT-DES-SOR',
                            'desa',
                            'Karang Taruna Desa Soreang',
                            'Soreang',
                            'Soreang',
                            'Nama Ketua Desa',
                            'Nama Sekretaris',
                            'Nama Bendahara',
                            '081311223344',
                            'desasoreang@karangtaruna.id',
                            'Kantor Desa Soreang',
                            2022,
                            2027,
                            25,
                            'Aktif',
                            -7.0300,
                            107.5200,
                        ],
                    ];

                    return ExcelImportExportService::downloadTemplateXlsx('template-master-unit-karang-taruna.xlsx', $headers, $sampleRows);
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
                        ->helperText('Gunakan template XLSX yang telah didownload. Nilai unit_level: kabupaten, kecamatan, atau desa. Data yang cocok akan diperbarui dan data baru akan ditambahkan tanpa duplikasi.'),
                ])
                ->action(function (array $data) {
                    try {
                        $count = ExcelImportExportService::importUnits($data['file']);
                        Notification::make()
                            ->title('Import Berhasil')
                            ->body("Berhasil memproses {$count} data unit Karang Taruna.")
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
