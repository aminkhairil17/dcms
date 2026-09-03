<style>
    .cdm-guide-wrap {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.85rem;
        color: #374151;
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
        padding: 0.2rem 0 0.4rem;
    }

    /* Banner Header */
    .cdm-banner {
        position: relative; overflow: hidden; border-radius: 0.875rem;
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%);
        padding: 0.95rem 1.15rem; color: #fff;
    }
    .cdm-banner-inner { display: flex; align-items: center; gap: 0.8rem; position: relative; z-index: 1; }
    .cdm-banner-icon {
        width: 2.3rem; height: 2.3rem; min-width: 2.3rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.65rem; background: rgba(255,255,255,0.18); flex-shrink: 0;
    }
    .cdm-banner-glow {
        position: absolute; right: -1.5rem; bottom: -1.5rem;
        width: 6.5rem; height: 6.5rem; border-radius: 50%;
        background: rgba(255,255,255,0.08); filter: blur(20px);
    }

    /* Grid 4 Langkah (2x2) */
    .cdm-steps-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
    }

    /* Step Card */
    .cdm-step-card {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        background: #ffffff;
        padding: 0.85rem 0.95rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        transition: all 0.2s ease;
    }
    .cdm-step-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 4px 12px rgba(37,99,235,0.06);
    }

    .cdm-step-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.15rem;
    }
    .cdm-step-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 0.4rem;
        padding: 0.15rem 0.5rem;
        font-weight: 700;
        font-size: 0.72rem;
    }
    .cdm-badge-1 { background: #dbeafe; color: #1d4ed8; }
    .cdm-badge-2 { background: #e0e7ff; color: #4338ca; }
    .cdm-badge-3 { background: #fef3c7; color: #d97706; }
    .cdm-badge-4 { background: #dcfce7; color: #15803d; }

    .cdm-step-icon {
        color: #9ca3af;
    }

    .cdm-step-title {
        font-weight: 700;
        font-size: 0.82rem;
        color: #111827;
    }

    .cdm-step-desc {
        font-size: 0.72rem;
        color: #4b5563;
        line-height: 1.45;
        margin: 0;
    }

    .cdm-step-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
        margin-top: 0.2rem;
    }
    .cdm-tag {
        background: #f3f4f6;
        color: #374151;
        border-radius: 0.3rem;
        padding: 0.1rem 0.38rem;
        font-size: 0.67rem;
        font-weight: 600;
    }
    .cdm-tag-highlight {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* Bottom Info Boxes */
    .cdm-bottom-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .cdm-info-box {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        border-radius: 0.75rem;
        padding: 0.65rem 0.85rem;
        font-size: 0.71rem;
        line-height: 1.4;
    }
    .cdm-info-box-blue {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }
    .cdm-info-box-amber {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }
    .cdm-info-icon {
        width: 1.8rem;
        height: 1.8rem;
        min-width: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .cdm-info-icon-blue { background: #1d4ed8; color: #fff; }
    .cdm-info-icon-amber { background: #d97706; color: #fff; }

    .cdm-info-title { font-weight: 700; font-size: 0.74rem; margin-bottom: 0.1rem; }
    .cdm-info-desc { margin: 0; }

    @media (max-width: 640px) {
        .cdm-steps-grid { grid-template-columns: 1fr; }
        .cdm-bottom-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="cdm-guide-wrap">

    {{-- Banner Header --}}
    <div class="cdm-banner">
        <div class="cdm-banner-inner">
            <div class="cdm-banner-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div>
                <div style="font-size:0.92rem; font-weight:800; margin-bottom:0.1rem;">Panduan Penambahan Dokumen Baru</div>
                <div style="font-size:0.72rem; color:rgba(255,255,255,0.85); line-height:1.4;">4 Langkah praktis untuk mengunggah atau membuat dokumen SOP di sistem DCMS.</div>
            </div>
        </div>
        <div class="cdm-banner-glow"></div>
    </div>

    {{-- Grid 4 Langkah Praktis --}}
    <div class="cdm-steps-grid">

        {{-- Langkah 1 --}}
        <div class="cdm-step-card">
            <div class="cdm-step-top">
                <span class="cdm-step-badge cdm-badge-1">Langkah 1</span>
                <div class="cdm-step-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                </div>
            </div>
            <div class="cdm-step-title">Pilih Tipe Dokumen</div>
            <p class="cdm-step-desc">Pilih <strong>Unggah Berkas</strong> (PDF/Word/Excel/Gambar) atau <strong>Formulir</strong> untuk mengetik teks dokumen secara langsung.</p>
            <div class="cdm-step-tags">
                <span class="cdm-tag">PDF / Word</span>
                <span class="cdm-tag">Max 10 MB</span>
                <span class="cdm-tag cdm-tag-highlight">Hybrid</span>
            </div>
        </div>

        {{-- Langkah 2 --}}
        <div class="cdm-step-card">
            <div class="cdm-step-top">
                <span class="cdm-step-badge cdm-badge-2">Langkah 2</span>
                <div class="cdm-step-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </div>
            </div>
            <div class="cdm-step-title">Identitas & Organisasi</div>
            <p class="cdm-step-desc">Isi <strong>Judul Resmi</strong>, versi dokumen (default <strong>1.0</strong>), serta pilih <strong>Departemen & Kategori</strong>. Nomor Kode dibuat otomatis.</p>
            <div class="cdm-step-tags">
                <span class="cdm-tag">Versi 1.0</span>
                <span class="cdm-tag cdm-tag-highlight">Kode Otomatis</span>
            </div>
        </div>

        {{-- Langkah 3 --}}
        <div class="cdm-step-card">
            <div class="cdm-step-top">
                <span class="cdm-step-badge cdm-badge-3">Langkah 3</span>
                <div class="cdm-step-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
            </div>
            <div class="cdm-step-title">Status & Akses Review</div>
            <p class="cdm-step-desc">Pilih <strong>Draft</strong> jika ingin menyimpan sementara, atau pilih <strong>Pending</strong> agar diajukan ke peninjauan Kabid & Direktur.</p>
            <div class="cdm-step-tags">
                <span class="cdm-tag">Draft</span>
                <span class="cdm-tag cdm-tag-highlight">Pending Review</span>
            </div>
        </div>

        {{-- Langkah 4 --}}
        <div class="cdm-step-card">
            <div class="cdm-step-top">
                <span class="cdm-step-badge cdm-badge-4">Langkah 4</span>
                <div class="cdm-step-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z"/></svg>
                </div>
            </div>
            <div class="cdm-step-title">Pantau Kesiapan & Simpan</div>
            <p class="cdm-step-desc">Perhatikan indikator <strong>Kesiapan Dokumen</strong> di kanan atas form. Jika persentase sudah lengkap, klik tombol <strong>Buat</strong>.</p>
            <div class="cdm-step-tags">
                <span class="cdm-tag cdm-tag-highlight">Kesiapan 100%</span>
                <span class="cdm-tag">Klik Buat</span>
            </div>
        </div>

    </div>

    {{-- Bottom Info Highlight Boxes --}}
    <div class="cdm-bottom-grid">

        <div class="cdm-info-box cdm-info-box-blue">
            <div class="cdm-info-icon cdm-info-icon-blue">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <div class="cdm-info-title">Pencegahan File Duplikat</div>
                <p class="cdm-info-desc">Sistem otomatis mendeteksi jika berkas serupa pernah diunggah sebelumnya untuk mencegah adanya penumpukan dokumen ganda.</p>
            </div>
        </div>

        <div class="cdm-info-box cdm-info-box-amber">
            <div class="cdm-info-icon cdm-info-icon-amber">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div>
                <div class="cdm-info-title">Pengingat Kedaluwarsa & Compliance</div>
                <p class="cdm-info-desc">Pengingat kedaluwarsa dikirim otomatis <strong>30 hari</strong> sebelumnya. Aktifkan <em>Wajib Dibaca</em> agar tampil di Compliance Hub.</p>
            </div>
        </div>

    </div>

</div>
