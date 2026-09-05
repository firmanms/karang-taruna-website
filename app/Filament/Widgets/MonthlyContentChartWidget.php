<?php

namespace App\Filament\Widgets;

use App\Domain\Content\Models\Article;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyContentChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Grafik Publikasi Konten (6 Bulan Terakhir)';

    public static function canView(): bool
    {
        return auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator();
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function ($monthsAgo) {
            return Carbon::now()->subMonths($monthsAgo)->format('Y-m');
        });

        $data = $months->map(function ($month) {
            return Article::where('approval_status', 'approved')
                ->whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
        })->toArray();

        $labels = $months->map(function ($month) {
            return Carbon::createFromFormat('Y-m', $month)->translatedFormat('M Y');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Berita & Artikel Terbit',
                    'data' => $data,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'borderColor' => '#10b981',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
