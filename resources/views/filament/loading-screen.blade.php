{{--
    Loading Screen — hanya muncul SEKALI per sesi browser (saat pertama kali website dibuka).
    Tidak muncul lagi saat navigasi antar halaman di dalam aplikasi.

    Cara kerja:
    - Overlay di-render ke DOM dengan display:flex (terlihat) secara default.
    - Script sinkron (parser-blocking) langsung dieksekusi SETELAH div di-parse
      tapi SEBELUM browser sempat me-paint, sehingga tidak ada kilatan (flash).
    - Jika sessionStorage sudah bertanda 'dcms_splash_seen', overlay langsung
      disembunyikan via inline style sebelum browser melakukan rendering apapun.
    - Jika belum, overlay ditampilkan dengan animasi lalu ditandai setelah halaman load.
--}}

<div id="dcms-loading-screen" class="dcms-loader-overlay">
    <div class="dcms-loader-bg-glow"></div>
    <div class="dcms-loader-content">
        <h1 class="dcms-logo-title">DCMS</h1>
        <div class="dcms-logo-subtitle">DOCUMENT CONTROL MANAGEMENT SYSTEM</div>
    </div>
</div>

{{-- Script ini berjalan SINKRON (parser-blocking) — browser tidak akan me-paint
     overlay di atas sebelum script ini selesai dieksekusi. Ini mencegah flash. --}}
<script>
    (function () {
        var seen = false;
        try { seen = !!sessionStorage.getItem('dcms_splash_seen'); } catch (e) { seen = true; }

        if (seen) {
            // Sembunyikan sebelum browser sempat paint — tidak ada kilatan
            var el = document.getElementById('dcms-loading-screen');
            if (el) el.style.cssText = 'display:none!important';
        }
    })();
</script>

<style>
    .dcms-loader-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0b192e 0%, #06101e 100%);
        overflow: hidden;
    }

    .dcms-loader-bg-glow {
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.22) 0%, rgba(11, 25, 46, 0) 70%);
        animation: dcmsPulseGlow 3s ease-in-out infinite alternate;
        pointer-events: none;
    }

    .dcms-loader-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
        animation: dcmsEntrance 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .dcms-logo-title {
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        font-size: clamp(3rem, 7vw, 5rem);
        font-weight: 900;
        letter-spacing: 0.2em;
        color: #ffffff;
        margin: 0;
        line-height: 1;
        text-indent: 0.2em;
        text-shadow: 0 0 35px rgba(59, 130, 246, 0.6), 0 0 70px rgba(59, 130, 246, 0.25);
    }

    .dcms-logo-subtitle {
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        font-size: clamp(0.6rem, 1.4vw, 0.75rem);
        font-weight: 600;
        letter-spacing: 0.4em;
        color: #94a3b8;
        margin-top: 1.25rem;
        text-transform: uppercase;
        text-indent: 0.4em;
        opacity: 0.85;
    }

    /* State saat overlay sedang keluar */
    .dcms-loader-overlay.dcms-exiting {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dcms-loader-overlay.dcms-exiting .dcms-loader-content {
        transform: scale(1.06) translateY(-8px);
        filter: blur(6px);
        opacity: 0;
        transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                    filter 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes dcmsEntrance {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(18px);
            filter: blur(10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
            filter: blur(0);
        }
    }

    @keyframes dcmsPulseGlow {
        from { transform: scale(0.9); opacity: 0.5; }
        to   { transform: scale(1.2); opacity: 0.95; }
    }
</style>

<script>
    (function () {
        var MIN_DISPLAY_MS = 600;   // sedikit lebih pendek — livewire:initialized lebih cepat dari window.load
        var FADE_DURATION_MS = 450;
        var FALLBACK_MS = 3000;     // timeout aman jika semua event gagal

        // Cek flag — jika sudah pernah dibuka sebelumnya, keluar (sudah disembunyikan
        // oleh script sinkron di atas, tidak perlu melakukan apapun lagi)
        var alreadySeen = false;
        try { alreadySeen = !!sessionStorage.getItem('dcms_splash_seen'); } catch (e) { alreadySeen = true; }
        if (alreadySeen) return;

        var loader = document.getElementById('dcms-loading-screen');
        if (!loader) return;

        var dismissed = false;
        var startTime = Date.now();

        function dismiss() {
            if (dismissed) return;
            dismissed = true;

            // Tandai sebagai sudah dilihat — navigasi berikutnya tidak akan tampilkan lagi
            try { sessionStorage.setItem('dcms_splash_seen', '1'); } catch (e) {}

            var elapsed = Date.now() - startTime;
            var wait = Math.max(0, MIN_DISPLAY_MS - elapsed);

            setTimeout(function () {
                loader.classList.add('dcms-exiting');
                setTimeout(function () {
                    // Hapus dari DOM agar tidak memakan memori dan tidak bisa di-trigger ulang
                    if (loader.parentNode) loader.parentNode.removeChild(loader);
                }, FADE_DURATION_MS);
            }, wait);
        }

        // ── Trigger 1 (Utama): Livewire selesai me-render semua komponen di halaman ──
        // 'livewire:initialized' fires setelah semua Livewire component di-hydrate & di-render.
        // Ini lebih akurat dari window.load karena tidak perlu menunggu download gambar dll.
        document.addEventListener('livewire:initialized', dismiss, { once: true });

        // ── Trigger 2 (Fallback A): window.load — semua aset selesai diunduh ──
        // Diperlukan jika Livewire belum sepenuhnya di-setup saat event daftar,
        // atau jika Livewire tidak digunakan di halaman tertentu.
        window.addEventListener('load', dismiss, { once: true });

        // ── Trigger 3 (Fallback B): Jika halaman sudah selesai dimuat sebelum script ini berjalan ──
        if (document.readyState === 'complete') {
            dismiss();
        }

        // ── Trigger 4 (Fallback C): Timeout mutlak — jaminan terakhir ──
        setTimeout(dismiss, FALLBACK_MS);
    })();
</script>
