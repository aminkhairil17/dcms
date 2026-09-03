<x-filament-widgets::widget>
<div class="dms-wrap">
    <div class="dms-grid">

        {{-- Card 1: Total Dokumen --}}
        <div class="dms-card">
            <div class="dms-card-top dms-top-blue"></div>
            <div class="dms-card-body">
                <div class="dms-card-head">
                    <div>
                        <div class="dms-num dms-num-blue">{{ number_format($total) }}</div>
                        <div class="dms-label">Total Dokumen</div>
                    </div>
                    <div class="dms-icon dms-icon-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="dms-chart">
                <svg viewBox="0 0 100 28" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="dms-g1" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $totalPath }} L 100,28 L 0,28 Z" fill="url(#dms-g1)"/>
                    <path d="{{ $totalPath }}" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Card 2: Disetujui --}}
        <div class="dms-card">
            <div class="dms-card-top dms-top-emerald"></div>
            <div class="dms-card-body">
                <div class="dms-card-head">
                    <div>
                        <div class="dms-num dms-num-emerald">{{ number_format($approved) }}</div>
                        <div class="dms-label">Disetujui</div>
                    </div>
                    <div class="dms-icon dms-icon-emerald">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="dms-sub">{{ $approved }} dokumen telah disetujui</div>
            </div>
            <div class="dms-chart">
                <svg viewBox="0 0 100 28" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="dms-g2" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $approvedPath }} L 100,28 L 0,28 Z" fill="url(#dms-g2)"/>
                    <path d="{{ $approvedPath }}" fill="none" stroke="#10b981" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Card 3: Menunggu Review --}}
        <div class="dms-card">
            <div class="dms-card-top dms-top-amber"></div>
            <div class="dms-card-body">
                <div class="dms-card-head">
                    <div>
                        <div class="dms-num dms-num-amber">{{ number_format($pendingTotal) }}</div>
                        <div class="dms-label">Menunggu Review</div>
                    </div>
                    <div class="dms-icon dms-icon-amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="dms-sub">{{ $pendingKabid }} Kabid · {{ $pendingDir }} Direktur</div>
            </div>
            <div class="dms-chart">
                <svg viewBox="0 0 100 28" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="dms-g3" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $pendingPath }} L 100,28 L 0,28 Z" fill="url(#dms-g3)"/>
                    <path d="{{ $pendingPath }}" fill="none" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Card 4: Draft --}}
        <div class="dms-card">
            <div class="dms-card-top dms-top-slate"></div>
            <div class="dms-card-body">
                <div class="dms-card-head">
                    <div>
                        <div class="dms-num dms-num-slate">{{ number_format($draft) }}</div>
                        <div class="dms-label">Draft</div>
                    </div>
                    <div class="dms-icon dms-icon-slate">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                        </svg>
                    </div>
                </div>
                <div class="dms-sub">{{ $rejected }} dokumen ditolak</div>
            </div>
            <div class="dms-chart">
                <svg viewBox="0 0 100 28" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="dms-g4" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#64748b" stop-opacity="0.2"/>
                            <stop offset="100%" stop-color="#64748b" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $draftPath }} L 100,28 L 0,28 Z" fill="url(#dms-g4)"/>
                    <path d="{{ $draftPath }}" fill="none" stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

    </div>
</div>
</x-filament-widgets::widget>

<style>
.dms-wrap {
    width: 100%;
    box-sizing: border-box;
}
.dms-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    width: 100%;
    box-sizing: border-box;
}
@media (min-width: 1024px) {
    .dms-grid { grid-template-columns: repeat(4, 1fr); }
}
.dms-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    min-width: 0;
}
.dms-card-top {
    height: 3px;
    flex-shrink: 0;
}
.dms-top-blue    { background: #2563eb; }
.dms-top-emerald { background: #10b981; }
.dms-top-amber   { background: #f59e0b; }
.dms-top-slate   { background: #64748b; }

.dms-card-body {
    padding: 14px 14px 10px;
    flex: 1;
    min-width: 0;
}
.dms-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
}
.dms-num {
    font-size: 28px;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -0.02em;
}
.dms-num-blue    { color: #1e40af; }
.dms-num-emerald { color: #047857; }
.dms-num-amber   { color: #b45309; }
.dms-num-slate   { color: #334155; }

.dms-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    margin-top: 5px;
}
.dms-sub {
    font-size: 10px;
    color: #94a3b8;
    margin-top: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dms-icon {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dms-icon svg { width: 17px; height: 17px; }
.dms-icon-blue    { background: #eff6ff; color: #2563eb; }
.dms-icon-emerald { background: #ecfdf5; color: #10b981; }
.dms-icon-amber   { background: #fffbeb; color: #f59e0b; }
.dms-icon-slate   { background: #f8fafc; color: #64748b; }

/* Chart — sits BELOW body content, never overlaps */
.dms-chart {
    height: 44px;
    flex-shrink: 0;
    overflow: hidden;
}
.dms-chart svg {
    width: 100%;
    height: 44px;
    display: block;
}

@media (max-width: 480px) {
    .dms-num { font-size: 22px; }
    .dms-label { font-size: 9.5px; }
}
</style>
