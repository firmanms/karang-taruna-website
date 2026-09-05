<?php

namespace App\Filament\Widgets;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\WorkProgram;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Units\Models\KarangTarunaUnit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        if (! $user) {
            return [];
        }

        // 1. Superadmin Stats
        if ($user->isSuperadmin()) {
            return [
                Stat::make('Total Kecamatan', RefDistrict::count())
                    ->description('31 Kecamatan resmi')
                    ->descriptionIcon('heroicon-m-map-pin')
                    ->color('info'),
                Stat::make('Unit Karang Taruna', KarangTarunaUnit::count())
                    ->description('Kabupaten, Kecamatan, Desa')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),
                Stat::make('Antrean Approval', Article::where('approval_status', 'pending_approval')->count() + Event::where('approval_status', 'pending_approval')->count() + WorkProgram::where('approval_status', 'pending_approval')->count())
                    ->description('Menunggu verifikasi Kabupaten')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Data Warga PPKS', PpksBeneficiary::count())
                    ->description('Total usulan terdata')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
            ];
        }

        // 2. Verifikator Stats
        if ($user->isVerifikator()) {
            return [
                Stat::make('Menunggu Reviu Berita', Article::where('approval_status', 'pending_approval')->count())
                    ->description('Usulan berita baru')
                    ->descriptionIcon('heroicon-m-newspaper')
                    ->color('warning'),
                Stat::make('Menunggu Reviu Agenda', Event::where('approval_status', 'pending_approval')->count())
                    ->description('Usulan event kegiatan')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('info'),
                Stat::make('Verifikasi PPKS', PpksBeneficiary::where('verification_status', 'pending_verification')->count())
                    ->description('Warga perlu verifikasi')
                    ->descriptionIcon('heroicon-m-shield-check')
                    ->color('danger'),
            ];
        }

        // 3. Admin Kecamatan Stats
        if ($user->isAdminKecamatan()) {
            $districtId = $user->unit?->district_id;

            return [
                Stat::make('Unit Desa di Wilayah', KarangTarunaUnit::where('district_id', $districtId)->where('unit_level', 'desa')->count())
                    ->description('Desa/Kelurahan binaan')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color('primary'),
                Stat::make('Berita Wilayah', Article::where('district_id', $districtId)->count())
                    ->description('Total berita kecamatan & desa')
                    ->descriptionIcon('heroicon-m-newspaper')
                    ->color('success'),
                Stat::make('Usulan Pending', Article::where('district_id', $districtId)->where('approval_status', 'pending_approval')->count())
                    ->description('Sedang ditinjau Kabupaten')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }

        // 4. Admin Desa Stats
        if ($user->isAdminDesa()) {
            $unitId = $user->unit_id;

            return [
                Stat::make('Pengurus Unit', $user->unit?->members()->count() ?? 0)
                    ->description('Anggota pengurus terdaftar')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('primary'),
                Stat::make('Berita Desa', Article::where('unit_id', $unitId)->count())
                    ->description('Liputan kegiatan desa')
                    ->descriptionIcon('heroicon-m-newspaper')
                    ->color('info'),
                Stat::make('Usulan Warga PPKS', PpksBeneficiary::where('unit_id', $unitId)->count())
                    ->description('Data diajukan')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
            ];
        }

        return [];
    }
}
