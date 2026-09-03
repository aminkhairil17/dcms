<x-filament-widgets::widget>
    <div class="pa-shell">
        <div class="pa-grid">
            {{-- Prioritas Hari Ini --}}
            <section class="pa-panel pa-animate" style="--pa-delay: 60ms;">
                <div class="pa-panel-head">
                    <div class="pa-panel-title">Prioritas Hari Ini</div>
                    <div class="pa-panel-desc">Pekerjaan yang memerlukan tindakan segera dari Anda.</div>
                </div>

                @if (count($documentsNeedAction))
                    <div class="pa-list">
                        @foreach ($documentsNeedAction as $index => $document)
                            <a
                                href="{{ $document['url'] }}"
                                wire:navigate
                                class="pa-item"
                                style="--pa-delay: {{ 80 + $index * 50 }}ms;"
                            >
                                <div class="pa-item-main">
                                    <div class="pa-item-title">{{ $document['title'] }}</div>
                                    <div class="pa-item-meta">
                                        <span class="pa-item-code">{{ $document['code'] }}</span>
                                        <span>{{ $document['meta'] }}</span>
                                        <span>{{ $document['updatedAt'] }}</span>
                                    </div>
                                </div>
                                <span class="pa-badge pa-badge--{{ $document['badgeColor'] }}">{{ $document['badge'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="pa-empty">
                        <div class="pa-empty-title">Tidak ada tindakan mendesak.</div>
                        <div class="pa-empty-text">Semua dokumen Anda saat ini berada pada jalur yang aman.</div>
                    </div>
                @endif
            </section>

            {{-- Aksi Cepat --}}
            <section class="pa-panel pa-animate" style="--pa-delay: 120ms;">
                <div class="pa-panel-head">
                    <div class="pa-panel-title">Aksi Cepat</div>
                    <div class="pa-panel-desc">Akses tugas & pengingat dokumen Anda langsung dari sini.</div>
                </div>

                <div class="pa-action-list">
                    @foreach ($quickActions as $index => $action)
                        <a
                            href="{{ $action['url'] }}"
                            wire:navigate
                            class="pa-action pa-action--{{ $action['accent'] }}"
                            style="--pa-delay: {{ 140 + $index * 55 }}ms;"
                        >
                            <span class="pa-action-icon">
                                <x-filament::icon :icon="$action['icon']" class="pa-icon" />
                            </span>
                            <span class="pa-action-text">
                                <span class="pa-action-title">{{ $action['label'] }}</span>
                                <span class="pa-action-desc">{{ $action['description'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <style>
        .pa-shell { display: grid; gap: 0; }

        .pa-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            align-items: start;
        }

        @media (max-width: 900px) {
            .pa-grid { grid-template-columns: 1fr; }
        }

        .pa-animate {
            opacity: 0;
            transform: translateY(10px);
            animation: pa-rise 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            animation-delay: var(--pa-delay, 0ms);
        }

        @keyframes pa-rise {
            to { opacity: 1; transform: translateY(0); }
        }

        .pa-panel {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid rgba(0,0,0,0.07);
            padding: 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        .pa-panel-head { margin-bottom: 1rem; }

        .pa-panel-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .pa-panel-desc {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.15rem;
        }

        /* List items */
        .pa-list { display: grid; gap: 0.35rem; }

        .pa-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.7rem 0.85rem;
            border-radius: 0.65rem;
            text-decoration: none;
            color: inherit;
            border: 1px solid transparent;
            transition: background 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
        }

        .pa-item:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
            transform: translateX(2px);
        }

        .pa-item-main { min-width: 0; }

        .pa-item-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pa-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.2rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .pa-item-code {
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 4px;
            padding: 1px 6px;
            font-weight: 600;
            font-size: 0.72rem;
        }

        /* Badges */
        .pa-badge {
            flex-shrink: 0;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .pa-badge--amber  { background: #fef3c7; color: #b45309; }
        .pa-badge--rose   { background: #ffe4e6; color: #e11d48; }
        .pa-badge--sky    { background: #e0f2fe; color: #0369a1; }
        .pa-badge--slate  { background: #f1f5f9; color: #475569; }
        .pa-badge--gray   { background: #f1f5f9; color: #64748b; }
        .pa-badge--success{ background: #d1fae5; color: #047857; }

        /* Empty state */
        .pa-empty {
            padding: 1.5rem 1rem;
            text-align: center;
            color: #94a3b8;
        }

        .pa-empty-title { font-weight: 600; color: #64748b; font-size: 0.875rem; }
        .pa-empty-text  { font-size: 0.8rem; margin-top: 0.25rem; }

        /* Actions */
        .pa-action-list { display: grid; gap: 0.65rem; }

        .pa-action {
            display: flex;
            gap: 0.9rem;
            align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 0.85rem;
            text-decoration: none;
            color: inherit;
            border: 1px solid transparent;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        }

        .pa-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .pa-action-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pa-icon { width: 1.1rem; height: 1.1rem; }

        .pa-action--rose { background: linear-gradient(135deg, #fff1f2, #ffe4e6); }
        .pa-action--rose .pa-action-icon { background: #fecdd3; color: #e11d48; }

        .pa-action--blue { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
        .pa-action--blue .pa-action-icon { background: #bfdbfe; color: #1d4ed8; }

        .pa-action--emerald { background: linear-gradient(135deg, #ecfdf5, #d1fae5); }
        .pa-action--emerald .pa-action-icon { background: #a7f3d0; color: #047857; }

        .pa-action--amber { background: linear-gradient(135deg, #fffbeb, #fef3c7); }
        .pa-action--amber .pa-action-icon { background: #fde68a; color: #b45309; }

        .pa-action-text { display: grid; gap: 0.15rem; }

        .pa-action-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1e293b;
        }

        .pa-action-desc {
            font-size: 0.78rem;
            color: #64748b;
        }
    </style>
</x-filament-widgets::widget>
