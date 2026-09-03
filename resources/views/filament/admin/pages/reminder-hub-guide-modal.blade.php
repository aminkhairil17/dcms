<style>
    .rh-guide-wrap {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.875rem;
        color: #374151;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        padding: 0.25rem 0 0.5rem;
    }

    .rh-guide-banner {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%);
        padding: 1rem 1.2rem;
        color: #fff;
    }
    .rh-guide-banner-inner { display: flex; align-items: center; gap: 0.85rem; position: relative; z-index: 1; }
    .rh-guide-banner-icon {
        width: 2.4rem; height: 2.4rem; min-width: 2.4rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.6rem; background: rgba(255,255,255,0.2); flex-shrink: 0;
    }
    .rh-guide-banner-glow {
        position: absolute; right: -1.5rem; bottom: -1.5rem;
        width: 6rem; height: 6rem; border-radius: 50%;
        background: rgba(255,255,255,0.08); filter: blur(20px);
    }

    .rh-card {
        border: 1px solid #e5e7eb; border-radius: 0.75rem;
        background: #fff; padding: 0.85rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .rh-card-title {
        font-size: 0.8rem; font-weight: 700; color: #111827; margin: 0 0 0.55rem;
    }

    /* 4-col reminder type grid */
    .rh-type-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.45rem;
    }
    .rh-type-item {
        display: flex; align-items: flex-start; gap: 0.5rem;
        background: #f9fafb; border: 1px solid #f3f4f6;
        border-radius: 0.55rem; padding: 0.55rem 0.65rem;
    }
    .rh-type-dot {
        width: 1.7rem; height: 1.7rem; min-width: 1.7rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.4rem; flex-shrink: 0;
    }
    .rh-dot-amber  { background: #fef3c7; color: #d97706; }
    .rh-dot-blue   { background: #dbeafe; color: #1d4ed8; }
    .rh-dot-green  { background: #dcfce7; color: #16a34a; }
    .rh-dot-red    { background: #fee2e2; color: #dc2626; }
    .rh-dot-purple { background: #ede9fe; color: #7c3aed; }
    .rh-type-name  { font-weight: 600; font-size: 0.76rem; color: #111827; }
    .rh-type-desc  { font-size: 0.68rem; color: #6b7280; line-height: 1.35; margin-top: 0.1rem; }

    .rh-info {
        display: flex; align-items: flex-start; gap: 0.4rem;
        border-radius: 0.55rem; padding: 0.5rem 0.65rem;
        font-size: 0.72rem; line-height: 1.5; margin-top: 0.6rem;
    }
    .rh-info svg { flex-shrink: 0; margin-top: 2px; }
    .rh-info-amber { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .rh-info-blue  { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

    .rh-footer-tip {
        display: flex; align-items: center; gap: 0.7rem;
        border-radius: 0.75rem; border: 1px solid #bfdbfe;
        background: #eff6ff; padding: 0.6rem 0.85rem;
    }
    .rh-tip-icon {
        width: 1.9rem; height: 1.9rem; min-width: 1.9rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; background: #1d4ed8; color: #fff; flex-shrink: 0;
    }
    .rh-tip-title { font-weight: 700; font-size: 0.74rem; color: #1e3a8a; }
    .rh-tip-desc  { font-size: 0.69rem; color: #1d4ed8; line-height: 1.4; margin-top: 0.08rem; }
</style>

<div class="rh-guide-wrap">

    {{-- Banner --}}
    <div class="rh-guide-banner">
        <div class="rh-guide-banner-inner">
            <div class="rh-guide-banner-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>
            <div>
                <div style="font-size:0.92rem; font-weight:800; margin-bottom:0.1rem;">Panduan Pusat Pengingat</div>
                <div style="font-size:0.72rem; color:rgba(255,255,255,0.85); line-height:1.5;">Kirim notifikasi pengingat secara manual ke pegawai atau reviewer sesuai kebutuhan.</div>
            </div>
        </div>
        <div class="rh-guide-banner-glow"></div>
    </div>

    {{-- Jenis Pengingat --}}
    <div class="rh-card">
        <p class="rh-card-title">Jenis Pengingat yang Tersedia</p>
        <div class="rh-type-grid">
            <div class="rh-type-item">
                <div class="rh-type-dot rh-dot-amber">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div>
                    <div class="rh-type-name">Wajib Baca</div>
                    <div class="rh-type-desc">Ingatkan pegawai yang belum membaca dokumen wajib baca divisi mereka.</div>
                </div>
            </div>
            <div class="rh-type-item">
                <div class="rh-type-dot rh-dot-blue">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="rh-type-name">Pending Review</div>
                    <div class="rh-type-desc">Ingatkan Kabid & Direktur untuk menyelesaikan persetujuan dokumen yang menunggu.</div>
                </div>
            </div>
            <div class="rh-type-item">
                <div class="rh-type-dot rh-dot-green">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
                </div>
                <div>
                    <div class="rh-type-name">Pengingat Rapat</div>
                    <div class="rh-type-desc">Kirim pengingat jadwal rapat dalam 7 hari ke depan ke seluruh peserta.</div>
                </div>
            </div>
            <div class="rh-type-item">
                <div class="rh-type-dot rh-dot-red">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <div class="rh-type-name">Kedaluwarsa Dokumen</div>
                    <div class="rh-type-desc">Ingatkan pemilik & penerima dokumen yang akan kedaluwarsa dalam 30 hari.</div>
                </div>
            </div>
        </div>
        <div class="rh-info rh-info-blue">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Tombol pengingat hanya muncul jika Anda memiliki izin yang sesuai untuk masing-masing jenis pengingat.</span>
        </div>
    </div>

    {{-- Pengingat Pribadi --}}
    <div class="rh-card">
        <p class="rh-card-title">Pengingat Pribadi</p>
        <p style="font-size:0.75rem; color:#374151; line-height:1.65; margin:0;">
            Klik <strong>"Buat Pengingat Pribadi"</strong>, isi judul dan catatan, lalu kirim. Notifikasi akan muncul langsung di lonceng notifikasi akun Anda sendiri sebagai pengingat personal.
        </p>
        <div class="rh-info rh-info-amber">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Pengingat pribadi hanya dikirim ke akun Anda sendiri, tidak ke pengguna lain.</span>
        </div>
    </div>

    {{-- Footer Tip --}}
    <div class="rh-footer-tip">
        <div class="rh-tip-icon">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="rh-tip-title">Pengiriman Manual</div>
            <div class="rh-tip-desc">Semua pengingat di halaman ini dikirim secara manual. Klik tombol pengingat yang diinginkan, sistem akan langsung memproses dan mengirimkan notifikasi.</div>
        </div>
    </div>

</div>
