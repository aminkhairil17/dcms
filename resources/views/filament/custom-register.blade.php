<div class="fixed inset-0 min-h-screen flex items-center justify-center font-sans overflow-y-auto p-4 md:p-8"
    style="background: #f1f3f7;" x-data="{ showTutorial: false }">

    {{-- Container Utama --}}
    <div class="relative w-full max-w-4xl flex flex-col items-center animate-fade-in mx-auto z-10 py-4">

        {{-- Section Branding --}}
        <div class="w-full text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Syifa Global Group Logo"
                class="h-14 w-auto mx-auto mb-3 object-contain">
            
            <h1 class="text-2xl font-bold text-[#1e293b] mb-1 tracking-tight">DCMS</h1>
            <p class="text-[13px] text-[#64748b] font-medium uppercase tracking-wider">Buat Akun Baru</p>
        </div>

        {{-- Card Utama --}}
        <div class="w-full bg-white rounded-xl shadow-[0_15px_60px_-10px_rgba(0,0,0,0.08)] border border-[#e2e8f0] p-6 md:p-10 mb-6 z-10 relative">

            <form wire:submit.prevent="register">
                {{-- Area Form Filament (2 Kolom di Desktop via CSS di bawah) --}}
                <div class="fi-form-override">
                    {{ $this->form }}
                </div>

                {{-- Tombol & Navigasi --}}
                <div class="mt-10 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 pt-8">
                    <div class="w-full md:w-1/2 order-2 md:order-1 flex flex-col items-center md:items-start gap-3">
                        <p class="text-[13px] text-[#64748b]">
                            Sudah punya akun?
                            <a href="{{ filament()->getLoginUrl() }}" class="text-[#2563eb] font-bold hover:underline">Masuk</a>
                        </p>
                        
                        {{-- Tombol Panduan (Heroicon Info) --}}
                        <button @click="showTutorial = true" 
                            type="button"
                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group text-[13px] font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:scale-110 transition-transform">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            Panduan Pendaftaran
                        </button>
                    </div>

                    <div class="w-full md:w-1/2 order-1 md:order-2">
                        <button type="submit"
                            class="w-full py-4 bg-[#2563eb] text-white rounded-lg font-bold text-base transition-colors duration-200 hover:bg-[#1d4ed8] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-200 tracking-wide uppercase">
                            Daftar Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-full text-center text-[12px] text-[#94a3b8] font-medium tracking-wide">
            &copy; {{ date('Y') }} Syifa Global Group.
        </div>
    </div>

    {{-- Modal Tutorial --}}
    <template x-teleport="body">
        <div x-show="showTutorial" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
            @keydown.escape.window="showTutorial = false"
            style="display: none;">
            
            <div @click.away="showTutorial = false" 
                class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        Panduan Halaman Registrasi
                    </h3>
                    <button @click="showTutorial = false" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 overflow-y-auto max-h-[80vh]">
                    <img src="{{ asset('images/daftar.jpg') }}" alt="Panduan Registrasi" class="w-full h-auto rounded-xl shadow-sm border border-gray-200">
                </div>
                <div class="px-6 py-4 border-t border-gray-100 text-right">
                    <button @click="showTutorial = false" class="px-6 py-2 bg-gray-800 text-white rounded-lg font-bold hover:bg-gray-900 transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </template>

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

        /* Mengatur Form agar menjadi 2 Kolom pada desktop */
        .fi-form-override .fi-grid {
            display: grid !important;
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }

        @media (min-width: 768px) {
            .fi-form-override .fi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
            /* Jika ada field yang harus full width (misal alamat), Filament biasanya menangani via class, 
               tapi style ini memastikan grid dasar bekerja */
        }

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