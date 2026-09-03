<div class="dcms-quick-access-widget" style="
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 12px -2px rgba(0,0,0,0.06);
    overflow: hidden;
    margin-bottom: 8px;
">
    {{-- Header --}}
    <div style="
        padding: 20px 24px 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 14px;
    ">
        <div style="
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(30,64,175,0.25);
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:22px;height:22px">
                <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
        </div>
        <div>
            <div style="font-size:15px; font-weight:800; color:#1e293b; letter-spacing:-0.01em;">Akses Cepat & Pintasan</div>
            <div style="font-size:12px; color:#64748b; margin-top:2px;">Pintasan aksi utama dan pemantauan status dokumen</div>
        </div>
    </div>

    <div style="padding: 20px 24px;">

        {{-- Quick Actions Grid --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">

            {{-- 1. Create Document --}}
            <a href="/admin/documents/create" style="
                display:flex; align-items:center; gap:12px;
                padding: 14px 16px;
                background: linear-gradient(135deg, #eff6ff, #dbeafe);
                border: 1px solid rgba(59,130,246,0.2);
                border-radius: 12px;
                text-decoration: none;
                transition: all 0.2s ease;
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59,130,246,0.15)';"
               onmouseout="this.style.transform=''; this.style.boxShadow='';">
                <div style="width:36px; height:36px; background:#2563eb; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:white;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:700; color:#1e3a8a;">Buat SOP Baru</div>
                    <div style="font-size:11px; color:#3b82f6; margin-top:1px;">Unggah dokumen baru</div>
                </div>
            </a>

            {{-- 2. Employee Reminder --}}
            <a href="{{ route('filament.admin.pages.reminders') }}" style="
                display:flex; align-items:center; gap:12px;
                padding: 14px 16px;
                background: linear-gradient(135deg, #fff1f2, #ffe4e6);
                border: 1px solid rgba(244,63,94,0.25);
                border-radius: 12px;
                text-decoration: none;
                transition: all 0.2s ease;
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(244,63,94,0.15)';"
               onmouseout="this.style.transform=''; this.style.boxShadow='';">
                <div style="width:36px; height:36px; background:#e11d48; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:white;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:700; color:#881337;">Buat Reminder</div>
                    <div style="font-size:11px; color:#be123c; margin-top:1px;">Ingatkan pegawai baca SOP</div>
                </div>
            </a>

            {{-- 3. Compliance Hub --}}
            <a href="/admin/compliance-hub" style="
                display:flex; align-items:center; gap:12px;
                padding: 14px 16px;
                background: linear-gradient(135deg, #ecfdf5, #d1fae5);
                border: 1px solid rgba(16,185,129,0.2);
                border-radius: 12px;
                text-decoration: none;
                transition: all 0.2s ease;
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(16,185,129,0.15)';"
               onmouseout="this.style.transform=''; this.style.boxShadow='';">
                <div style="width:36px; height:36px; background:#059669; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:white;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:13px; font-weight:700; color:#065f46;">Compliance Hub</span>
                        @if($unreadComplianceCount > 0)
                        <span style="font-size:10px; font-weight:800; background:#ef4444; color:white; padding:2px 6px; border-radius:50px;">{{ $unreadComplianceCount }}</span>
                        @endif
                    </div>
                    <div style="font-size:11px; color:#047857; margin-top:1px;">Dokumen wajib baca</div>
                </div>
            </a>

            {{-- 4. Bookmarks --}}
            <a href="/admin/bookmarks" style="
                display:flex; align-items:center; gap:12px;
                padding: 14px 16px;
                background: linear-gradient(135deg, #f5f3ff, #ede9fe);
                border: 1px solid rgba(139,92,246,0.2);
                border-radius: 12px;
                text-decoration: none;
                transition: all 0.2s ease;
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(139,92,246,0.15)';"
               onmouseout="this.style.transform=''; this.style.boxShadow='';">
                <div style="width:36px; height:36px; background:#7c3aed; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:white;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" /></svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:13px; font-weight:700; color:#4c1d95;">Dokumen Tersimpan</span>
                        @if($bookmarkCount > 0)
                        <span style="font-size:10px; font-weight:800; background:#7c3aed; color:white; padding:2px 6px; border-radius:50px;">{{ $bookmarkCount }}</span>
                        @endif
                    </div>
                    <div style="font-size:11px; color:#6d28d9; margin-top:1px;">Akses cepat favorit</div>
                </div>
            </a>
        </div>

        {{-- My Recent Documents Section --}}
        <div style="margin-bottom:16px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#64748b" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    <span>Dokumen Terakhir Anda</span>
                </div>
                <a href="/admin/documents" style="font-size:11px; font-weight:600; color:#2563eb; text-decoration:none;">Lihat Semua →</a>
            </div>

            <div style="display:flex; flex-direction:column; gap:6px;">
                @forelse($myRecentDocs as $doc)
                @php
                    $statusConfig = match($doc->status) {
                        'draft'             => ['label' => 'Draft', 'bg' => '#f1f5f9', 'text' => '#475569'],
                        'pending_kabid'    => ['label' => 'Review Kabid', 'bg' => '#fef3c7', 'text' => '#b45309'],
                        'pending_direktur' => ['label' => 'Decision Direktur', 'bg' => '#dbeafe', 'text' => '#1d4ed8'],
                        'approved'         => ['label' => 'Disetujui', 'bg' => '#dcfce7', 'text' => '#15803d'],
                        'rejected'         => ['label' => 'Ditolak', 'bg' => '#fee2e2', 'text' => '#b91c1c'],
                        default            => ['label' => ucfirst($doc->status), 'bg' => '#f1f5f9', 'text' => '#475569'],
                    };
                @endphp
                <a href="/admin/documents/{{ $doc->id }}" style="
                    display:flex; align-items:center; justify-content:space-between; gap:10px;
                    padding: 8px 12px;
                    background: #f8fafc;
                    border: 1px solid rgba(0,0,0,0.04);
                    border-radius: 8px;
                    text-decoration: none;
                    transition: background-color 0.15s ease;
                " onmouseover="this.style.backgroundColor='#f1f5f9';" onmouseout="this.style.backgroundColor='#f8fafc';">
                    <div style="min-width:0; flex:1;">
                        <div style="font-size:12px; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $doc->title }}</div>
                        <div style="font-size:10px; color:#94a3b8; margin-top:1px;">{{ $doc->code_number ?? 'SOP' }} · {{ $doc->updated_at->diffForHumans() }}</div>
                    </div>
                    <div style="--st-bg: {{ $statusConfig['bg'] }}; --st-tx: {{ $statusConfig['text'] }}; background: var(--st-bg); color: var(--st-tx); padding:2px 8px; border-radius:50px; font-size:10px; font-weight:700; flex-shrink:0;">
                        {{ $statusConfig['label'] }}
                    </div>
                </a>
                @empty
                <div style="font-size:11px; color:#94a3b8; text-align:center; padding:12px; background:#f8fafc; border-radius:8px;">Belum ada dokumen yang Anda buat</div>
                @endforelse
            </div>
        </div>

        {{-- Expiry Alert / Health Banner --}}
        @if($hasExpiryAlerts)
        <div style="
            padding: 12px 14px;
            background: linear-gradient(135deg, #fef2f2, #fff7ed);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
        ">
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; background:rgba(239,68,68,0.1); border-radius:6px; display:flex; align-items:center; justify-content:center; color:#ef4444; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                </div>
                <div>
                    <div style="font-size:12px; font-weight:700; color:#991b1b;">Perhatian Masa Berlaku</div>
                    <div style="font-size:10px; color:#b91c1c;">
                        {{ $expiredCount > 0 ? $expiredCount . ' kedaluwarsa' : '' }}
                        {{ ($expiredCount > 0 && $expiringSoonCount > 0) ? ' · ' : '' }}
                        {{ $expiringSoonCount > 0 ? $expiringSoonCount . ' mendekati kedaluwarsa' : '' }}
                    </div>
                </div>
            </div>
            <a href="/admin/documents" style="font-size:10px; font-weight:700; color:#ef4444; background:white; padding:4px 10px; border-radius:6px; text-decoration:none; border:1px solid rgba(239,68,68,0.2); flex-shrink:0;">Tinjau</a>
        </div>
        @else
        <div style="
            padding: 10px 14px;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 10px;
            display: flex; align-items: center; gap: 8px;
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#059669" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            <span style="font-size:11px; font-weight:600; color:#065f46;">Semua dokumen aktif &amp; dalam kondisi valid</span>
        </div>
        @endif

    </div>
</div>
