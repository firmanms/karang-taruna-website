<?php

namespace App\Filament\Widgets;

use App\Domain\Content\Models\Achievement;
use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\WorkProgram;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
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

        // 1. SUPERADMIN KABUPATEN STATS (10 Key Indicators)
        if ($user->isSuperadmin()) {
            $pendingArticles = Article::where('approval_status', 'pending_approval')->count();
            $pendingEvents = Event::where('approval_status', 'pending_approval')->count();
            $pendingPrograms = WorkProgram::where('approval_status', 'pending_approval')->count();
            $pendingAchievements = Achievement::where('approval_status', 'pending_approval')->count();
            $totalPending = $pendingArticles + $pendingEvents + $pendingPrograms + $pendingAchievements;

            return [
                Stat::make('Total Unit Karang Taruna', KarangTarunaUnit::count())
                    ->description('Kabupaten, Kecamatan, Desa')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color('primary'),
                Stat::make('Kecamatan Terjangkau', RefDistrict::count())
                    ->description('31 Kecamatan Resmi')
                    ->descriptionIcon('heroicon-m-map-pin')
                    ->color('info'),
                Stat::make('Desa / Kelurahan', RefVillage::count())
                    ->description('280 Basis Desa se-Kabupaten')
                    ->descriptionIcon('heroicon-m-home')
                    ->color('secondary'),
                Stat::make('Total Anggota / Kader', number_format((int) KarangTarunaUnit::sum('total_members'), 0, ',', '.'))
                    ->description('Kader terdaftar di sistem')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success'),
                Stat::make('Berita & Artikel', Article::count())
                    ->description(Article::where('is_published', true)->count().' Terbit Publik')
                    ->descriptionIcon('heroicon-m-newspaper')
                    ->color('primary'),
                Stat::make('Agenda Kegiatan', Event::count())
                    ->description(Event::where('approval_status', 'approved')->count().' Terverifikasi')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('info'),
                Stat::make('Program Kerja', WorkProgram::count())
                    ->description(WorkProgram::where('approval_status', 'approved')->count().' Disetujui')
                    ->descriptionIcon('heroicon-m-briefcase')
                    ->color('success'),
                Stat::make('Prestasi Pemuda', Achievement::count())
                    ->description(Achievement::where('approval_status', 'approved')->count().' Terverifikasi')
                    ->descriptionIcon('heroicon-m-trophy')
                    ->color('warning'),
                Stat::make('Warga Terdata PPKS', PpksBeneficiary::count())
                    ->description(PpksBeneficiary::where('verification_status', 'verified')->count().' Terverifikasi Bansos')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
                Stat::make('Pending Approval', $totalPending)
                    ->description('Antrean moderasi masuk')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color($totalPending > 0 ? 'danger' : 'success'),
            ];
        }

        // 2. VERIFIKATOR KABUPATEN STATS
        if ($user->isVerifikator()) {
            $pendingArticles = Article::where('approval_status', 'pending_approval')->count();
            $pendingEvents = Event::where('approval_status', 'pending_approval')->count();
            $pendingPrograms = WorkProgram::where('approval_status', 'pending_approval')->count();
            $pendingAchievements = Achievement::where('approval_status', 'pending_approval')->count();
            $pendingPpks = PpksBeneficiary::where('verification_status', 'pending_verification')->count();

            $todayApprovedArticles = Article::where('approval_status', 'approved')->whereDate('approved_at', today())->count();
            $todayApprovedEvents = Event::where('approval_status', 'approved')->whereDate('approved_at', today())->count();

            $totalRevisions = Article::where('approval_status', 'revision_required')->count()
                + Event::where('approval_status', 'revision_required')->count();

            $totalRejected = Article::where('approval_status', 'rejected')->count()
                + PpksBeneficiary::where('verification_status', 'rejected')->count();

            return [
                Stat::make('Pending Review', $pendingArticles + $pendingEvents + $pendingPrograms + $pendingAchievements + $pendingPpks)
                    ->description('Total antrean moderasi')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Perlu Revisi (Revision)', $totalRevisions)
                    ->description('Dikembalikan ke unit')
                    ->descriptionIcon('heroicon-m-arrow-uturn-left')
                    ->color('danger'),
                Stat::make('Approved Today', $todayApprovedArticles + $todayApprovedEvents)
                    ->description('Disetujui hari ini')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('success'),
                Stat::make('Total Ditolak (Rejected)', $totalRejected)
                    ->description('Tidak memenuhi kriteria')
                    ->descriptionIcon('heroicon-m-x-circle')
                    ->color('gray'),
            ];
        }

        // 3. ADMIN KECAMATAN STATS (Scoped to District)
        if ($user->isAdminKecamatan()) {
            $districtId = $user->unit?->district_id;

            return [
                Stat::make('Unit Desa Binaan', KarangTarunaUnit::where('district_id', $districtId)->where('unit_level', 'desa')->count())
                    ->description('Desa/Kelurahan aktif')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color('primary'),
                Stat::make('Berita Wilayah', Article::where('district_id', $districtId)->count())
                    ->description('Total berita kecamatan & desa')
                    ->descriptionIcon('heroicon-m-newspaper')
                    ->color('info'),
                Stat::make('Agenda Kegiatan', Event::where('district_id', $districtId)->count())
                    ->description('Agenda wilayah kecamatan')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('success'),
                Stat::make('Warga PPKS Wilayah', PpksBeneficiary::where('district_id', $districtId)->count())
                    ->description(PpksBeneficiary::where('district_id', $districtId)->where('verification_status', 'verified')->count().' Terverifikasi')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
                Stat::make('Usulan Pending', Article::where('district_id', $districtId)->where('approval_status', 'pending_approval')->count())
                    ->description('Sedang ditinjau Kabupaten')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }

        // 4. ADMIN DESA STATS (Scoped to Village Unit)
        if ($user->isAdminDesa()) {
            $unitId = $user->unit_id;

            return [
                Stat::make('Pengurus Unit', $user->unit?->members()->count() ?? 0)
                    ->description('Anggota pengurus terdaftar')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('primary'),
                Stat::make('Berita Desa', Article::where('unit_id', $unitId)->count())
                    ->description(Article::where('unit_id', $unitId)->where('approval_status', 'approved')->count().' Disetujui')
                    ->descriptionIcon('heroicon-m-newspaper')
                    ->color('info'),
                Stat::make('Agenda & Kegiatan', Event::where('unit_id', $unitId)->count())
                    ->description(Event::where('unit_id', $unitId)->where('approval_status', 'approved')->count().' Disetujui')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('success'),
                Stat::make('Usulan Warga PPKS', PpksBeneficiary::where('unit_id', $unitId)->count())
                    ->description(PpksBeneficiary::where('unit_id', $unitId)->where('verification_status', 'verified')->count().' Terverifikasi')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
            ];
        }

        return [];
    }
}
