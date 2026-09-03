<div 
    class="inline-flex items-center gap-x-2 px-2.5 py-1 bg-slate-800/90 border border-slate-700/80 rounded-lg shadow-sm"
    x-data="{ isSubscribed: @entangle('isSubscribed') }"
>
    <span class="text-xs font-bold text-slate-200">Push</span>

    <span 
        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase transition"
        :class="isSubscribed ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-700 text-slate-300'"
    >
        <span class="w-1.5 h-1.5 rounded-full" :class="isSubscribed ? 'bg-emerald-400 animate-pulse' : 'bg-slate-400'"></span>
        <span x-text="isSubscribed ? 'ON' : 'OFF'"></span>
    </span>

    @if($isSubscribed)
        <button 
            type="button"
            wire:click="sendTestNotification"
            title="Tes Push Notifikasi"
            class="p-1 text-sky-400 hover:text-white hover:bg-sky-500/20 rounded transition"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </button>
    @endif

    {{-- Toggle Switch --}}
    <button 
        type="button"
        @click="if (isSubscribed) { window.unsubscribeWebPush(); } else { window.requestWebPushPermission(); }"
        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
        :class="isSubscribed ? 'bg-emerald-500' : 'bg-slate-500'"
        role="switch"
        :aria-checked="isSubscribed"
        title="Klik untuk ON/OFF Notifikasi Web Push Browser"
    >
        <span 
            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
            :class="isSubscribed ? 'translate-x-4' : 'translate-x-0'"
        ></span>
    </button>
</div>
