<?php

namespace App\Filament\Widgets;

use App\Domain\Territory\Models\RefDistrict;
use Filament\Widgets\ChartWidget;

class EventsByDistrictChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Kegiatan per Kecamatan Teraktif';

    public static function canView(): bool
    {
        return auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator();
    }

    protected function getData(): array
    {
        $districts = RefDistrict::withCount('units')->take(8)->get();

        $labels = $districts->pluck('name')->toArray();
        $counts = $districts->pluck('units_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Unit Lembaga Terdaftar',
                    'data' => $counts,
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
