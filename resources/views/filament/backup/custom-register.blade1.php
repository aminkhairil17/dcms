<div class="fixed inset-0 min-h-screen flex items-center justify-center font-sans overflow-y-auto p-4 md:p-8"
    style="background: #f1f3f7;">

    {{-- Container Utama: Diperlebar menjadi max-w-4xl untuk menampung 2 kolom --}}
    <div class="relative w-full max-w-4xl flex flex-col items-center animate-fade-in mx-auto z-10 py-4">

        {{-- Section Branding: Dibuat lebih ringkas --}}
        <div class="w-full text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Syifa Global Group Logo"
                class="h-14 w-auto mx-auto mb-3 object-contain">
            <h1 class="text-2xl font-bold text-[#1e293b] mb-1 tracking-tight">DCMS</h1>
            <p class="text-[13px] text-[#64748b] font-medium">Buat Akun Baru</p>
        </div>

        {{-- Card Utama --}}
        <div class="w-full bg-white rounded-xl shadow-[0_15px_60px_-10px_rgba(0,0,0,0.08)] border border-[#e2e8f0] p-6 md:p-10 mb-6 z-10 relative">

            <form wire:submit.prevent="register">
                {{-- Area Form Filament: Dipaksa menjadi Grid 2 Kolom pada layar desktop --}}
                <div class="fi-form-override">
                    {{ $this->form }}
                </div>

                {{-- Tombol & Link: Ditempatkan di bawah grid --}}
                <div class="mt-8 flex flex-col md:flex-row items-center gap-4">
                    <button type="submit"
                        class="w-full md:w-1/2 py-4 bg-[#2563eb] text-white rounded-lg font-bold text-base transition-colors duration-200 hover:bg-[#1d4ed8] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-200 tracking-wide uppercase">
                        Daftar Sekarang
                    </button>
                    
                    <div class="w-full md:w-1/2 text-center md:text-left md:pl-4">
                        <p class="text-[13px] text-[#64748b]">
                            Sudah punya akun?
                            <a href="{{ filament()->getLoginUrl() }}" class="text-[#2563eb] font-bold hover:underline">Masuk</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-full text-center text-[12px] text-[#94a3b8] font-medium tracking-wide">
            &copy; {{ date('Y') }} Syifa Global Group.
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    borderRadius: { 'xl': '18px' },
                }
            }
        }
    </script>

    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(15px) } to { opacity: 1; transform: translateY(0) } }
        .animate-fade-in { animation: fade-in 0.7s ease-out forwards; }

        /* Mengatur Form agar menjadi 2 Kolom */
        .fi-form-override .fi-grid {
            display: grid !important;
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }

        @media (min-width: 768px) {
            .fi-form-override .fi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        /* Menghilangkan border internal Filament */
        .fi-form-override .fi-fo-field-wrp,
        .fi-form-override .fi-section-content {
            border: none !important;
        }

        .fi-form-override label {
            color: #475569 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 0.3rem !important;
            display: block;
        }

        .fi-form-override .fi-input-wrp {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 2px 4px !important;
        }

        .fi-form-override .fi-input-wrp:focus-within {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }

        .fi-form-override .fi-input {
            color: #1e293b !important;
            font-size: 0.95rem !important;
            padding: 0.75rem !important;
        }

        .fi-input-wrp button { color: #64748b !important; }
        
        body { background-color: #f1f3f7 !important; margin: 0; }
    </style>
</div>