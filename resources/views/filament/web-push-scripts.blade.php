{{-- Filament Web Push Notification Service Worker Initialization --}}
<script>
    (function() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            console.warn('[WebPush] Browser tidak mendukung Service Worker / Push Manager');
            return;
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        async function syncSubscription(registration) {
            try {
                let subscription = await registration.pushManager.getSubscription();
                if (!subscription) {
                    const res = await fetch('/api/push-subscriptions/vapid-key');
                    const data = await res.json();
                    if (!data.publicKey) return;

                    subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(data.publicKey)
                    });
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                await fetch('/api/push-subscriptions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify(subscription)
                });
                console.log('[WebPush] PushSubscription ter-sinkronisasi dengan server');
            } catch (e) {
                console.error('[WebPush] Error saat menyinkronkan subscription:', e);
            }
        }

        async function initWebPush() {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });
                console.log('[WebPush] Service Worker terdaftar:', registration.scope);

                if (Notification.permission === 'granted') {
                    await syncSubscription(registration);
                }
            } catch (err) {
                console.error('[WebPush] Gagal mendaftarkan Service Worker:', err);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initWebPush);
        } else {
            initWebPush();
        }
    })();
</script>