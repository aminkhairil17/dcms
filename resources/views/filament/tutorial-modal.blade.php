<style>
    .tutorial-step-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
    }
    .tutorial-image-container { 
        overflow: hidden; 
        border-radius: 0.75rem; 
        border: 1px solid var(--color-gray-200, #e5e7eb); 
        background: var(--color-gray-50, #f9fafb);
    }
    .tutorial-image-container img {
        width: 100%;
        height: auto;
        display: block;
    }        
</style>

@if(isset($image))
<div class="tutorial-image-container">
    <img src="/images/{{ $image }}" alt="Panduan Tutorial">
</div>
@else

<div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; padding: 0.5rem 0 1rem;">

    {{-- Step 1 --}}
    <div
        style="position:relative; padding:1.25rem; border-radius:1rem; background:var(--color-gray-50,#f9fafb); border:1px solid var(--color-gray-200,#e5e7eb);">
        <div
            style="position:absolute; top:-12px; left:-12px; width:28px; height:28px; border-radius:50%; background:#3b82f6; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; box-shadow:0 2px 6px rgba(0,0,0,.2);">
            1</div>
        <div
            style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:0.6rem; margin-top:0.25rem;">
            <div style="padding:10px; background:#eff6ff; border-radius:12px; color:#3b82f6; display:inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="tutorial-step-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <strong style="font-size:0.9rem; color:#1f2937;">Buat Rapat</strong>
            <p style="font-size:0.78rem; color:#6b7280; margin:0; line-height:1.5;">Klik <b>Tambah Rapat</b>. Isi detail
                dasar seperti judul, waktu, lokasi, dan agenda rapat.</p>
        </div>
    </div>

    {{-- Step 2 --}}
    <div
        style="position:relative; padding:1.25rem; border-radius:1rem; background:var(--color-gray-50,#f9fafb); border:1px solid var(--color-gray-200,#e5e7eb);">
        <div
            style="position:absolute; top:-12px; left:-12px; width:28px; height:28px; border-radius:50%; background:#3b82f6; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; box-shadow:0 2px 6px rgba(0,0,0,.2);">
            2</div>
        <div
            style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:0.6rem; margin-top:0.25rem;">
            <div style="padding:10px; background:#eff6ff; border-radius:12px; color:#3b82f6; display:inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="tutorial-step-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <strong style="font-size:0.9rem; color:#1f2937;">Masukkan Peserta</strong>
            <p style="font-size:0.78rem; color:#6b7280; margin:0; line-height:1.5;">Pilih dari daftar karyawan atau
                partisipan yang diwajibkan untuk hadir pada rapat.</p>
        </div>
    </div>

    {{-- Step 3 --}}
    <div
        style="position:relative; padding:1.25rem; border-radius:1rem; background:var(--color-gray-50,#f9fafb); border:1px solid var(--color-gray-200,#e5e7eb);">
        <div
            style="position:absolute; top:-12px; left:-12px; width:28px; height:28px; border-radius:50%; background:#3b82f6; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; box-shadow:0 2px 6px rgba(0,0,0,.2);">
            3</div>
        <div
            style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:0.6rem; margin-top:0.25rem;">
            <div style="padding:10px; background:#eff6ff; border-radius:12px; color:#3b82f6; display:inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="tutorial-step-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
            </div>
            <strong style="font-size:0.9rem; color:#1f2937;">Isi Notulensi</strong>
            <p style="font-size:0.78rem; color:#6b7280; margin:0; line-height:1.5;">Pilih aksi <b>Edit</b> pada tabel.
                Catat notulensi serta lampirkan foto dokumentasi rapat.</p>
        </div>
    </div>

    {{-- Step 4 --}}
    <div
        style="position:relative; padding:1.25rem; border-radius:1rem; background:var(--color-gray-50,#f9fafb); border:1px solid var(--color-gray-200,#e5e7eb);">
        <div
            style="position:absolute; top:-12px; left:-12px; width:28px; height:28px; border-radius:50%; background:#3b82f6; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; box-shadow:0 2px 6px rgba(0,0,0,.2);">
            4</div>
        <div
            style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:0.6rem; margin-top:0.25rem;">
            <div style="padding:10px; background:#eff6ff; border-radius:12px; color:#3b82f6; display:inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="tutorial-step-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <strong style="font-size:0.9rem; color:#1f2937;">Unduh PDF</strong>
            <p style="font-size:0.78rem; color:#6b7280; margin:0; line-height:1.5;">Ganti status menjadi <b>Selesai</b>.
                Buka lewat tombol <b>Lihat</b> untuk mengunduh rekap PDF notulensi.</p>
        </div>
    </div>

</div>
@endif

{{-- Tips Shortcut Kalender --}}
<div style="display:flex; align-items:center; gap:0.85rem; background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1px solid #bfdbfe; border-radius:0.875rem; padding:0.85rem 1rem; margin-top:0.75rem;">
    <div style="width:2.2rem; height:2.2rem; min-width:2.2rem; display:flex; align-items:center; justify-content:center; border-radius:50%; background:#2563eb; color:#fff; flex-shrink:0;">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
    </div>
    <div>
        <div style="font-weight:700; font-size:0.8rem; color:#1e3a8a; margin-bottom:0.15rem;">
            Shortcut: Klik Tanggal di Kalender Dashboard
        </div>
        <div style="font-size:0.72rem; color:#1d4ed8; line-height:1.5;">
            Di <strong>Dashboard Utama</strong>, klik langsung pada tanggal di widget kalender untuk langsung membuka formulir <strong>Tambah Rapat</strong> dengan tanggal yang sudah terisi otomatis.
        </div>
    </div>
</div>