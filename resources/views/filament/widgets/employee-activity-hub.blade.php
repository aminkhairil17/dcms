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
                <div class="eah-stat-content">
                    <div class="eah-stat-value">{{ $todayMeetingsCount }}</div>
                    <div class="eah-stat-label">Agenda hari ini</div>
                </div>
            </div>

            <div class="eah-stat eah-animate" style="--eah-delay: 140ms;">
                <div class="eah-stat-icon eah-stat-icon--amber">
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" class="eah-icon" />
                </div>
                <div class="eah-stat-content">
                    <div class="eah-stat-value">{{ $documentsNeedActionCount }}</div>
                    <div class="eah-stat-label">Perlu tindakan</div>
                </div>
            </div>

            <div class="eah-stat eah-animate" style="--eah-delay: 210ms;">
                <div class="eah-stat-icon eah-stat-icon--emerald">
                    <x-filament::icon icon="heroicon-o-bell" class="eah-icon" />
                </div>
                <div class="eah-stat-content">
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
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E"),
                radial-gradient(circle at top right, rgba(96, 165, 250, 0.15), transparent 50%),
                radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.2), transparent 50%),
                radial-gradient(circle at center, #1e40af 0%, #1e3a8a 45%, #0f172a 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
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
            align-items: center;
            gap: 1.15rem;
            background: #ffffff;
            border-radius: 999px;
            padding: 0.65rem 1.65rem 0.65rem 0.65rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .eah-stat:hover {
            transform: translateY(-4px) scale(1.015);
            box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.12), 0 8px 12px -6px rgba(15, 23, 42, 0.06);
            border-color: rgba(203, 213, 225, 0.9);
        }

        .eah-panel:hover {
            transform: translateY(-3px);
            box-shadow: 0 22px 50px -30px rgba(37, 99, 235, 0.35);
        }

        .eah-stat-icon {
            flex-shrink: 0;
            width: 3.4rem;
            height: 3.4rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 2;
        }

        .eah-stat:hover .eah-stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .eah-stat-icon--blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(37, 99, 235, 0.4);
        }

        .eah-stat-icon--amber {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(217, 119, 6, 0.4);
        }

        .eah-stat-icon--emerald {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(5, 150, 105, 0.4);
        }

        .eah-icon {
            width: 1.55rem;
            height: 1.55rem;
        }

        .eah-stat-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-right: 0.5rem;
        }

        .eah-stat-value {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #0f172a;
        }

        .eah-stat-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            margin-top: 0.2rem;
            text-transform: capitalize;
            letter-spacing: -0.01em;
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
            align-items: center;
            gap: 1.15rem;
            padding: 0.65rem 1.65rem 0.65rem 0.65rem;
            border-radius: 999px;
            text-decoration: none;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            color: inherit;
        }

        .eah-action:hover {
            transform: translateY(-3px) scale(1.01);
            border-color: rgba(203, 213, 225, 0.9);
            box-shadow: 0 10px 24px -6px rgba(15, 23, 42, 0.1), 0 6px 10px -5px rgba(15, 23, 42, 0.05);
        }

        .eah-action-icon {
            flex-shrink: 0;
            width: 3.2rem;
            height: 3.2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 2;
        }

        .eah-action:hover .eah-action-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .eah-action--indigo .eah-action-icon {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(79, 70, 229, 0.4);
        }

        .eah-action--blue .eah-action-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(37, 99, 235, 0.4);
        }

        .eah-action--emerald .eah-action-icon {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(5, 150, 105, 0.4);
        }

        .eah-action--amber .eah-action-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(217, 119, 6, 0.4);
        }

        .eah-action--rose .eah-action-icon {
            background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px -2px rgba(225, 29, 72, 0.4);
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
                padding: 0.5rem 1.25rem 0.5rem 0.5rem !important;
                border-radius: 999px !important;
                gap: 0.85rem !important;
            }

            .eah-stat-icon {
                width: 2.8rem !important;
                height: 2.8rem !important;
                border-radius: 50% !important;
            }

            .eah-stat-value {
                font-size: 1.35rem !important;
            }

            .eah-stat-label {
                font-size: 0.75rem !important;
                margin-top: 0.1rem !important;
            }
            
            .eah-icon {
                width: 1.25rem !important;
                height: 1.25rem !important;
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
