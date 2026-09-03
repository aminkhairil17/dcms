<style>
    .ch-guide-wrap {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.875rem;
        color: #374151;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 0.25rem 0 0.5rem;
    }

    .ch-guide-banner {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        background: linear-gradient(135deg, #0B2545 0%, #133B6B 50%, #1E40AF 100%);
        padding: 1.1rem 1.25rem;
        color: #fff;
    }
    .ch-guide-banner-inner {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        position: relative;
        z-index: 1;
    }
    .ch-guide-banner-icon {
        width: 2.6rem;
        height: 2.6rem;
        min-width: 2.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.65rem;
        background: rgba(255,255,255,0.18);
        flex-shrink: 0;
    }
    .ch-guide-banner-glow {
        position: absolute;
        right: -1.5rem;
        bottom: -1.5rem;
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        filter: blur(20px);
    }

    .ch-step-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.875rem;
        background: #fff;
        padding: 0.9rem 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .ch-step-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.6rem;
    }
    .ch-step-num {
        width: 1.8rem;
        height: 1.8rem;
        min-width: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.4rem;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .ch-num-1 { background:#dbeafe; color:#1d4ed8; }
    .ch-num-2 { background:#e0e7ff; color:#4338ca; }
    .ch-num-3 { background:#d1fae5; color:#059669; }

    .ch-flow {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.4rem;
        margin-top: 0.5rem;
    }
    .ch-flow-item {
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        border-radius: 0.5rem;
        padding: 0.5rem 0.6rem;
        text-align: center;
    }
    .ch-flow-badge {
        display: inline-block;
        border-radius: 0.3rem;
        padding: 0.1rem 0.45rem;
        font-size: 0.65rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .ch-badge-gray   { background:#f1f5f9; color:#475569; }
    .ch-badge-amber  { background:#fef3c7; color:#d97706; }
    .ch-badge-blue   { background:#dbeafe; color:#1d4ed8; }
    .ch-badge-green  { background:#dcfce7; color:#16a34a; }
    .ch-flow-desc { font-size: 0.68rem; color: #6b7280; line-height: 1.4; }

    .ch-info-box {
        display: flex;
        align-items: flex-start;
        gap: 0.45rem;
        border-radius: 0.6rem;
        padding: 0.55rem 0.7rem;
        font-size: 0.73rem;
        line-height: 1.5;
        margin-top: 0.65rem;
    }
    .ch-info-box svg { flex-shrink: 0; margin-top: 2px; }
    .ch-info-amber { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
    .ch-info-green { background:#f0fdf4; color:#15803d; border:1px solid #86efac; }
    .ch-info-blue  { background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; }

    .ch-footer-tip {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        padding: 0.65rem 0.9rem;
    }
    .ch-tip-icon {
        width: 2rem;
        height: 2rem;
        min-width: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #1e40af;
        color: #fff;
        flex-shrink: 0;
    }
    .ch-tip-title { font-weight: 700; font-size: 0.75rem; color: #1e3a8a; }
    .ch-tip-desc  { font-size: 0.7rem; color: #1d4ed8; line-height: 1.4; margin-top: 0.1rem; }
</style>

<div class="ch-guide-wrap">

    {{-- Banner --}}
    <div class="ch-guide-banner">
        <div class="ch-guide-banner-inner">
            <div class="ch-guide-banner-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <div style="font-size:0.95rem; font-weight:800; margin-bottom:0.15rem;">Panduan Compliance Hub</div>
                <div style="font-size:0.73rem; color:rgba(255,255,255,0.82); line-height:1.5;">Halaman untuk memantau dan mengkonfirmasi dokumen wajib baca divisi Anda.</div>
            </div>
        </div>
        <div class="ch-guide-banner-glow"></div>
    </div>

    {{-- Step 1 --}}
    <div class="ch-step-card">
        <div class="ch-step-header">
            <span class="ch-step-num ch-num-1">1</span>
            <strong style="font-size:0.85rem; color:#111827;">Alur Konfirmasi Dokumen</strong>
        </div>
        <p style="font-size:0.75rem; color:#6b7280; margin:0 0 0.5rem;">Setiap dokumen di bagian <strong>Belum Dibaca</strong> melewati 4 tahap:</p>
        <div class="ch-flow">
            <div class="ch-flow-item">
                <div class="ch-flow-badge ch-badge-gray">Terkunci</div>
                <div class="ch-flow-desc">Buka dokumen terlebih dahulu</div>
            </div>

            <div class="ch-flow-item">
                <div class="ch-flow-badge ch-badge-blue">Siap</div>
                <div class="ch-flow-desc">Tombol konfirmasi aktif</div>
            </div>
            <div class="ch-flow-item">
                <div class="ch-flow-badge ch-badge-green">Selesai</div>
                <div class="ch-flow-desc">Pindah ke bagian Sudah Dibaca</div>
            </div>
        </div>
    </div>

    {{-- Step 2 --}}
    <div class="ch-step-card">
        <div class="ch-step-header">
            <span class="ch-step-num ch-num-2">2</span>
            <strong style="font-size:0.85rem; color:#111827;">Cara Mengkonfirmasi</strong>
        </div>
        <ol style="margin:0; padding-left:1.2rem; font-size:0.76rem; color:#374151; line-height:1.7;">
            <li>Klik <strong>"Buka &amp; Baca Dokumen"</strong> — dokumen terbuka di tab baru.</li>
            <li>Klik <strong>"Konfirmasi Telah Membaca"</strong> setelah tombol aktif.</li>
            <li>Centang pernyataan kepatuhan, lalu klik <strong>"Kirim Konfirmasi"</strong>.</li>
        </ol>
        <div class="ch-info-box ch-info-amber">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Konfirmasi bersifat permanen dan tercatat resmi. Tombol "Kirim" aktif setelah kotak centang diisi.</span>
        </div>
    </div>

    {{-- Step 3 --}}
    <div class="ch-step-card">
        <div class="ch-step-header">
            <span class="ch-step-num ch-num-3">3</span>
            <strong style="font-size:0.85rem; color:#111827;">Indikator Kepatuhan</strong>
        </div>
        <p style="font-size:0.76rem; color:#374151; line-height:1.6; margin:0;">
            Lingkaran persentase di bagian atas menunjukkan tingkat kepatuhan Anda. Saat semua dokumen selesai dikonfirmasi, persentase mencapai <strong>100%</strong> dan badge merah di menu navigasi akan hilang otomatis.
        </p>
        <div class="ch-info-box ch-info-green">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <span>Dokumen yang sudah dikonfirmasi tetap dapat dibuka kembali kapan saja di bagian <strong>Sudah Dibaca</strong>.</span>
        </div>
    </div>

    {{-- Footer Tip --}}
    <div class="ch-footer-tip">
        <div class="ch-tip-icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="ch-tip-title">Badge di Menu Navigasi</div>
            <div class="ch-tip-desc">Angka merah di menu <strong>Compliance Hub</strong> menunjukkan jumlah dokumen wajib baca yang belum dikonfirmasi.</div>
        </div>
    </div>

</div>
