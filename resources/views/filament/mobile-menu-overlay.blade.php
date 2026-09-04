@php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$panelPath = request()->segment(1) ?: 'admin';

// Build user avatar initials
$name = $user?->name ?? 'User';
$initials = collect(explode(' ', $name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');

// Determine which menu items to show based on permissions/roles
$isSuperAdmin = $user?->hasRole('super_admin');
$isDirektur   = $user?->hasRole('direktur');
$isKabid      = $user?->hasRole('kabid');
$isAdmin      = $isSuperAdmin || $isDirektur || $isKabid;

// Menu structure: groups → items
$menuGroups = [
    [
        'label' => 'Utama',
        'items' => [
            ['label' => 'Beranda',    'url' => url("/{$panelPath}"),             'icon' => 'home',       'color' => '#2563eb'],
            ['label' => 'Dokumen',    'url' => url("/{$panelPath}/documents"),   'icon' => 'document',   'color' => '#7c3aed'],
            ['label' => 'Tambah Dok', 'url' => url("/{$panelPath}/documents/create"), 'icon' => 'add_doc', 'color' => '#059669'],
            ['label' => 'Rapat',      'url' => url("/{$panelPath}/meetings"),    'icon' => 'calendar',   'color' => '#0891b2'],
            ['label' => 'Tambah Rapat', 'url' => url("/{$panelPath}/meetings/create"), 'icon' => 'add_cal', 'color' => '#d97706'],
        ],
    ],
    [
        'label' => 'Dokumen',
        'items' => [
            ['label' => 'Dokumen Saya',  'url' => url("/{$panelPath}/documents"),         'icon' => 'my_doc',    'color' => '#4f46e5'],
            ['label' => 'Bookmark',      'url' => url("/{$panelPath}/bookmarks"),          'icon' => 'bookmark',  'color' => '#db2777'],
            ['label' => 'Pengingat',     'url' => url("/{$panelPath}/reminders"),          'icon' => 'bell',      'color' => '#ea580c'],
            ['label' => 'Perubahan',     'url' => url("/{$panelPath}/document-change-requests"), 'icon' => 'change', 'color' => '#0f766e'],
        ],
    ],
    [
        'label' => 'Rapat',
        'items' => [
            ['label' => 'Semua Rapat',   'url' => url("/{$panelPath}/meetings"),            'icon' => 'meetings',  'color' => '#2563eb'],
            ['label' => 'Jadwal Saya',   'url' => url("/{$panelPath}/meetings"),            'icon' => 'my_cal',    'color' => '#7c3aed'],
        ],
    ],
    [
        'label' => 'Pengaturan',
        'items' => array_filter([
            $isAdmin ? ['label' => 'Pengguna',    'url' => url("/{$panelPath}/users"),           'icon' => 'users',     'color' => '#1d4ed8'] : null,
            $isAdmin ? ['label' => 'Departemen',  'url' => url("/{$panelPath}/departments"),      'icon' => 'dept',      'color' => '#7c3aed'] : null,
            $isSuperAdmin ? ['label' => 'Perusahaan', 'url' => url("/{$panelPath}/companies"),   'icon' => 'company',   'color' => '#059669'] : null,
            $isAdmin ? ['label' => 'Lokasi Rapat','url' => url("/{$panelPath}/meeting-locations"), 'icon' => 'location', 'color' => '#dc2626'] : null,
            $isSuperAdmin ? ['label' => 'Peran', 'url' => url("/{$panelPath}/shield/roles"),    'icon' => 'role',      'color' => '#9333ea'] : null,
            $isAdmin ? ['label' => 'Kategori',    'url' => url("/{$panelPath}/document-categories"), 'icon' => 'category', 'color' => '#0891b2'] : null,
            $isSuperAdmin ? ['label' => 'Audit', 'url' => url("/{$panelPath}/audit-trail"),     'icon' => 'audit',     'color' => '#64748b'] : null,
            ['label' => 'Profil Saya',   'url' => url("/{$panelPath}/profile"),            'icon' => 'profile',   'color' => '#475569'],
        ]),
    ],
];
@endphp

{{-- ══════════════════════════════════════════════════════════
     DCMS MOBILE MENU OVERLAY
     Shown when user taps "Menu" in bottom bar (mobile only)
══════════════════════════════════════════════════════════ --}}
<div id="dcms-mobile-menu-overlay"
     class="dcms-menu-overlay"
     aria-hidden="true"
     role="dialog"
     aria-modal="true"
     aria-label="Menu Navigasi"
     onclick="if(event.target===this)dcmsCloseMenu()">

    {{-- Drawer Sheet --}}
    <div class="dcms-menu-sheet" role="document">

        {{-- Handle Bar --}}
        <div class="dcms-menu-handle"></div>

        {{-- User Profile Header --}}
        <div class="dcms-menu-header">
            <div class="dcms-menu-avatar">{{ $initials }}</div>
            <div class="dcms-menu-user-info">
                <div class="dcms-menu-user-name">{{ $user?->name ?? 'Pengguna' }}</div>
                <div class="dcms-menu-user-email">{{ $user?->email ?? '' }}</div>
            </div>
            <button class="dcms-menu-close-btn" onclick="dcmsCloseMenu()" aria-label="Tutup menu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Menu Scrollable Body --}}
        <div class="dcms-menu-body">
            @foreach ($menuGroups as $group)
                @php $items = array_values(array_filter($group['items'])); @endphp
                @if (!empty($items))
                <div class="dcms-menu-group">
                    <div class="dcms-menu-group-label">{{ strtoupper($group['label']) }}</div>
                    <div class="dcms-menu-grid">
                        @foreach ($items as $item)
                        <a href="{{ $item['url'] }}"
                           class="dcms-menu-item"
                           onclick="dcmsCloseMenu()"
                           aria-label="{{ $item['label'] }}">
                            <div class="dcms-menu-item-icon" style="background: {{ $item['color'] }}20; color: {{ $item['color'] }}">
                                @include('filament.mobile-menu-icon', ['icon' => $item['icon'], 'color' => $item['color']])
                            </div>
                            <span class="dcms-menu-item-label">{{ $item['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach

            {{-- Logout --}}
            <div class="dcms-menu-group">
                <div class="dcms-menu-group-label">AKUN</div>
                <div class="dcms-menu-grid">
                    <a href="{{ url("/{$panelPath}/profile") }}"
                       class="dcms-menu-item"
                       onclick="dcmsCloseMenu()">
                        <div class="dcms-menu-item-icon" style="background: #f1f5f9; color: #475569">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        </div>
                        <span class="dcms-menu-item-label">Profil</span>
                    </a>
                    <form method="POST" action="{{ url('/admin/logout') }}" style="display:contents">
                        @csrf
                        <button type="submit" class="dcms-menu-item dcms-menu-logout">
                            <div class="dcms-menu-item-icon" style="background: #fef2f2; color: #dc2626">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                            </div>
                            <span class="dcms-menu-item-label">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>

            <div style="height: 32px"></div>
        </div>
    </div>
</div>

<style>
/* ══════════════════════════════════════════════════════════
   DCMS MOBILE MENU — iOS-style App Grid Drawer
══════════════════════════════════════════════════════════ */

.dcms-menu-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999999;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    align-items: flex-end;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease;
}

.dcms-menu-overlay.dcms-open {
    display: flex;
    opacity: 1;
}

.dcms-menu-overlay.dcms-animating-out {
    opacity: 0;
}

.dcms-menu-sheet {
    background: #ffffff;
    width: 100%;
    max-width: 480px;
    max-height: 92vh;
    border-radius: 24px 24px 0 0;
    display: flex;
    flex-direction: column;
    transform: translateY(100%);
    transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
    overflow: hidden;
}

.dcms-menu-overlay.dcms-open .dcms-menu-sheet {
    transform: translateY(0);
}

.dcms-menu-overlay.dcms-animating-out .dcms-menu-sheet {
    transform: translateY(100%);
}

/* Handle Bar */
.dcms-menu-handle {
    width: 36px;
    height: 4px;
    background: #d1d5db;
    border-radius: 2px;
    margin: 10px auto 0;
    flex-shrink: 0;
}

/* Header */
.dcms-menu-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px 16px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}

.dcms-menu-avatar {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.5px;
}

.dcms-menu-user-info {
    flex: 1;
    min-width: 0;
}

.dcms-menu-user-name {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dcms-menu-user-email {
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
}

.dcms-menu-close-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    -webkit-tap-highlight-color: transparent;
}

.dcms-menu-close-btn svg {
    width: 16px;
    height: 16px;
    color: #64748b;
}

/* Body */
.dcms-menu-body {
    overflow-y: auto;
    flex: 1;
    padding: 8px 0;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
}

/* Groups */
.dcms-menu-group {
    padding: 12px 20px 4px;
}

.dcms-menu-group-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #94a3b8;
    margin-bottom: 10px;
    padding-left: 2px;
}

/* Grid of 4 icons per row */
.dcms-menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-bottom: 8px;
}

/* Each Item */
.dcms-menu-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 10px 4px 8px;
    border-radius: 16px;
    text-decoration: none;
    cursor: pointer;
    background: transparent;
    border: none;
    -webkit-tap-highlight-color: transparent;
    transition: background 0.12s ease, transform 0.12s ease;
    font-family: inherit;
}

.dcms-menu-item:active {
    background: #f1f5f9;
    transform: scale(0.94);
}

.dcms-menu-item-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.dcms-menu-item-icon svg {
    width: 24px;
    height: 24px;
}

.dcms-menu-item-label {
    font-size: 10px;
    font-weight: 500;
    color: #334155;
    text-align: center;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 68px;
}

.dcms-menu-logout {
    width: 100%;
}
</style>

<script>
function dcmsOpenMenu() {
    const overlay = document.getElementById('dcms-mobile-menu-overlay');
    if (!overlay) return;
    overlay.classList.remove('dcms-animating-out');
    overlay.classList.add('dcms-open');
    document.body.style.overflow = 'hidden';
    overlay.removeAttribute('aria-hidden');
}

function dcmsCloseMenu() {
    const overlay = document.getElementById('dcms-mobile-menu-overlay');
    if (!overlay) return;
    overlay.classList.add('dcms-animating-out');
    setTimeout(() => {
        overlay.classList.remove('dcms-open', 'dcms-animating-out');
        document.body.style.overflow = '';
        overlay.setAttribute('aria-hidden', 'true');
    }, 300);
}

// Close on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') dcmsCloseMenu();
});
</script>
