<div class="fixed inset-0 min-h-screen flex items-center justify-center font-sans overflow-y-auto p-4 md:p-10" style="background: #f1f3f7;">

    {{-- Container Utama --}}
    <div class="relative w-full max-w-[440px] flex flex-col items-center animate-fade-in mx-auto z-10">

        {{-- Section Branding --}}
        <div class="w-full text-center mb-10">
            <img src="{{ asset('images/logo.png') }}" alt="Syifa Global Group Logo" class="h-20 w-auto mx-auto mb-6 object-contain">
            <h1 class="text-2xl font-bold text-[#1e293b] mb-3 tracking-tight">DCMS</h1>
            <p class="text-[13px] text-[#64748b] leading-relaxed max-w-sm mx-auto font-medium">
                Manajemen Rapat dan Dokumen
            </p>
        </div>

        {{-- Card Utama --}}
        <div class="w-full bg-white rounded-xl shadow-[0_15px_60px_-10px_rgba(0,0,0,0.08)] border border-[#e2e8f0] p-10 md:p-12 mb-8 z-10 relative">
            
            <form wire:submit.prevent="authenticate" class="space-y-5">
                
                <div class="fi-form-override space-y-5">
                    {{ $this->form }}
                </div>

                <div style="padding-top: 1rem;">
                    <button type="submit" class="w-full py-4 bg-[#2563eb] text-white rounded-lg font-bold text-base transition-colors duration-200 hover:bg-[#1d4ed8] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-200 tracking-wide uppercase">
                        Masuk
                    </button>
                </div>
            </form>
            
             <div class="mt-6 text-center">
                <p class="text-[13px] text-[#64748b]">
                    Belum punya akun? 
                    <a href="{{ filament()->getRegistrationUrl() }}" class="text-[#2563eb] font-bold hover:underline">Buat Akun</a>
                </p>
            </div>           
        </div>

        <div class="w-full text-center text-[12px] text-[#94a3b8] font-medium tracking-wide">
            &copy; 2026 Syifa Global Group.
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
        @keyframes fade-in { from{opacity:0;transform:translateY(15px)}to{opacity:1;transform:translateY(0)} }
        .animate-fade-in { animation: fade-in 0.7s ease-out forwards; }

        .fi-form-override .fi-fo-field-wrp,
        .fi-form-override .fi-grid,
        .fi-form-override .fi-section-content {
            display: flex !important; flex-direction: column !important; width: 100% !important; gap: 0.25rem !important; border: none !important;
        }

        .fi-form-override label {
            color: #475569 !important; font-size: 0.75rem !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; margin-bottom: 0.2rem !important;
        }

        .fi-form-override .fi-input-wrp {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 2px 4px !important;
        }

        .fi-form-override .fi-input {
            color: #1e293b !important; font-size: 0.95rem !important; padding: 0.75rem !important;
        }

        /* PERBAIKAN TOTAL CHECKBOX */
        .fi-form-override .fi-fo-checkbox-stack {
            display: block !important;
        }

        /* Container Checkbox + Label */
        .fi-form-override div:has(> .fi-checkbox), 
        .fi-form-override .fi-checkbox-outer {
            display: flex !important; 
            flex-direction: row !important; 
            align-items: center !important; 
            gap: 0.75rem !important; 
            margin-top: 0.75rem !important;
            cursor: pointer;
        }

        /* Memaksa Checkbox Muncul dengan Border */
        .fi-form-override input[type="checkbox"],
        .fi-form-override .fi-checkbox {
            -webkit-appearance: none !important;
            appearance: none !important;
            width: 1.2rem !important;
            height: 1.2rem !important;
            background-color: #ffffff !important;
            border: 2px solid #cbd5e1 !important;
            border-radius: 4px !important;
            position: relative !important;
            cursor: pointer !important;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        /* Efek Centang Saat Checkbox Aktif */
        .fi-form-override input[type="checkbox"]:checked,
        .fi-form-override .fi-checkbox:checked {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }

        .fi-form-override input[type="checkbox"]:checked::after {
            content: '✓' !important;
            position: absolute !important;
            color: white !important;
            font-size: 0.8rem !important;
            font-weight: bold !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
        }

        .fi-form-override span, 
        .fi-form-override label[for*="remember"] {
            color: #64748b !important; 
            font-size: 0.875rem !important; 
            text-transform: none !important; 
            letter-spacing: normal !important; 
            font-weight: 500 !important;
            margin-bottom: 0 !important;
        }

        .fi-input-wrp button[title*="password"] { color: #64748b !important; }

        body { background-color:#f1f3f7 !important; margin: 0; padding: 0; }
    </style>
</div>