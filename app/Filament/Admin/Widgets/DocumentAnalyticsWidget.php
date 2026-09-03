<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use App\Models\Department;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentAnalyticsWidget extends Widget
{
    protected string $view = 'filament.widgets.document-analytics-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 2;

    protected function getPollingInterval(): ?string
    {
        return '120s';
    }

    public static function canView(): bool
    {
        return Auth::check();
    }

    protected function getViewData(): array
    {
        $baseQuery = Document::query()->access();

        // Average time-to-approval (from created_at to direktur_reviewed_at)
        $avgApprovalTime = Document::query()->access()
            ->where('status', Document::STATUS_APPROVED)
            ->whereNotNull('direktur_reviewed_at')
            ->selectRaw('AVG(DATEDIFF(direktur_reviewed_at, created_at)) as avg_days')
            ->value('avg_days');

        $avgApprovalDays = $avgApprovalTime ? round($avgApprovalTime, 1) : null;

        // Approval rate
        $totalCompleted = (clone $baseQuery)
            ->whereIn('status', [Document::STATUS_APPROVED, Document::STATUS_REJECTED])->count();
        $totalApproved = (clone $baseQuery)->where('status', Document::STATUS_APPROVED)->count();
        $approvalRate = $totalCompleted > 0 ? round(($totalApproved / $totalCompleted) * 100, 1) : 0;

        // Docs per department (top 6)
        $docsPerDepartment = Document::query()->access()
            ->select('department_id', DB::raw('count(*) as total'))
            ->with('department:id,name')
            ->groupBy('department_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->map(fn($row) => [
                'name'  => $row->department?->name ?? 'Tidak diketahui',
                'total' => $row->total,
            ])->all();

        // Top creators this month
        $topCreators = Document::query()->access()
            ->select('user_id', DB::raw('count(*) as total'))
            ->with('user:id,name')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn($row) => [
                'name'  => $row->user?->name ?? 'Anonim',
                'total' => $row->total,
            ])->all();

        // Monthly trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = (clone $baseQuery)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyTrend[] = [
                'label' => $month->format('M Y'),
                'count' => $count,
            ];
        }

        // Status breakdown
        $statusBreakdown = [
            ['label' => 'Draft',             'count' => (clone $baseQuery)->where('status', 'draft')->count(),            'color' => '#94a3b8'],
            ['label' => 'Menunggu Kabid',    'count' => (clone $baseQuery)->where('status', 'pending_kabid')->count(),    'color' => '#f59e0b'],
            ['label' => 'Menunggu Direktur', 'count' => (clone $baseQuery)->where('status', 'pending_direktur')->count(), 'color' => '#3b82f6'],
            ['label' => 'Disetujui',         'count' => (clone $baseQuery)->where('status', 'approved')->count(),         'color' => '#10b981'],
            ['label' => 'Ditolak',           'count' => (clone $baseQuery)->where('status', 'rejected')->count(),         'color' => '#ef4444'],
            ['label' => 'Diarsipkan',        'count' => (clone $baseQuery)->where('status', 'archived')->count(),         'color' => '#6366f1'],
        ];

        $maxDeptTotal = count($docsPerDepartment) > 0 ? max(array_column($docsPerDepartment, 'total')) : 1;
        $maxMonthCount = count($monthlyTrend) > 0 ? max(array_column($monthlyTrend, 'count')) : 1;

        return [
            'avgApprovalDays'   => $avgApprovalDays,
            'approvalRate'      => $approvalRate,
            'totalApproved'     => $totalApproved,
            'docsPerDepartment' => $docsPerDepartment,
            'topCreators'       => $topCreators,
            'monthlyTrend'      => $monthlyTrend,
            'statusBreakdown'   => $statusBreakdown,
            'maxDeptTotal'      => $maxDeptTotal,
            'maxMonthCount'     => $maxMonthCount,
            'currentMonth'      => now()->locale('id')->isoFormat('MMMM YYYY'),
        ];
    }
}
