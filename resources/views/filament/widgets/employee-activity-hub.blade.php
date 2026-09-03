<x-filament-widgets::widget>
    @php
        $todayLoad = $todayMeetingsCount + $documentsNeedActionCount + $unreadNotificationsCount;
        $focusTone = match (true) {
            $todayLoad >= 8 => ['label' => 'Padat', 'class' => 'eah-chip--rose'],
            $todayLoad >= 4 => ['label' => 'Aktif', 'class' => 'eah-chip--amber'],
            default => ['label' => 'Terkendali', 'class' => 'eah-chip--emerald'],
        };
    @endphp

    <div class="eah-shell" x-data="{ tab: 'focus' }">
        <div class="eah-hero eah-animate" style="--eah-delay: 0ms;">
            <div>
                <div class="eah-kicker">Pusat Aktivitas Pegawai</div>
                <h2 class="eah-title">{{ $greeting }}, {{ $userName }}</h2>
                <p class="eah-subtitle">
                    {{ $subtitle }}
                </p>

                <div class="eah-meta">
                    <span class="eah-chip {{ $focusTone['class'] }}">{{ $focusTone['label'] }}</span>
                    <span class="eah-meta-text">{{ $organizationPath }}</span>
                </div>
            </div>

            <div class="eah-next-card">
                <div class="eah-next-label">Agenda terdekat</div>

                @if ($nextMeeting)
                    <a href="{{ $nextMeeting['url'] }}" wire:navigate class="eah-next-link">
                        <div class="eah-next-title">{{ $nextMeeting['title'] }}</div>
                        <div class="eah-next-time">{{ $nextMeeting['when'] }}</div>
                        <div class="eah-next-location">{{ $nextMeeting['location'] }}</div>
                    </a>
                @else
                    <div class="eah-next-empty">
                        Belum ada agenda rapat mendatang. Gunakan tombol cepat di samping untuk mulai menjadwalkan aktivitas baru.
                    </div>
                @endif
            </div>
        </div>

        <div class="eah-stats">
            <div class="eah-stat eah-animate" style="--eah-delay: 70ms;">
                <div class="eah-stat-icon eah-stat-icon--blue">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="eah-icon" />
                </div>
                <div>
                    <div class="eah-stat-value">{{ $todayMeetingsCount }}</div>
                    <div class="eah-stat-label">Agenda hari ini</div>
                </div>
            </div>

            <div class="eah-stat eah-animate" style="--eah-delay: 140ms;">
                <div class="eah-stat-icon eah-stat-icon--amber">
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" class="eah-icon" />
                </div>
                <div>
                    <div class="eah-stat-value">{{ $documentsNeedActionCount }}</div>
                    <div class="eah-stat-label">Perlu tindakan</div>
                </div>
            </div>

            <div class="eah-stat eah-animate" style="--eah-delay: 210ms;">
                <div class="eah-stat-icon eah-stat-icon--emerald">
                    <x-filament::icon icon="heroicon-o-bell" class="eah-icon" />
                </div>
                <div>
                    <div class="eah-stat-value">{{ $unreadNotificationsCount }}</div>
                    <div class="eah-stat-label">Notifikasi belum dibaca</div>
                </div>
            </div>
        </div>

    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .eah-shell {
            display: grid;
            gap: 1rem;
        }

        .eah-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            align-items: start;
        }

        @media (max-width: 900px) {
            .eah-grid-2col {
                grid-template-columns: 1fr;
            }
        }

        .eah-hero,
        .eah-stat,
        .eah-panel {
            opacity: 0;
            transform: translateY(14px);
            animation: eah-rise 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            animation-delay: var(--eah-delay, 0ms);
        }

        .eah-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(280px, 0.9fr);
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 1.25rem;
            background:
                radial-gradient(circle at top right, rgba(96, 165, 250, 0.24), transparent 32%),
                linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .eah-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, transparent 25%, rgba(255, 255, 255, 0.08) 50%, transparent 75%);
            transform: translateX(-120%);
            animation: eah-shimmer 6s linear infinite;
            pointer-events: none;
        }

        .eah-kicker {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 0.45rem;
        }

        .eah-title {
            font-size: 1.7rem;
            font-weight: 800;
            line-height: 1.15;
            margin: 0;
        }

        .eah-subtitle {
            max-width: 60ch;
            margin-top: 0.65rem;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.6;
        }

        .eah-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            align-items: center;
            margin-top: 1rem;
        }

        .eah-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            backdrop-filter: blur(12px);
        }

        .eah-chip--rose {
            background: rgba(224, 231, 255, 0.22);
            color: #e0e7ff;
        }

        .eah-chip--amber {
            background: rgba(186, 230, 253, 0.22);
            color: #e0f2fe;
        }

        .eah-chip--emerald {
            background: rgba(226, 232, 240, 0.22);
            color: #f1f5f9;
        }

        .eah-meta-text {
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.9rem;
        }

        .eah-next-card {
            border-radius: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .eah-next-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .eah-next-link {
            color: #fff;
            text-decoration: none;
            display: grid;
            gap: 0.35rem;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .eah-next-link:hover {
            transform: translateY(-2px);
        }

        .eah-next-title {
            font-size: 1.08rem;
            font-weight: 700;
            line-height: 1.4;
        }

        .eah-next-time,
        .eah-next-location,
        .eah-next-empty {
            color: rgba(255, 255, 255, 0.84);
            line-height: 1.55;
        }

        .eah-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .eah-stat {
            display: flex;
            gap: 0.95rem;
            align-items: center;
            background: #fff;
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(148, 163, 184, 0.14);
            box-shadow: 0 18px 40px -32px rgba(15, 23, 42, 0.55);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
        }

        .eah-stat:hover,
        .eah-panel:hover {
            transform: translateY(-3px);
            box-shadow: 0 22px 50px -30px rgba(37, 99, 235, 0.35);
        }

        .eah-stat-icon {
            width: 2.9rem;
            height: 2.9rem;
            border-radius: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .eah-stat-icon--blue {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .eah-stat-icon--amber {
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            color: #0284c7;
        }

        .eah-stat-icon--emerald {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            color: #0f172a;
        }

        .eah-icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .eah-stat-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .eah-stat-label {
            margin-top: 0.3rem;
            color: #64748b;
            font-size: 0.92rem;
        }

        .eah-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.95fr);
            gap: 1rem;
        }

        .eah-panel {
            background: #fff;
            border-radius: 1.15rem;
            padding: 1.2rem;
            border: 1px solid rgba(148, 163, 184, 0.14);
            box-shadow: 0 18px 40px -32px rgba(15, 23, 42, 0.55);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
        }

        .eah-panel-head {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .eah-panel-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .eah-panel-desc {
            margin-top: 0.25rem;
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .eah-tabs {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem;
            border-radius: 999px;
            background: #eff6ff;
        }

        .eah-tab {
            border: none;
            background: transparent;
            color: #475569;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .eah-tab--active {
            background: #fff;
            color: #1d4ed8;
            box-shadow: 0 8px 16px -12px rgba(37, 99, 235, 0.55);
        }

        .eah-list {
            display: grid;
            gap: 0.75rem;
        }

        .eah-tab-stage {
            min-height: 10rem;
        }

        .eah-item,
        .eah-action {
            opacity: 0;
            transform: translateY(10px);
            animation: eah-rise 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            animation-delay: var(--eah-delay, 0ms);
        }

        .eah-item {
            display: flex;
            justify-content: space-between;
            gap: 0.9rem;
            align-items: center;
            padding: 0.9rem 1rem;
            border-radius: 1rem;
            text-decoration: none;
            color: inherit;
            background: linear-gradient(135deg, #f8fafc, #eff6ff);
            border: 1px solid transparent;
            transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.22s ease, box-shadow 0.22s ease;
        }

        .eah-item:hover,
        .eah-action:hover {
            transform: translateY(-2px);
            border-color: rgba(59, 130, 246, 0.24);
            box-shadow: 0 14px 24px -20px rgba(37, 99, 235, 0.45);
        }

        .eah-item-main {
            min-width: 0;
        }

        .eah-item-title,
        .eah-action-title,
        .eah-note-title {
            font-weight: 700;
            color: #0f172a;
        }

        .eah-item-meta-line,
        .eah-action-desc,
        .eah-note-text,
        .eah-empty-text {
            margin-top: 0.28rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
            color: #64748b;
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .eah-item-code {
            display: inline-flex;
            align-items: center;
            padding: 0.18rem 0.45rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .eah-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .eah-badge--amber,
        .eah-badge--warning {
            background: #fef3c7;
            color: #92400e;
        }

        .eah-badge--rose {
            background: #ffe4e6;
            color: #be123c;
        }

        .eah-badge--sky,
        .eah-badge--info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .eah-badge--slate,
        .eah-badge--gray {
            background: #e2e8f0;
            color: #334155;
        }

        .eah-badge--emerald,
        .eah-badge--success {
            background: #dcfce7;
            color: #166534;
        }

        .eah-empty {
            border-radius: 1rem;
            padding: 1.2rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px dashed rgba(148, 163, 184, 0.45);
        }

        .eah-empty-title {
            font-weight: 700;
            color: #0f172a;
        }

        .eah-action-list {
            display: grid;
            gap: 0.75rem;
        }

        .eah-action-list--horizontal {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .eah-action {
            display: flex;
            gap: 0.9rem;
            padding: 0.95rem 1rem;
            border-radius: 1rem;
            text-decoration: none;
            color: inherit;
            border: 1px solid transparent;
            transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.22s ease, box-shadow 0.22s ease;
        }

        .eah-action-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .eah-action--indigo {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        }

        .eah-action--indigo .eah-action-icon {
            background: #c7d2fe;
            color: #4338ca;
        }

        .eah-action--blue {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .eah-action--blue .eah-action-icon {
            background: #bfdbfe;
            color: #1d4ed8;
        }

        .eah-action--emerald {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .eah-action--emerald .eah-action-icon {
            background: #a7f3d0;
            color: #047857;
        }

        .eah-action--amber {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .eah-action--amber .eah-action-icon {
            background: #fde68a;
            color: #b45309;
        }

        .eah-action--rose {
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
        }

        .eah-action--rose .eah-action-icon {
            background: #fecdd3;
            color: #e11d48;
        }

        .eah-action-text {
            min-width: 0;
            display: grid;
            gap: 0.22rem;
        }

        .eah-note {
            margin-top: 1rem;
            padding: 0.95rem 1rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #fff;
        }

        .eah-note-title {
            color: #fff;
        }

        .eah-note-text {
            color: rgba(255, 255, 255, 0.8);
        }

        .eah-panel-enter {
            transition: opacity 0.18s ease, transform 0.22s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .eah-panel-enter-from,
        .eah-panel-leave-to {
            opacity: 0;
            transform: translateY(8px);
        }

        .eah-panel-enter-to,
        .eah-panel-leave-from {
            opacity: 1;
            transform: translateY(0);
        }

        .eah-panel-leave {
            transition: opacity 0.14s ease, transform 0.14s ease;
        }

        @keyframes eah-rise {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes eah-shimmer {
            to {
                transform: translateX(120%);
            }
        }

        @media (max-width: 1024px) {
            .eah-hero,
            .eah-grid,
            .eah-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .eah-hero {
                padding: 0.9rem 1rem !important;
                gap: 0.75rem !important;
                border-radius: 1rem !important;
            }

            .eah-kicker {
                font-size: 0.65rem !important;
                margin-bottom: 0.2rem !important;
            }

            .eah-title {
                font-size: 1.2rem !important;
                line-height: 1.2 !important;
            }

            .eah-subtitle {
                font-size: 0.8rem !important;
                margin-top: 0.3rem !important;
                line-height: 1.4 !important;
            }

            .eah-meta {
                margin-top: 0.5rem !important;
                gap: 0.4rem !important;
            }

            .eah-chip {
                padding: 0.2rem 0.6rem !important;
                font-size: 0.7rem !important;
            }

            .eah-meta-text {
                font-size: 0.78rem !important;
            }

            .eah-next-card {
                padding: 0.65rem 0.85rem !important;
                border-radius: 0.8rem !important;
            }

            .eah-next-label {
                font-size: 0.65rem !important;
                margin-bottom: 0.3rem !important;
            }

            .eah-next-title {
                font-size: 0.88rem !important;
            }

            .eah-next-time,
            .eah-next-location,
            .eah-next-empty {
                font-size: 0.76rem !important;
                line-height: 1.35 !important;
            }

            .eah-stat {
                padding: 0.6rem 0.85rem !important;
                gap: 0.6rem !important;
                border-radius: 0.8rem !important;
            }

            .eah-stat-icon {
                width: 2.2rem !important;
                height: 2.2rem !important;
                border-radius: 0.6rem !important;
            }

            .eah-stat-value {
                font-size: 1.15rem !important;
            }

            .eah-stat-label {
                font-size: 0.75rem !important;
                margin-top: 0.1rem !important;
            }

            .eah-panel {
                padding: 0.9rem !important;
                border-radius: 0.9rem !important;
            }

            .eah-panel-head {
                flex-direction: column;
            }

            .eah-tabs {
                width: 100%;
                justify-content: space-between;
            }

            .eah-tab {
                flex: 1;
                text-align: center;
                padding: 0.4rem 0.6rem !important;
                font-size: 0.75rem !important;
            }

            .eah-item,
            .eah-action {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.7rem 0.85rem !important;
            }
        }
    </style>
</x-filament-widgets::widget>
