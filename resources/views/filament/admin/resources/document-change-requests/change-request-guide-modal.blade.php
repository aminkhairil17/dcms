<style>
    .cr-guide-wrap {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.875rem;
        color: #374151;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        padding: 0.25rem 0 0.5rem;
    }

    .cr-guide-banner {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 1rem 1.2rem;
        color: #fff;
    }
    .cr-guide-banner-inner { display: flex; align-items: center; gap: 0.85rem; position: relative; z-index: 1; }
    .cr-guide-banner-icon {
        width: 2.4rem; height: 2.4rem; min-width: 2.4rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.6rem; background: rgba(255,255,255,0.18); flex-shrink: 0;
    }
    .cr-guide-banner-glow {
        position: absolute; right: -1.5rem; bottom: -1.5rem;
        width: 6rem; height: 6rem; border-radius: 50%;
        background: rgba(255,255,255,0.08); filter: blur(20px);
    }

    .cr-card {
        border: 1px solid #e5e7eb; border-radius: 0.75rem;
        background: #fff; padding: 0.85rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .cr-flow {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 0.4rem;
    }
    .cr-flow-item {
        background: #f9fafb; border: 1px solid #f3f4f6;
        border-radius: 0.5rem; padding: 0.45rem 0.5rem; text-align: center;
    }
    .cr-badge {
        display: inline-block; border-radius: 0.3rem;
        padding: 0.1rem 0.4rem; font-size: 0.64rem; font-weight: 700; margin-bottom: 0.25rem;
    }
    .cr-b-amber  { background: #fef3c7; color: #d97706; }
    .cr-b-green  { background: #dcfce7; color: #16a34a; }
    .cr-b-red    { background: #fee2e2; color: #dc2626; }
    .cr-flow-desc { font-size: 0.67rem; color: #6b7280; line-height: 1.35; }

    .cr-info {
        display: flex; align-items: flex-start; gap: 0.4rem;
        border-radius: 0.55rem; padding: 0.5rem 0.65rem;
        font-size: 0.72rem; line-height: 1.5; margin-top: 0.6rem;
    }
    .cr-info svg { flex-shrink: 0; margin-top: 2px; }
    .cr-info-blue  { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .cr-info-amber { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

    .cr-footer-tip {
        display: flex; align-items: center; gap: 0.7rem;
        border-radius: 0.75rem; border: 1px solid #c4b5fd;
        background: #f5f3ff; padding: 0.6rem 0.85rem;
    }
    .cr-tip-icon {
        width: 1.9rem; height: 1.9rem; min-width: 1.9rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; background: #7c3aed; color: #fff; flex-shrink: 0;
    }
    .cr-tip-title { font-weight: 700; font-size: 0.74rem; color: #4c1d95; }
    .cr-tip-desc  { font-size: 0.69rem; color: #6d28d9; line-height: 1.4; margin-top: 0.08rem; }
</style>

<div class="cr-guide-wrap">

    {{-- Banner --}}
    <div class="cr-guide-banner">
        <div class="cr-guide-banner-inner">
            <div class="cr-guide-banner-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
            </div>
            <div>
                <div style="font-size:0.92rem; font-weight:800; margin-bottom:0.1rem;">Panduan Usulan Revisi Dokumen</div>
                <div style="font-size:0.72rem; color:rgba(255,255,255,0.82); line-height:1.5;">Ajukan usulan perubahan isi SOP yang sudah diterbitkan kepada Admin / Kabid.</div>
            </div>
        </div>
        <div class="cr-guide-banner-glow"></div>
    </div>

    {{-- Cara Mengajukan --}}
    <div class="cr-card">
        <p style="font-size:0.8rem; font-weight:700; color:#111827; margin:0 0 0.5rem;">Cara Mengajukan</p>
        <ol style="margin:0; padding-left:1.15rem; font-size:0.75rem; color:#374151; line-height:1.75;">
            <li>Klik <strong>"+ Ajukan Usulan Revisi"</strong> di bagian atas halaman.</li>
            <li>Pilih <strong>Dokumen SOP</strong> yang ingin direvisi.</li>
            <li>Isi <strong>Usulan Perubahan</strong> secara spesifik.</li>
            <li>Unggah <strong>Lampiran</strong> (opsional, maks. 10 MB), lalu simpan.</li>
        </ol>
        <div class="cr-info cr-info-blue">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Setelah disimpan, notifikasi otomatis dikirim ke Admin &amp; Kabid untuk ditinjau.</span>
        </div>
    </div>

    {{-- Alur Status --}}
    <div class="cr-card">
        <p style="font-size:0.8rem; font-weight:700; color:#111827; margin:0 0 0.5rem;">Alur Status Usulan</p>
        <div class="cr-flow">
            <div class="cr-flow-item">
                <div class="cr-badge cr-b-amber">Menunggu</div>
                <div class="cr-flow-desc">Sedang ditinjau Admin / Kabid</div>
            </div>
            <div class="cr-flow-item">
                <div class="cr-badge cr-b-green">Disetujui</div>
                <div class="cr-flow-desc">Dokumen dikembalikan ke alur review</div>
            </div>
            <div class="cr-flow-item">
                <div class="cr-badge cr-b-red">Ditolak</div>
                <div class="cr-flow-desc">Usulan tidak diterima</div>
            </div>
        </div>
        <div class="cr-info cr-info-amber">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Usulan yang masih <strong>Menunggu</strong> dapat dihapus untuk membatalkannya.</span>
        </div>
    </div>

    {{-- Footer Tip --}}
    <div class="cr-footer-tip">
        <div class="cr-tip-icon">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="cr-tip-title">Badge di Menu Navigasi</div>
            <div class="cr-tip-desc">Angka kuning menunjukkan jumlah usulan yang sedang menunggu tinjauan.</div>
        </div>
    </div>

</div>
