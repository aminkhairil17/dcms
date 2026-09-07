<x-filament-widgets::widget>
<div class="dcms-analytics-wrap">

    {{-- KPI Row --}}
    <div class="dcms-kpi-row">

        <div class="dcms-kpi-card dcms-kpi--emerald">
            <div class="dcms-kpi-header">
                <div class="dcms-kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                </div>
            </div>
            <div class="dcms-kpi-body">
                <div class="dcms-kpi-value">{{ $avgApprovalDays !== null ? $avgApprovalDays : '—' }}</div>
                <div class="dcms-kpi-label">{{ $avgApprovalDays !== null ? 'Hari Rata-rata Approval' : 'Belum ada data' }}</div>
            </div>
            <div class="dcms-kpi-footer">
                Waktu rata-rata dokumen disetujui
            </div>
        </div>

        <div class="dcms-kpi-card dcms-kpi--blue">
            <div class="dcms-kpi-header">
                <div class="dcms-kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
            </div>
            <div class="dcms-kpi-body">
                <div class="dcms-kpi-value">{{ $approvalRate }}%</div>
                <div class="dcms-kpi-label">Tingkat Persetujuan</div>
            </div>
            <div class="dcms-kpi-footer">
                {{ $totalApproved }} dokumen telah disetujui
            </div>
        </div>

        @php $thisMonthCount = collect($monthlyTrend)->last()['count'] ?? 0; @endphp
        <div class="dcms-kpi-card dcms-kpi--indigo">
            <div class="dcms-kpi-header">
                <div class="dcms-kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/></svg>
                </div>
            </div>
            <div class="dcms-kpi-body">
                <div class="dcms-kpi-value">{{ $thisMonthCount }}</div>
                <div class="dcms-kpi-label">Dokumen Bulan Ini</div>
            </div>
            <div class="dcms-kpi-footer">
                Total di bulan {{ $currentMonth }}
            </div>
        </div>

    </div>

    {{-- Charts Row --}}
    <div class="dcms-charts-row">

        {{-- Monthly Trend Bar --}}
        <div class="dcms-chart-panel">
            <div class="dcms-panel-title">
                <div class="dcms-panel-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 0 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>
                </div>
                Tren 6 Bulan Terakhir
            </div>
            <div class="dcms-bar-chart">
                @foreach($monthlyTrend as $month)
                @php $barH = $maxMonthCount > 0 ? max(10, round(($month['count'] / $maxMonthCount) * 120)) : 10; @endphp
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
        <div class="dcms-chart-panel">
            <div class="dcms-panel-title">
                <div class="dcms-panel-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5z"/></svg>
                </div>
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
    <div class="dcms-charts-row">

        {{-- Per Department --}}
        <div class="dcms-chart-panel">
            <div class="dcms-panel-title">
                <div class="dcms-panel-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5v1.5H9v-1.5zm3 0h1.5v1.5H12v-1.5zm-3 4.5h1.5v1.5H9v-1.5zm3 0h1.5v1.5H12v-1.5zm-3 4.5h1.5v1.5H9v-1.5zm3 0h1.5v1.5H12v-1.5z"/></svg>
                </div>
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
        <div class="dcms-chart-panel">
            <div class="dcms-panel-title">
                <div class="dcms-panel-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                </div>
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

<style>
.dcms-analytics-wrap {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    width: 100%;
}

/* ── KPI Row ─────────────────────────────── */
.dcms-kpi-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    width: 100%;
}

.dcms-kpi-card {
    background: #ffffff;
    border-radius: 1.25rem;
    padding: 1.5rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.dcms-kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.1), 0 8px 12px -6px rgba(15, 23, 42, 0.05);
    border-color: rgba(203, 213, 225, 0.9);
}

.dcms-kpi-header {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 0.5rem;
}

.dcms-kpi-icon-wrap {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.dcms-kpi-card:hover .dcms-kpi-icon-wrap {
    transform: scale(1.1) rotate(5deg);
}

.dcms-kpi--emerald .dcms-kpi-icon-wrap {
    background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}
.dcms-kpi--emerald .dcms-kpi-value { color: #059669; }

.dcms-kpi--blue .dcms-kpi-icon-wrap {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}
.dcms-kpi--blue .dcms-kpi-value { color: #1d4ed8; }

.dcms-kpi--indigo .dcms-kpi-icon-wrap {
    background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}
.dcms-kpi--indigo .dcms-kpi-value { color: #4338ca; }


.dcms-kpi-body {
    display: flex;
    flex-direction: column;
}

.dcms-kpi-value {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.03em;
}

.dcms-kpi-label {
    font-size: 0.95rem;
    color: #475569;
    font-weight: 700;
    margin-top: 0.25rem;
}

.dcms-kpi-footer {
    font-size: 0.8rem;
    font-weight: 600;
    color: #94a3b8;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px dashed #e2e8f0;
}

/* ── Charts Row ──────────────────────────── */
.dcms-charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    width: 100%;
}

.dcms-chart-panel {
    background: #ffffff;
    border-radius: 1.25rem;
    padding: 1.5rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.dcms-chart-panel:hover {
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
}

.dcms-panel-title {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
}

.dcms-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 0.6rem;
    background: #f1f5f9;
    color: #64748b;
}

/* ── Bar Chart ───────────────────────────── */
.dcms-bar-chart {
    display: flex;
    align-items: flex-end;
    gap: 0.75rem;
    height: 150px;
    width: 100%;
    margin-top: auto;
}
.dcms-bar-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    justify-content: flex-end;
    gap: 6px;
}
.dcms-bar-count {
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748b;
    min-height: 16px;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;
}
.dcms-bar-col:hover .dcms-bar-count {
    opacity: 1;
    color: #3b82f6;
}
.dcms-bar-fill {
    width: 100%;
    max-width: 40px;
    border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, #93c5fd 0%, #bfdbfe 100%);
    min-height: 6px;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.dcms-bar-col:hover .dcms-bar-fill {
    background: linear-gradient(180deg, #3b82f6 0%, #60a5fa 100%);
    transform: scaleY(1.05);
    transform-origin: bottom;
    box-shadow: 0 -4px 12px rgba(59, 130, 246, 0.3);
}
.dcms-bar-label {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    line-height: 1.2;
}

/* ── Status List ─────────────────────────── */
.dcms-status-list { display: flex; flex-direction: column; gap: 1rem; }
.dcms-status-row { display: flex; align-items: center; gap: 1rem; }
.dcms-status-name {
    font-size: 0.85rem; color: #475569; font-weight: 600;
    width: 130px; flex-shrink: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dcms-status-bar-wrap {
    flex: 1; background: #f1f5f9;
    border-radius: 999px; height: 8px; overflow: hidden;
}
.dcms-status-bar-fill { height: 100%; border-radius: 999px; transition: width 1s ease-out; }
.dcms-status-count {
    font-size: 0.9rem; font-weight: 800;
    width: 35px; text-align: right; flex-shrink: 0;
}

/* ── Dept List ───────────────────────────── */
.dcms-dept-list { display: flex; flex-direction: column; gap: 1.2rem; }
.dcms-dept-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.dcms-dept-name {
    font-size: 0.85rem; color: #475569; font-weight: 600;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%;
}
.dcms-dept-count { font-size: 0.9rem; font-weight: 800; color: #0f172a; }
.dcms-dept-bar-wrap { background: #f1f5f9; border-radius: 999px; height: 6px; overflow: hidden; }
.dcms-dept-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #6366f1, #3b82f6); transition: width 1s ease-out; }

/* ── Creator List ────────────────────────── */
.dcms-creator-list { display: flex; flex-direction: column; gap: 0.75rem; }
.dcms-creator-row { 
    display: flex; align-items: center; gap: 12px; 
    padding: 0.6rem 0.8rem; border-radius: 0.8rem; 
    border: 1px solid transparent;
    transition: all 0.2s; 
}
.dcms-creator-row:hover { 
    background: #f8fafc; 
    border-color: #e2e8f0;
}
.dcms-creator-rank {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 800; color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.dcms-creator-name {
    flex: 1; font-size: 0.9rem; font-weight: 700; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dcms-creator-badge {
    background: rgba(99,102,241,0.1); color: #4338ca;
    padding: 0.3rem 0.75rem; border-radius: 999px;
    font-size: 0.75rem; font-weight: 800; flex-shrink: 0;
}
.dcms-empty { font-size: 0.85rem; color: #94a3b8; text-align: center; padding: 2rem; font-weight: 500; }

/* ── Mobile ──────────────────────────────── */
@media (max-width: 768px) {
    .dcms-analytics-wrap { gap: 0.85rem; }
    .dcms-kpi-row, .dcms-charts-row { grid-template-columns: 1fr; gap: 0.85rem; }
    .dcms-kpi-card, .dcms-chart-panel { padding: 1rem; border-radius: 1rem; }
    
    .dcms-kpi-value { font-size: 1.6rem; }
    .dcms-kpi-label { font-size: 0.85rem; }
    .dcms-kpi-footer { font-size: 0.75rem; margin-top: 0.75rem; padding-top: 0.75rem; }
    .dcms-kpi-icon-wrap { width: 2.25rem; height: 2.25rem; }
    .dcms-kpi-icon-wrap svg { width: 16px; height: 16px; }

    .dcms-panel-title { font-size: 0.8rem; margin-bottom: 1rem; }
    .dcms-panel-icon { width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; }
    .dcms-panel-icon svg { width: 13px; height: 13px; }

    .dcms-bar-chart { height: 120px; }
    .dcms-status-list { gap: 0.75rem; }
    .dcms-status-name { width: 90px; font-size: 0.75rem; }
    .dcms-status-count { font-size: 0.8rem; }
    
    .dcms-dept-list { gap: 0.85rem; }
    .dcms-dept-name { font-size: 0.75rem; }
    .dcms-dept-count { font-size: 0.85rem; }

    .dcms-creator-row { gap: 8px; padding: 0.4rem 0.6rem; }
    .dcms-creator-rank { width: 24px; height: 24px; font-size: 0.7rem; }
    .dcms-creator-name { font-size: 0.8rem; }
    .dcms-creator-badge { font-size: 0.7rem; padding: 0.2rem 0.5rem; }
}
</style>
</x-filament-widgets::widget>
