<?php

namespace App\Filament\Widgets;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\WorkProgram;
use Filament\Widgets\ChartWidget;

class ApprovalStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Distribusi Status Approval Konten';

    public static function canView(): bool
    {
        return auth()->user()?->isSuperadmin() || auth()->user()?->isVerifikator();
    }

    protected function getData(): array
    {
        $approvedCount = Article::where('approval_status', 'approved')->count()
            + Event::where('approval_status', 'approved')->count()
            + WorkProgram::where('approval_status', 'approved')->count();

        $pendingCount = Article::where('approval_status', 'pending_approval')->count()
            + Event::where('approval_status', 'pending_approval')->count()
            + WorkProgram::where('approval_status', 'pending_approval')->count();

        $revisionCount = Article::where('approval_status', 'revision_required')->count()
            + Event::where('approval_status', 'revision_required')->count();

        $rejectedCount = Article::where('approval_status', 'rejected')->count()
            + Event::where('approval_status', 'rejected')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Item',
                    'data' => [$approvedCount, $pendingCount, $revisionCount, $rejectedCount],
                    'backgroundColor' => [
                        '#10b981', // green for approved
                        '#f59e0b', // amber for pending
                        '#ef4444', // red for revision
                        '#64748b', // slate for rejected
                    ],
                ],
            ],
            'labels' => ['Approved', 'Pending Approval', 'Revision Required', 'Rejected'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
