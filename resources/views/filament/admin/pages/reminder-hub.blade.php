<x-filament-panels::page>
<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
.rh-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    opacity: 0;
    animation: fadeInUp 0.45s cubic-bezier(.22,.68,0,1.2) forwards;
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}
.rh-card:hover {
    box-shadow: 0 12px 32px rgba(37,99,235,0.13);
    transform: translateY(-4px);
}
.rh-card:nth-child(1) { animation-delay: 0ms; }
.rh-card:nth-child(2) { animation-delay: 100ms; }
.rh-card:nth-child(3) { animation-delay: 200ms; }
.rh-card:nth-child(4) { animation-delay: 300ms; }
.rh-btn {
    width: 100%;
    padding: 10px 16px;
    background: #2563eb;
    color: #ffffff;
    font-weight: 600;
    font-size: 13px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.rh-btn:hover:not(:disabled) {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
}
.rh-btn:active:not(:disabled) { transform: translateY(0); box-shadow: none; }
.rh-btn:disabled { opacity: 0.65; cursor: not-allowed; }

/* Modal styles (teleported to body) */
.rh-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 9999999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15,23,42,0.5);
    padding: 16px;
}
.rh-modal {
    background: #ffffff;
    border-radius: 18px;
    width: 100%;
    max-width: 460px;
    max-height: calc(100vh - 32px);
    overflow-y: auto;
    box-shadow: 0 24px 60px -10px rgba(15,23,42,0.35);
    font-family: 'Inter', system-ui, sans-serif;
}
.rh-modal-header {
    padding: 18px 22px;
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 18px 18px 0 0;
    position: sticky; top: 0; z-index: 1;
}
.rh-modal-header-left { display: flex; align-items: center; gap: 12px; }
.rh-modal-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(255,255,255,0.18);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.rh-modal-close {
    background: rgba(255,255,255,0.15);
    border: none; color: #ffffff; cursor: pointer;
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; line-height: 1;
    transition: background 0.15s; flex-shrink: 0;
}
.rh-modal-close:hover { background: rgba(255,255,255,0.28); }
.rh-modal-body { padding: 24px 22px; }
.rh-field { margin-bottom: 18px; }
.rh-label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 7px; }
.rh-input, .rh-textarea {
    width: 100%; padding: 10px 14px; font-size: 14px;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    outline: none; color: #1e293b; background: #f8fafc;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box; font-family: inherit;
}
.rh-input:focus, .rh-textarea:focus {
    border-color: #2563eb; background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}
.rh-textarea { resize: vertical; min-height: 80px; }
.rh-modal-footer {
    display: flex; align-items: center; justify-content: flex-end;
    gap: 10px; padding-top: 4px;
}
.rh-modal-btn-cancel {
    padding: 9px 20px; font-size: 13px; font-weight: 600;
    background: #f1f5f9; color: #64748b;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    cursor: pointer; transition: background 0.15s; font-family: inherit;
}
.rh-modal-btn-cancel:hover { background: #e9eef5; }
.rh-modal-btn-submit {
    padding: 9px 22px; font-size: 13px; font-weight: 600;
    background: #2563eb; color: #ffffff;
    border: none; border-radius: 9px; cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
    display: inline-flex; align-items: center; gap: 7px;
    font-family: inherit;
}
.rh-modal-btn-submit:hover {
    background: #1d4ed8;
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
    transform: translateY(-1px);
}
[x-cloak] { display: none !important; }

/* Alpine transition utilities (Tailwind not available in teleported context) */
.rh-enter { transition: opacity 200ms ease-out, transform 200ms ease-out; }
.rh-leave { transition: opacity 150ms ease-in, transform 150ms ease-in; }
.rh-overlay-start { opacity: 0; }
.rh-overlay-end   { opacity: 1; }
.rh-modal-start   { opacity: 0; transform: scale(0.95) translateY(8px); }
.rh-modal-end     { opacity: 1; transform: scale(1) translateY(0); }

/* Responsive: mobile */
@media (max-width: 640px) {
    .rh-modal-overlay { padding: 0; align-items: flex-end; }
    .rh-modal {
        max-width: 100%;
        max-height: 92vh;
        border-radius: 18px 18px 0 0;
    }
    .rh-modal-header {
        border-radius: 18px 18px 0 0;
        padding: 16px 18px;
    }
    .rh-modal-body { padding: 20px 18px; }
    .rh-modal-footer {
        flex-direction: column-reverse;
        gap: 8px;
    }
    .rh-modal-btn-cancel,
    .rh-modal-btn-submit {
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
    }
}
</style>

    <div style="font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1e293b;">

        {{-- Reminder Grid Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">

            {{-- Card 1: Reminder Wajib Baca --}}
            @if (Auth::user()?->can('send_mandatory_read_reminder') || Auth::user()?->hasRole(['super_admin', 'direktur', 'kabid', 'manager']))
            <div class="rh-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Dokumen Wajib Baca</h3>
                            <span style="font-size: 12px; color: #64748b;">Compliance Hub</span>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #475569; line-height: 1.5; margin: 0 0 18px 0;">
                        Kirim notifikasi lonceng & email pengingat kepada pegawai yang belum membaca & mengonfirmasi dokumen wajib.
                    </p>
                </div>
                <button type="button" class="rh-btn" wire:click="sendMandatoryReadReminders" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    <span>Kirim Pengingat Wajib Baca</span>
                </button>
            </div>
            @endif

            {{-- Card 2: Pengingat Pribadi --}}
            @if (Auth::user()?->can('create_own_reminder') || Auth::user()?->can('create_personal_reminder') || Auth::user()?->hasRole(['super_admin', 'direktur', 'kabid', 'manager', 'staff']))
            <div class="rh-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Pengingat Pribadi</h3>
                            <span style="font-size: 12px; color: #64748b;">Catatan & Tugas Anda</span>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #475569; line-height: 1.5; margin: 0 0 18px 0;">
                        Buat pengingat untuk tugas, catatan, atau jadwal pribadi Anda yang akan dikirim langsung ke notifikasi lonceng Anda.
                    </p>
                </div>
                <button type="button" class="rh-btn" wire:click="openPersonalModal" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <span>Buat Pengingat Pribadi</span>
                </button>
            </div>
            @endif

            {{-- Card 3: Reminder Rapat --}}
            @if (Auth::user()?->can('send_meeting_reminder') || Auth::user()?->hasRole(['super_admin', 'direktur', 'kabid', 'manager']))
            <div class="rh-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Jadwal Rapat</h3>
                            <span style="font-size: 12px; color: #64748b;">Notulen & Undangan</span>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #475569; line-height: 1.5; margin: 0 0 18px 0;">
                        Kirim notifikasi pengingat ke seluruh peserta rapat yang memiliki agenda rapat dalam 7 hari ke depan.
                    </p>
                </div>
                <button type="button" class="rh-btn" wire:click="sendUpcomingMeetingReminders" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Ingatkan Peserta Rapat</span>
                </button>
            </div>
            @endif

            {{-- Card 4: Reminder Dokumen Kedaluwarsa --}}
            @if (Auth::user()?->can('send_expiry_reminder') || Auth::user()?->hasRole(['super_admin', 'direktur', 'kabid', 'manager']))
            <div class="rh-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Masa Berlaku Dokumen</h3>
                            <span style="font-size: 12px; color: #64748b;">Kedaluwarsa / Revokasi</span>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #475569; line-height: 1.5; margin: 0 0 18px 0;">
                        Kirim notifikasi pengingat untuk dokumen yang akan habis masa berlakunya dalam 30 hari ke depan.
                    </p>
                </div>
                <button type="button" class="rh-btn" wire:click="sendExpiryReminders" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Kirim Pengingat Kedaluwarsa</span>
                </button>
            </div>
            @endif

        </div>
    </div>

    {{-- Personal Reminder Modal --}}
    <div x-data="{ show: @entangle('showPersonalModal').live }">
        <template x-teleport="body">
            {{-- Overlay --}}
            <div class="rh-modal-overlay"
                 x-show="show"
                 x-cloak
                 x-transition:enter="rh-enter"
                 x-transition:enter-start="rh-overlay-start"
                 x-transition:enter-end="rh-overlay-end"
                 x-transition:leave="rh-leave"
                 x-transition:leave-start="rh-overlay-end"
                 x-transition:leave-end="rh-overlay-start"
                 @click.self="show = false">

                {{-- Modal --}}
                <div class="rh-modal"
                     x-show="show"
                     x-transition:enter="rh-enter"
                     x-transition:enter-start="rh-modal-start"
                     x-transition:enter-end="rh-modal-end"
                     x-transition:leave="rh-leave"
                     x-transition:leave-start="rh-modal-end"
                     x-transition:leave-end="rh-modal-start"
                     @click.stop>

                    <div class="rh-modal-header">
                        <div class="rh-modal-header-left">
                            <div class="rh-modal-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size: 15px; font-weight: 700; line-height: 1.2;">Buat Pengingat Pribadi</div>
                                <div style="font-size: 11.5px; opacity: 0.8; margin-top: 2px;">Notifikasi dikirim langsung ke lonceng Anda</div>
                            </div>
                        </div>
                        <button type="button" class="rh-modal-close" @click="show = false">&times;</button>
                    </div>

                    <div class="rh-modal-body">
                        <div class="rh-field">
                            <label class="rh-label">
                                Judul / Tugas Pengingat
                                <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text"
                                   wire:model="personalTitle"
                                   class="rh-input"
                                   placeholder="Contoh: Review SOP Keuangan Bab 2" />
                        </div>

                        <div class="rh-field">
                            <label class="rh-label">Catatan / Rincian <span style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                            <textarea wire:model="personalNotes"
                                      rows="4"
                                      class="rh-textarea"
                                      placeholder="Tambahkan rincian atau deadline tugas pribadi ini..."></textarea>
                        </div>

                        <div class="rh-modal-footer">
                            <button type="button" class="rh-modal-btn-cancel" @click="show = false">Batal</button>
                            <button type="button" class="rh-modal-btn-submit" wire:click="sendPersonalReminder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                Kirim ke Notifikasi Saya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

</x-filament-panels::page>
