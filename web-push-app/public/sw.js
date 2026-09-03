/* ==========================================================================
   Service Worker untuk Web Push Notification (sw.js)
   ========================================================================== */

// Event listener saat Service Worker di-install
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installed successfully.');
  self.skipWaiting(); // Langsung aktif tanpa menunggu tab lama ditutup
});

// Event listener saat Service Worker di-aktifkan
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activated.');
  event.waitUntil(clients.claim()); // Mengambil kontrol seluruh client aktif
});

/**
 * 1. Event 'push'
 * Mendengarkan pesan yang dikirim dari push service via server backend
 */
self.addEventListener('push', (event) => {
  console.log('[Service Worker] Push event diterima.');

  let payload = {
    title: 'Notifikasi Baru',
    body: 'Anda menerima notifikasi dari aplikasi.',
    icon: 'https://cdn-icons-png.flaticon.com/512/3602/3602145.png',
    badge: 'https://cdn-icons-png.flaticon.com/512/3602/3602145.png',
    url: '/'
  };

  if (event.data) {
    try {
      payload = event.data.json();
    } catch (e) {
      console.warn('[Service Worker] Payload berupa plain text:', event.data.text());
      payload.body = event.data.text();
    }
  }

  const notificationTitle = payload.title || 'Notifikasi Baru';
  const notificationOptions = {
    body: payload.body || 'Pesan notifikasi',
    icon: payload.icon || 'https://cdn-icons-png.flaticon.com/512/3602/3602145.png',
    badge: payload.badge || 'https://cdn-icons-png.flaticon.com/512/3602/3602145.png',
    vibrate: [100, 50, 100],
    data: {
      url: payload.url || '/',
      timestamp: payload.timestamp || Date.now()
    },
    actions: [
      {
        action: 'open_url',
        title: 'Buka tautan'
      },
      {
        action: 'close',
        title: 'Tutup'
      }
    ]
  };

  // Tampilkan notifikasi ke layar user
  event.waitUntil(
    self.registration.showNotification(notificationTitle, notificationOptions)
  );
});

/**
 * 2. Event 'notificationclick'
 * Menangani aksi saat pengguna mengklik notifikasi toast
 */
self.addEventListener('notificationclick', (event) => {
  console.log('[Service Worker] Notification click diterima.');
  
  const notification = event.notification;
  const action = event.action;
  const targetUrl = (notification.data && notification.data.url) ? notification.data.url : '/';

  // Tutup notifikasi toast
  notification.close();

  if (action === 'close') {
    return;
  }

  // Arahkan user ke URL tujuan
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
      // Cek apakah tab dengan URL sama sudah terbuka
      for (let i = 0; i < windowClients.length; i++) {
        const client = windowClients[i];
        if (client.url === targetUrl && 'focus' in client) {
          return client.focus();
        }
      }
      // Jika belum terbuka, buka tab baru
      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});
