<style>
    @keyframes dms-badge-enter {
        0% {
            opacity: 0;
            transform: translateY(8px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #dms-readiness-badge {
        position: fixed !important;
        bottom: 1.75rem;
        right: 1.75rem;
        z-index: 9999 !important;
        user-select: none !important;
        touch-action: none !important;
        cursor: grab !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 0.5rem !important;
        width: max-content !important;
        min-width: 0 !important;
        max-width: calc(100vw - 2rem) !important;
        height: 44px !important;
        box-sizing: border-box !important;
        background: rgba(255, 255, 255, 0.97) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        border: 1.5px solid rgba(148, 163, 184, 0.25) !important;
        border-radius: 9999px !important;
        padding: 0 0.875rem 0 0.5rem !important;
        box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.14), 0 2px 8px rgba(0,0,0,0.04) !important;
        will-change: transform, left, top, box-shadow;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.35s ease !important;
        animation: dms-badge-enter 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* ── Mobile: smaller, more compact ── */
    @media (max-width: 1023.98px) {
        #dms-readiness-badge {
            bottom: calc(48px + 0.75rem) !important;
            right: 0.75rem !important;
            height: 40px !important;
            gap: 0.4rem !important;
            padding: 0 0.75rem 0 0.4rem !important;
        }
    }

    #dms-readiness-badge:hover {
        transform: translateY(-1.5px) !important;
        border-color: rgba(37, 99, 235, 0.35) !important;
        box-shadow: 0 12px 28px -4px rgba(37, 99, 235, 0.22) !important;
    }

    #dms-readiness-badge.snapping {
        transition: left 0.7s cubic-bezier(0.16, 1, 0.3, 1),
                    top  0.7s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.35s ease,
                    box-shadow 0.35s ease !important;
    }

    #dms-readiness-badge.dragging {
        cursor: grabbing !important;
        transform: scale(1.02) !important;
        box-shadow: 0 16px 36px -4px rgba(37, 99, 235, 0.28) !important;
        border-color: rgba(37, 99, 235, 0.4) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    /* ── Progress ring ── */
    .dms-ring-wrap {
        position: relative;
        width: 2.2rem;
        height: 2.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    @media (max-width: 1023.98px) {
        .dms-ring-wrap {
            width: 1.9rem;
            height: 1.9rem;
        }
    }

    .dms-ring-wrap svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .dms-ring-bg-path {
        fill: none;
        stroke: rgba(148, 163, 184, 0.2);
        stroke-width: 4;
    }

    .dms-ring-fg-path {
        fill: none;
        stroke: url(#dmsGradId);
        stroke-width: 4;
        stroke-linecap: round;
        transition: stroke-dasharray 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .dms-ring-pct {
        position: absolute;
        font-size: 0.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        pointer-events: none;
        transition: opacity 0.3s ease;
        letter-spacing: -0.02em;
    }

    @media (max-width: 1023.98px) {
        .dms-ring-pct {
            font-size: 0.55rem;
        }
    }

    /* ── Badge label ── */
    .dms-badge-label {
        display: flex;
        flex-direction: column;
        gap: 0.06rem;
        pointer-events: none;
        min-width: 0;
    }

    .dms-badge-title {
        font-size: 0.74rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
        white-space: nowrap;
        letter-spacing: -0.01em;
    }

    .dms-badge-sub {
        font-size: 0.67rem;
        font-weight: 600;
        color: #2563eb;
        white-space: nowrap;
        letter-spacing: -0.01em;
    }

    @media (max-width: 1023.98px) {
        .dms-badge-title {
            font-size: 0.7rem;
        }
        .dms-badge-sub {
            font-size: 0.62rem;
        }
    }
</style>


{{-- Wrapper just used by Alpine to inject progress value into the script --}}
<div x-data style="display:none" data-progress="{{ $progress }}" id="dms-progress-source"></div>

<div x-data>
    <template x-teleport="body">
        <div id="dms-readiness-badge">
            <div class="dms-ring-wrap">
                <svg viewBox="0 0 36 36">
                    <defs>
                        <linearGradient id="dmsGradId" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%"   stop-color="#2563eb"/>
                            <stop offset="100%" stop-color="#38bdf8"/>
                        </linearGradient>
                    </defs>
                    <path class="dms-ring-bg-path"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    <path class="dms-ring-fg-path" id="dms-ring-fill"
                        stroke-dasharray="{{ $progress }}, 100"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
                <span class="dms-ring-pct" id="dms-pct-text">{{ $progress }}%</span>
            </div>

            <div class="dms-badge-label">
                <span class="dms-badge-title">Kesiapan Dokumen</span>
                <span class="dms-badge-sub" id="dms-sub-text">{{ $progress }}% Terisi</span>
            </div>
        </div>
    </template>
</div>

<script>
(function () {
    function initBadge() {
        const badge = document.getElementById('dms-readiness-badge');
        if (!badge) { setTimeout(initBadge, 100); return; }

        const PAD   = 20;
        let dragging = false;
        let ox = 0, oy = 0; // offset from pointer to badge top-left
        let raf = null;
        let pendingX = 0, pendingY = 0;

        /* ── Get topbar bottom edge (blue bar) as minimum Y ── */
        function getTopbarHeight() {
            const topbar = document.querySelector('.fi-topbar-ctn');
            return topbar ? (topbar.getBoundingClientRect().bottom + PAD) : PAD;
        }

        /* ── Get bottom navbar height as maximum Y offset ── */
        function getBottomPad() {
            const bottomNav = document.querySelector('.dcms-mobile-bottom-bar');
            if (bottomNav && window.getComputedStyle(bottomNav).display !== 'none') {
                return bottomNav.getBoundingClientRect().height + PAD;
            }
            return PAD;
        }

        /* ── Restore saved position ── */
        try {
            const saved = JSON.parse(localStorage.getItem('dms_badge_v4') || 'null');
            if (saved && typeof saved.left === 'number' && typeof saved.top === 'number' && saved.left >= 0 && saved.left <= window.innerWidth - 60 && saved.top >= 0 && saved.top <= window.innerHeight - 40) {
                const minY = getTopbarHeight();
                const badgeRect = badge.getBoundingClientRect();
                const h = (badgeRect && badgeRect.height > 0) ? badgeRect.height : 52;
                const maxTop  = window.innerHeight - h - getBottomPad();
                badge.style.right  = 'auto';
                badge.style.bottom = 'auto';
                badge.style.left   = Math.max(PAD, Math.min(saved.left, window.innerWidth - 120)) + 'px';
                /* clamp saved top so it doesn't restore above topbar or below bottom navbar */
                badge.style.top    = Math.min(Math.max(minY, saved.top), Math.max(minY, maxTop)) + 'px';
            } else {
                localStorage.removeItem('dms_badge_v4');
            }
        } catch(e) {}

        /* ── Snap to nearest left/right edge ── */
        function snapToEdge() {
            const rect   = badge.getBoundingClientRect();
            const W      = window.innerWidth;
            const cx     = rect.left + rect.width / 2;
            const minY   = getTopbarHeight();
            const maxTop = window.innerHeight - rect.height - getBottomPad();

            /* Always snap left or right */
            const snapLeft = cx < W / 2
                ? PAD
                : W - rect.width - PAD;

            /* Clamp top — never above topbar, never below bottom navbar */
            const snapTop = Math.min(
                Math.max(minY, rect.top),
                Math.max(minY, maxTop)
            );

            badge.classList.add('snapping');
            badge.style.left = snapLeft + 'px';
            badge.style.top  = snapTop  + 'px';

            localStorage.setItem('dms_badge_v4', JSON.stringify({ left: snapLeft, top: snapTop }));

            setTimeout(() => badge.classList.remove('snapping'), 750);
        }

        /* ── Pointer start ── */
        function onStart(e) {
            e.preventDefault();
            dragging = true;

            const rect = badge.getBoundingClientRect();
            badge.style.right  = 'auto';
            badge.style.bottom = 'auto';
            badge.style.left   = rect.left + 'px';
            badge.style.top    = rect.top  + 'px';
            badge.classList.remove('snapping');
            badge.classList.add('dragging');

            const pt = e.touches ? e.touches[0] : e;
            ox = pt.clientX - rect.left;
            oy = pt.clientY - rect.top;

            window.addEventListener('pointermove', onMove, { passive: false });
            window.addEventListener('pointerup',   onEnd);
            window.addEventListener('touchmove',   onMove, { passive: false });
            window.addEventListener('touchend',    onEnd);
        }

        /* ── Pointer move (via rAF for smoothness) ── */
        function onMove(e) {
            e.preventDefault();
            const pt     = e.touches ? e.touches[0] : e;
            const rect   = badge.getBoundingClientRect();
            const W      = window.innerWidth, H = window.innerHeight;
            const minY   = getTopbarHeight();
            const maxTop = H - rect.height - getBottomPad();
            pendingX = Math.min(Math.max(PAD, pt.clientX - ox), W - rect.width  - PAD);
            /* Clamp Y so badge never enters topbar or bottom navbar area */
            pendingY = Math.min(Math.max(minY, pt.clientY - oy), Math.max(minY, maxTop));
            if (!raf) raf = requestAnimationFrame(applyMove);
        }

        function applyMove() {
            raf = null;
            badge.style.left = pendingX + 'px';
            badge.style.top  = pendingY + 'px';
        }

        /* ── Pointer end ── */
        function onEnd() {
            if (!dragging) return;
            dragging = false;
            badge.classList.remove('dragging');

            window.removeEventListener('pointermove', onMove);
            window.removeEventListener('pointerup',   onEnd);
            window.removeEventListener('touchmove',   onMove);
            window.removeEventListener('touchend',    onEnd);

            if (raf) { cancelAnimationFrame(raf); raf = null; }

            snapToEdge();
        }

        badge.addEventListener('pointerdown', onStart);
        badge.addEventListener('touchstart',  onStart, { passive: false });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBadge);
    } else {
        initBadge();
    }
})();
</script>
