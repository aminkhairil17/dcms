const express = require('express');
const webpush = require('web-push');
const path = require('path');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// Konfigurasi VAPID Keys pada web-push
const vapidPublicKey = process.env.VAPID_PUBLIC_KEY;
const vapidPrivateKey = process.env.VAPID_PRIVATE_KEY;
const vapidMailto = process.env.VAPID_MAILTO || 'mailto:admin@example.com';

if (!vapidPublicKey || !vapidPrivateKey) {
  console.error('ERROR: VAPID_PUBLIC_KEY dan VAPID_PRIVATE_KEY harus diatur di file .env');
  process.exit(1);
}

webpush.setVapidDetails(
  vapidMailto,
  vapidPublicKey,
  vapidPrivateKey
);

// Penyimpanan PushSubscription dalam memori (dapat diganti DB di produksi)
let subscriptions = [];

/**
 * 1. Endpoint GET '/api/vapid-public-key'
 * Mengirimkan VAPID Public Key ke client agar dapat dikonversi ke Uint8Array
 */
app.get('/api/vapid-public-key', (req, res) => {
  res.json({
    publicKey: vapidPublicKey
  });
});

/**
 * 2. Endpoint POST '/api/subscribe'
 * Menyimpan objek PushSubscription yang dikirim oleh client browser
 */
app.post('/api/subscribe', (req, res) => {
  const subscription = req.body;

  if (!subscription || !subscription.endpoint) {
    return res.status(400).json({ error: 'Objek subscription tidak valid.' });
  }

  // Cek apakah subscription sudah terdaftar (berdasarkan endpoint)
  const existingIndex = subscriptions.findIndex(
    (sub) => sub.endpoint === subscription.endpoint
  );

  if (existingIndex > -1) {
    subscriptions[existingIndex] = subscription; // Perbarui subscription
  } else {
    subscriptions.push(subscription); // Tambahkan subscription baru
  }

  console.log(`[SUBSCRIBE] Subscription disimpan. Total subscriber aktif: ${subscriptions.length}`);

  return res.status(201).json({
    message: 'Subscription berhasil disimpan.',
    totalSubscribers: subscriptions.length
  });
});

/**
 * Endpoint POST '/api/unsubscribe'
 * Menghapus subscription ketika pengguna menonaktifkan notifikasi
 */
app.post('/api/unsubscribe', (req, res) => {
  const { endpoint } = req.body;

  if (!endpoint) {
    return res.status(400).json({ error: 'Endpoint diperlukan.' });
  }

  subscriptions = subscriptions.filter((sub) => sub.endpoint !== endpoint);
  console.log(`[UNSUBSCRIBE] Subscription dihapus. Total subscriber aktif: ${subscriptions.length}`);

  return res.status(200).json({
    message: 'Unsubscribe berhasil.',
    totalSubscribers: subscriptions.length
  });
});

/**
 * 3. Endpoint POST '/api/send-notification'
 * Mengirimkan Web Push Notification ke seluruh subscriber yang terdaftar
 */
app.post('/api/send-notification', async (req, res) => {
  const { title, body, icon, badge, url } = req.body;

  if (subscriptions.length === 0) {
    return res.status(400).json({
      error: 'Belum ada subscriber terdaftar. Silakan subscribe notifikasi dari browser terlebih dahulu.'
    });
  }

  // Payload yang akan dikirim ke Service Worker (JSON String)
  const notificationPayload = JSON.stringify({
    title: title || 'Notifikasi Baru',
    body: body || 'Ini adalah pesan notifikasi dari server!',
    icon: icon || '/icon-192.png',
    badge: badge || '/icon-192.png',
    url: url || 'https://google.com',
    timestamp: Date.now()
  });

  const pushPromises = subscriptions.map((subscription) => {
    return webpush
      .sendNotification(subscription, notificationPayload)
      .then(() => ({ success: true, endpoint: subscription.endpoint }))
      .catch((err) => {
        console.error(`[PUSH ERROR] Gagal mengirim ke ${subscription.endpoint}:`, err.statusCode || err.message);

        // Jika status code 410 (Gone) atau 404 (Not Found), hapus subscription dari memori
        if (err.statusCode === 410 || err.statusCode === 404) {
          subscriptions = subscriptions.filter((sub) => sub.endpoint !== subscription.endpoint);
          console.log(`[CLEANUP] Subscription kedaluwarsa dihapus: ${subscription.endpoint}`);
        }

        return { success: false, endpoint: subscription.endpoint, error: err.message };
      });
  });

  const results = await Promise.all(pushPromises);
  const successCount = results.filter((r) => r.success).length;
  const failureCount = results.filter((r) => !r.success).length;

  console.log(`[SEND] Notifikasi terkirim ke ${successCount} subscriber (${failureCount} gagal).`);

  return res.status(200).json({
    message: 'Proses pengiriman notifikasi selesai.',
    successCount,
    failureCount,
    totalSubscribers: subscriptions.length,
    results
  });
});

// Jalankan Server Express
app.listen(PORT, () => {
  console.log(`====================================================`);
  console.log(`🚀 Server Web Push Notification berjalan di:`);
  console.log(`👉 http://localhost:${PORT}`);
  console.log(`====================================================`);
});
