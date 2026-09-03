@php
use Filament\Support\Enums\Alignment;
use Filament\Support\View\Components\BadgeComponent;
use Illuminate\View\ComponentAttributeBag;

$notifications = $this->getNotifications();
$unreadNotificationsCount = $this->getUnreadNotificationsCount();
$hasNotifications = $notifications->count();
$isPaginated = $notifications instanceof \Illuminate\Contracts\Pagination\Paginator && $notifications->hasPages();
$pollingInterval = $this->getPollingInterval();
@endphp

<div class="fi-no-database">
    <x-filament::modal
        :alignment="$hasNotifications ? null : Alignment::Center"
        close-button
        :description="$hasNotifications ? null : __('filament-notifications::database.modal.empty.description')"
        :heading="$hasNotifications ? null : __('filament-notifications::database.modal.empty.heading')"
        :icon="$hasNotifications ? null : \Filament\Support\Icons\Heroicon::OutlinedBellSlash"
        :icon-alias="
            $hasNotifications
            ? null
            : \Filament\Notifications\View\NotificationsIconAlias::DATABASE_MODAL_EMPTY_STATE
        "
        :icon-color="$hasNotifications ? null : 'gray'"
        id="database-notifications"
        slide-over
        :sticky-header="$hasNotifications"
        teleport="body"
        width="md"
        class="fi-no-database"
        :attributes="
            new \Illuminate\View\ComponentAttributeBag([
                'wire:poll.' . $pollingInterval => $pollingInterval ? '' : false,
            ])
        ">
        @if ($trigger = $this->getTrigger())
        <x-slot name="trigger">
            {{ $trigger->with(['unreadNotificationsCount' => $unreadNotificationsCount]) }}
        </x-slot>
        @endif

        <x-slot name="header">
            <div class="flex items-center justify-between w-full pr-8">
                {{-- Kiri: Judul + badge + Toggle Switch Web Push --}}
                <div class="flex items-center gap-x-3">
                    <h2 class="fi-modal-heading">
                        {{ __('filament-notifications::database.modal.heading') }}

                        @if ($unreadNotificationsCount)
                        <span
                            {{
                                    (new ComponentAttributeBag)->color(BadgeComponent::class, 'primary')->class([
                                        'fi-badge fi-size-xs',
                                    ])
                                }}>
                            {{ $unreadNotificationsCount }}
                        </span>
                        @endif
                    </h2>

                    {{-- Toggle Switch ON/OFF Web Push — Alpine.js, tidak perlu Livewire --}}
                    <div
                        x-data="{
                            on: false,
                            init() {
                                if (!('Notification' in window)) return;
                                if (Notification.permission === 'granted') {
                                    navigator.serviceWorker.ready.then(reg => {
                                        reg.pushManager.getSubscription().then(sub => {
                                            this.on = !!sub;
                                        });
                                    });
                                }
                            },
                            toggle() {
                                if (this.on) {
                                    this.doUnsubscribe();
                                } else {
                                    this.doSubscribe();
                                }
                            },
                            async doSubscribe() {
                                if (!('Notification' in window)) { alert('Browser tidak mendukung notifikasi.'); return; }
                                if (Notification.permission === 'denied') {
                                    alert('Izin notifikasi diblokir. Klik ikon gembok di URL browser lalu ubah Notifikasi menjadi Izinkan, kemudian refresh halaman.');
                                    return;
                                }
                                const permission = await Notification.requestPermission();
                                if (permission !== 'granted') { return; }
                                try {
                                    const keyRes = await fetch('/api/push-subscriptions/vapid-key');
                                    const { publicKey } = await keyRes.json();
                                    const reg = await navigator.serviceWorker.ready;
                                    const sub = await reg.pushManager.subscribe({
                                        userVisibleOnly: true,
                                        applicationServerKey: this.urlBase64ToUint8Array(publicKey)
                                    });
                                    const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                                    await fetch('/api/push-subscriptions', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                        body: JSON.stringify(sub)
                                    });
                                    this.on = true;
                                } catch(e) {
                                    alert('Gagal mengaktifkan notifikasi: ' + e.message);
                                }
                            },
                            async doUnsubscribe() {
                                try {
                                    const reg = await navigator.serviceWorker.ready;
                                    const sub = await reg.pushManager.getSubscription();
                                    if (sub) {
                                        const endpoint = sub.endpoint;
                                        await sub.unsubscribe();
                                        const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                                        await fetch('/api/push-subscriptions', {
                                            method: 'DELETE',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                            body: JSON.stringify({ endpoint })
                                        });
                                    }
                                    this.on = false;
                                } catch(e) {
                                    alert('Gagal mematikan notifikasi: ' + e.message);
                                }
                            },
                            urlBase64ToUint8Array(base64) {
                                const pad = '='.repeat((4 - base64.length % 4) % 4);
                                const b64 = (base64 + pad).replace(/-/g, '+').replace(/_/g, '/');
                                const raw = atob(b64);
                                const arr = new Uint8Array(raw.length);
                                for (let i = 0; i < raw.length; i++) arr[i] = raw.charCodeAt(i);
                                return arr;
                            }
                        }"
                        class="flex items-center gap-x-1.5"
                        title="Toggle Web Push Notification">
                        <span class="text-[11px] font-semibold" :class="on ? 'text-emerald-500' : 'text-gray-400'" x-text="on ? 'Push ON' : 'Push OFF'"></span>

                        <button
                            type="button"
                            @click="toggle()"
                            :class="on ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-gray-600'"
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            role="switch"
                            :aria-checked="on">
                            <span
                                :class="on ? 'translate-x-4' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                </div>

                {{-- Kanan: Aksi Tandai Dibaca / Hapus Semua --}}
                @if ($hasNotifications)
                <div class="fi-ac">
                    @if ($unreadNotificationsCount && $this->markAllNotificationsAsReadAction?->isVisible())
                    {{ $this->markAllNotificationsAsReadAction }}
                    @endif

                    @if ($this->clearNotificationsAction?->isVisible())
                    {{ $this->clearNotificationsAction }}
                    @endif
                </div>
                @endif
            </div>
        </x-slot>

        @if ($hasNotifications)
        @foreach ($notifications as $notification)
        <div
            @class([ 'fi-no-notification-read-ctn'=> ! $notification->unread(),
            'fi-no-notification-unread-ctn' => $notification->unread(),
            ])
            >
            {{ $this->getNotification($notification)->inline() }}
        </div>
        @endforeach

        @if ($broadcastChannel = $this->getBroadcastChannel())
        @script
        <script>
            window.addEventListener('EchoLoaded', () => {
                window.Echo.private(@js($broadcastChannel)).listen(
                    '.database-notifications.sent',
                    () => {
                        setTimeout(
                            () => $wire.call('$refresh'),
                            500,
                        )
                    },
                )
            })

            if (window.Echo) {
                window.dispatchEvent(new CustomEvent('EchoLoaded'))
            }
        </script>
        @endscript
        @endif

        @if ($isPaginated)
        <x-slot name="footer">
            <x-filament::pagination :paginator="$notifications" />
        </x-slot>
        @endif
        @endif
    </x-filament::modal>
</div>