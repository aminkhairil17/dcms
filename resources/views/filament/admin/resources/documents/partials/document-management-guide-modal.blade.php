<style>
    .dm-guide-wrap {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.875rem;
        color: #374151;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        padding: 0.25rem 0 0.5rem;
    }

    .dm-banner {
        position: relative; overflow: hidden; border-radius: 1rem;
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #3b82f6 100%);
        padding: 1rem 1.2rem; color: #fff;
    }
    .dm-banner-inner { display: flex; align-items: center; gap: 0.85rem; position: relative; z-index: 1; }
    .dm-banner-icon {
        width: 2.4rem; height: 2.4rem; min-width: 2.4rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.6rem; background: rgba(255,255,255,0.18); flex-shrink: 0;
    }
    .dm-banner-glow {
        position: absolute; right: -1.5rem; bottom: -1.5rem;
        width: 6rem; height: 6rem; border-radius: 50%;
        background: rgba(255,255,255,0.08); filter: blur(20px);
    }

    .dm-card {
        border: 1px solid #e5e7eb; border-radius: 0.75rem;
        background: #fff; padding: 0.85rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .dm-card-title {
        font-size: 0.8rem; font-weight: 700; color: #111827; margin: 0 0 0.6rem;
    }

    /* Workflow flow */
    .dm-flow {
        display: flex; align-items: stretch; gap: 0; overflow: hidden;
    }
    .dm-flow-item {
        flex: 1; text-align: center; padding: 0.55rem 0.4rem;
        background: #f9fafb; border: 1px solid #f3f4f6;
        position: relative;
    }
    .dm-flow-item:first-child { border-radius: 0.5rem 0 0 0.5rem; }
    .dm-flow-item:last-child  { border-radius: 0 0.5rem 0.5rem 0; }
    .dm-flow-arrow {
        display: flex; align-items: center; justify-content: center;
        padding: 0 2px; background: #f9fafb; border-top: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6;
        color: #9ca3af; flex-shrink: 0;
    }
    .dm-flow-badge {
        display: inline-block; border-radius: 0.3rem;
        padding: 0.1rem 0.4rem; font-size: 0.64rem; font-weight: 700; margin-bottom: 0.25rem;
    }
    .dm-b-gray   { background: #f1f5f9; color: #475569; }
    .dm-b-amber  { background: #fef3c7; color: #d97706; }
    .dm-b-indigo { background: #e0e7ff; color: #4338ca; }
    .dm-b-green  { background: #dcfce7; color: #16a34a; }
    .dm-b-red    { background: #fee2e2; color: #dc2626; }
    .dm-flow-desc { font-size: 0.62rem; color: #6b7280; line-height: 1.35; }

    /* Role table */
    .dm-role-table { width: 100%; border-collapse: collapse; font-size: 0.73rem; }
    .dm-role-table th {
        background: #f8fafc; color: #374151; font-weight: 700;
        text-align: left; padding: 0.4rem 0.6rem;
        border-bottom: 2px solid #e5e7eb;
    }
    .dm-role-table td {
        padding: 0.4rem 0.6rem; border-bottom: 1px solid #f3f4f6;
        vertical-align: top; line-height: 1.5;
    }
    .dm-role-table tr:last-child td { border-bottom: none; }
    .dm-role-pill {
        display: inline-block; border-radius: 0.3rem;
        padding: 0.08rem 0.4rem; font-size: 0.65rem; font-weight: 700;
    }
    .dm-pill-blue   { background: #dbeafe; color: #1d4ed8; }
    .dm-pill-indigo { background: #e0e7ff; color: #4338ca; }
    .dm-pill-purple { background: #ede9fe; color: #7c3aed; }
    .dm-pill-gray   { background: #f1f5f9; color: #475569; }

    .dm-info {
        display: flex; align-items: flex-start; gap: 0.4rem;
        border-radius: 0.55rem; padding: 0.5rem 0.65rem;
        font-size: 0.72rem; line-height: 1.5; margin-top: 0.6rem;
    }
    .dm-info svg { flex-shrink: 0; margin-top: 2px; }
    .dm-info-blue  { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .dm-info-amber { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

    .dm-footer-tip {
        display: flex; align-items: center; gap: 0.7rem;
        border-radius: 0.75rem; border: 1px solid #bfdbfe;
        background: #eff6ff; padding: 0.6rem 0.85rem;
    }
    .dm-tip-icon {
        width: 1.9rem; height: 1.9rem; min-width: 1.9rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; background: #1d4ed8; color: #fff; flex-shrink: 0;
    }
    .dm-tip-title { font-weight: 700; font-size: 0.74rem; color: #1e3a8a; }
    .dm-tip-desc  { font-size: 0.69rem; color: #1d4ed8; line-height: 1.4; margin-top: 0.08rem; }
</style>

<div class="dm-guide-wrap">

    {{-- Banner --}}
    <div class="dm-banner">
        <div class="dm-banner-inner">
            <div class="dm-banner-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:0.92rem; font-weight:800; margin-bottom:0.1rem;">Panduan Manajemen Dokumen</div>
                <div style="font-size:0.72rem; color:rgba(255,255,255,0.82); line-height:1.5;">Kelola, ajukan, tinjau, dan setujui dokumen SOP melalui alur persetujuan bertahap.</div>
            </div>
        </div>
        <div class="dm-banner-glow"></div>
    </div>

    {{-- Workflow --}}
    <div class="dm-card">
        <p class="dm-card-title">Alur Persetujuan Dokumen</p>
        <div class="dm-flow">
            <div class="dm-flow-item">
                <div class="dm-flow-badge dm-b-gray">Draft</div>
                <div class="dm-flow-desc">Pegawai unggah & simpan dokumen</div>
            </div>
            <div class="dm-flow-arrow">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="dm-flow-item">
                <div class="dm-flow-badge dm-b-amber">Menunggu Kabid</div>
                <div class="dm-flow-desc">Diteruskan ke Kabid untuk ditinjau</div>
            </div>
            <div class="dm-flow-arrow">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="dm-flow-item">
                <div class="dm-flow-badge dm-b-indigo">Menunggu Direktur</div>
                <div class="dm-flow-desc">Kabid setujui → lanjut ke Direktur</div>
            </div>
            <div class="dm-flow-arrow">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="dm-flow-item" style="border-radius:0 0.5rem 0.5rem 0;">
                <div class="dm-flow-badge dm-b-green">Disetujui</div>
                <div class="dm-flow-desc">Direktur setujui → dokumen aktif</div>
            </div>
        </div>
        <div class="dm-info dm-info-amber">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Jika Kabid atau Direktur <strong>menolak</strong>, dokumen kembali ke status <span style="background:#fee2e2;color:#dc2626;border-radius:0.25rem;padding:0 0.3rem;font-size:0.65rem;font-weight:700;">Ditolak</span> dan pembuat dokumen mendapat notifikasi.</span>
        </div>
    </div>

    {{-- Cara ACC / Tolak --}}
    <div class="dm-card">
        <p class="dm-card-title">Cara ACC / Tolak Dokumen</p>
        <table class="dm-role-table">
            <thead>
                <tr>
                    <th>Peran</th>
                    <th>Dokumen yang Dapat Ditinjau</th>
                    <th>Cara Melakukan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="dm-role-pill dm-pill-indigo">Kabid</span></td>
                    <td>Status <strong>Menunggu Kabid</strong></td>
                    <td>Klik <strong>ACC Kabid</strong> atau <strong>Tolak</strong> pada baris dokumen / halaman Tinjauan Dokumen.</td>
                </tr>
                <tr>
                    <td><span class="dm-role-pill dm-pill-purple">Direktur</span></td>
                    <td>Status <strong>Menunggu Direktur</strong></td>
                    <td>Klik <strong>ACC Direktur</strong> atau <strong>Tolak</strong> pada baris dokumen / halaman Tinjauan Dokumen.</td>
                </tr>
                <tr>
                    <td><span class="dm-role-pill dm-pill-blue">Super Admin</span></td>
                    <td>Semua status</td>
                    <td>Dapat ACC / Tolak di semua tahap. Aksi tersedia di kolom tabel dan detail dokumen.</td>
                </tr>
            </tbody>
        </table>
        <div class="dm-info dm-info-blue">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Tinjauan lebih detail tersedia di menu <strong>Tinjauan Dokumen</strong> pada panel Reviewer.</span>
        </div>
    </div>

    {{-- Footer Tip --}}
    <div class="dm-footer-tip">
        <div class="dm-tip-icon">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="dm-tip-title">Tambah Dokumen Baru</div>
            <div class="dm-tip-desc">Klik tombol <strong>"Tambah Dokumen"</strong> di pojok kanan atas untuk mengajukan dokumen baru ke alur persetujuan.</div>
        </div>
    </div>

</div>
