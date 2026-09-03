<x-filament-widgets::widget>
<div class="dcms-analytics-wrap">

    {{-- KPI Row --}}
    <div class="dcms-kpi-row">

        <div class="dcms-kpi-card">
            <div class="dcms-kpi-value" style="color:#059669;">
                {{ $avgApprovalDays !== null ? $avgApprovalDays : '—' }}
            </div>
            <div class="dcms-kpi-label">
                {{ $avgApprovalDays !== null ? 'Hari rata-rata approval' : 'Belum ada data' }}
            </div>
            <div class="dcms-kpi-sub" style="color:#10b981;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                Waktu rata-rata disetujui
            </div>
        </div>

        <div class="dcms-kpi-card">
            <div class="dcms-kpi-value" style="color:#2563eb;">{{ $approvalRate }}%</div>
            <div class="dcms-kpi-label">Tingkat persetujuan</div>
            <div class="dcms-kpi-sub" style="color:#3b82f6;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                {{ $totalApproved }} disetujui
            </div>
        </div>

        @php $thisMonthCount = collect($monthlyTrend)->last()['count'] ?? 0; @endphp
        <div class="dcms-kpi-card" style="border-right:none;">
            <div class="dcms-kpi-value" style="color:#7c3aed;">{{ $thisMonthCount }}</div>
            <div class="dcms-kpi-label">Dokumen bulan ini</div>
            <div class="dcms-kpi-sub" style="color:#8b5cf6;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/></svg>
                {{ $currentMonth }}
            </div>
        </div>

    </div>

    {{-- Charts Row --}}
    <div class="dcms-charts-row">

        {{-- Monthly Trend Bar --}}
        <div class="dcms-chart-panel">
            <div class="dcms-panel-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 0 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>
                Tren 6 Bulan Terakhir
            </div>
            <div class="dcms-bar-chart">
                @foreach($monthlyTrend as $month)
                @php $barH = $maxMonthCount > 0 ? max(8, round(($month['count'] / $maxMonthCount) * 76)) : 8; @endphp
                <div class="dcms-bar-col">
                    <div class="dcms-bar-count">{{ $month['count'] > 0 ? $month['count'] : '' }}</div>
                    <div class="dcms-bar-fill" style="height:{{ $barH }}px;"></div>
                    <div class="dcms-bar-label">
                        {{ \Carbon\Carbon::parse('first day of ' . $month['label'])->locale('id')->isoFormat('MMM') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="dcms-chart-panel" style="border-right:none;">
            <div class="dcms-panel-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5z"/></svg>
                Distribusi Status
            </div>
            <div class="dcms-status-list">
                @foreach($statusBreakdown as $status)
                @if($status['count'] > 0)
                @php
                    $totalDocs = array_sum(array_column($statusBreakdown, 'count'));
                    $pct = $totalDocs > 0 ? round(($status['count'] / $totalDocs) * 100) : 0;
                @endphp
                <div class="dcms-status-row">
                    <div class="dcms-status-dot" style="background:{{ $status['color'] }};"></div>
                    <div class="dcms-status-name">{{ $status['label'] }}</div>
                    <div class="dcms-status-bar-wrap">
                        <div class="dcms-status-bar-fill" style="width:{{ $pct }}%;background:{{ $status['color'] }};"></div>
                    </div>
                    <div class="dcms-status-count" style="color:{{ $status['color'] }};">{{ $status['count'] }}</div>
                </div>
                @endif
                @endforeach
            </div>
        </div>

    </div>

    {{-- Bottom Row --}}
    <div class="dcms-charts-row dcms-bottom-row">

        {{-- Per Department --}}
        <div class="dcms-chart-panel">
            <div class="dcms-panel-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5v1.5H9v-1.5zm3 0h1.5v1.5H12v-1.5zm-3 4.5h1.5v1.5H9v-1.5zm3 0h1.5v1.5H12v-1.5zm-3 4.5h1.5v1.5H9v-1.5zm3 0h1.5v1.5H12v-1.5z"/></svg>
                Per Departemen
            </div>
            <div class="dcms-dept-list">
                @forelse($docsPerDepartment as $dept)
                @php $pct = $maxDeptTotal > 0 ? round(($dept['total'] / $maxDeptTotal) * 100) : 0; @endphp
                <div class="dcms-dept-item">
                    <div class="dcms-dept-header">
                        <span class="dcms-dept-name">{{ $dept['name'] }}</span>
                        <span class="dcms-dept-count">{{ $dept['total'] }}</span>
                    </div>
                    <div class="dcms-dept-bar-wrap">
                        <div class="dcms-dept-bar-fill" style="width:{{ $pct }}%;"></div>
                    </div>
                </div>
                @empty
                <div class="dcms-empty">Tidak ada data</div>
                @endforelse
            </div>
        </div>

        {{-- Top Creators --}}
        <div class="dcms-chart-panel" style="border-right:none;">
            <div class="dcms-panel-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                Paling Aktif Bulan Ini
            </div>
            <div class="dcms-creator-list">
                @forelse($topCreators as $i => $creator)
                @php
                    $rankColors = ['#f59e0b','#94a3b8','#92400e','#6366f1','#10b981'];
                    $rankBg = $rankColors[$i] ?? '#94a3b8';
                @endphp
                <div class="dcms-creator-row">
                    <div class="dcms-creator-rank" style="background:{{ $rankBg }};">{{ $i + 1 }}</div>
                    <div class="dcms-creator-name">{{ $creator['name'] }}</div>
                    <div class="dcms-creator-badge">{{ $creator['total'] }} dok</div>
                </div>
                @empty
                <div class="dcms-empty">Belum ada aktivitas bulan ini</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
</x-filament-widgets::widget>

<style>
.dcms-analytics-wrap {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
}

/* ── KPI Row ─────────────────────────────── */
.dcms-kpi-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-bottom: 1px solid #f1f5f9;
    width: 100%;
}
.dcms-kpi-card {
    padding: 18px 16px;
    border-right: 1px solid #f1f5f9;
    text-align: center;
}
.dcms-kpi-value {
    font-size: 26px;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -0.02em;
}
.dcms-kpi-label {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    margin-top: 5px;
}
.dcms-kpi-sub {
    font-size: 10px;
    font-weight: 500;
    margin-top: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
}
.dcms-kpi-sub svg { width: 11px; height: 11px; flex-shrink: 0; }

/* ── Charts Row ──────────────────────────── */
.dcms-charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-bottom: 1px solid #f1f5f9;
    width: 100%;
}
.dcms-bottom-row { border-bottom: none; }
.dcms-chart-panel {
    padding: 18px 16px;
    border-right: 1px solid #f1f5f9;
    min-width: 0;
    box-sizing: border-box;
}
.dcms-panel-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 14px;
}
.dcms-panel-title svg { width: 13px; height: 13px; flex-shrink: 0; }

/* ── Bar Chart ───────────────────────────── */
.dcms-bar-chart {
    display: flex;
    align-items: flex-end;
    gap: 4px;
    height: 100px;
    width: 100%;
}
.dcms-bar-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    justify-content: flex-end;
    gap: 3px;
    min-width: 0;
}
.dcms-bar-count {
    font-size: 9px;
    font-weight: 700;
    color: #475569;
    min-height: 12px;
    line-height: 1;
}
.dcms-bar-fill {
    width: 100%;
    border-radius: 3px 3px 0 0;
    background: linear-gradient(180deg, #6366f1 0%, #3b82f6 100%);
    min-height: 4px;
}
.dcms-bar-label {
    font-size: 8px;
    color: #94a3b8;
    text-align: center;
    white-space: nowrap;
    line-height: 1.2;
}

/* ── Status List ─────────────────────────── */
.dcms-status-list { display: flex; flex-direction: column; gap: 9px; }
.dcms-status-row { display: flex; align-items: center; gap: 7px; min-width: 0; }
.dcms-status-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dcms-status-name {
    font-size: 11px; color: #374151;
    flex: 0 0 auto; width: 100px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dcms-status-bar-wrap {
    flex: 1; background: #f1f5f9;
    border-radius: 50px; height: 5px; overflow: hidden; min-width: 0;
}
.dcms-status-bar-fill { height: 100%; border-radius: 50px; }
.dcms-status-count {
    font-size: 11px; font-weight: 700;
    width: 20px; text-align: right; flex-shrink: 0;
}

/* ── Dept List ───────────────────────────── */
.dcms-dept-list { display: flex; flex-direction: column; gap: 9px; }
.dcms-dept-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
.dcms-dept-name {
    font-size: 11.5px; color: #374151; font-weight: 500;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 75%;
}
.dcms-dept-count { font-size: 12px; font-weight: 700; color: #1e293b; }
.dcms-dept-bar-wrap { background: #f1f5f9; border-radius: 50px; height: 4px; overflow: hidden; }
.dcms-dept-bar-fill { height: 100%; border-radius: 50px; background: linear-gradient(90deg, #6366f1, #3b82f6); }

/* ── Creator List ────────────────────────── */
.dcms-creator-list { display: flex; flex-direction: column; gap: 9px; }
.dcms-creator-row { display: flex; align-items: center; gap: 10px; min-width: 0; }
.dcms-creator-rank {
    width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; color: white;
}
.dcms-creator-name {
    flex: 1; font-size: 12px; font-weight: 600; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dcms-creator-badge {
    background: rgba(99,102,241,0.1); color: #6366f1;
    padding: 2px 7px; border-radius: 50px;
    font-size: 10px; font-weight: 700; flex-shrink: 0;
}
.dcms-empty { font-size: 12px; color: #94a3b8; text-align: center; padding: 16px; }

/* ── Mobile ──────────────────────────────── */
@media (max-width: 640px) {
    .dcms-kpi-row { grid-template-columns: 1fr 1fr; }
    .dcms-kpi-card:nth-child(2) { border-right: none; }
    .dcms-kpi-card:nth-child(3) {
        grid-column: 1 / -1;
        border-right: none;
        border-top: 1px solid #f1f5f9;
    }
    .dcms-charts-row { grid-template-columns: 1fr; }
    .dcms-chart-panel { border-right: none; border-bottom: 1px solid #f1f5f9; }
    .dcms-chart-panel:last-child { border-bottom: none; }
    .dcms-status-name { width: 80px; }
}
</style>
