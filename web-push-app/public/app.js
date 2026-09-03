/* ==========================================================================
   Client-side Logic untuk Web Push Notification (app.js)
   ========================================================================== */

// Elemen DOM UI
const permissionStatusEl = document.getElementById('permission-status');
const swStatusEl = document.getElementById('sw-status');
const subscriptionStatusEl = document.getElementById('subscription-status');

const btnSubscribe = document.getElementById('btn-subscribe');
const btnUnsubscribe = document.getElementById('btn-unsubscribe');
const btnSend = document.getElementById('btn-send');

const formSendNotif = document.getElementById('form-send-notification');
const vapidKeyDisplay = document.getElementById('vapid-key-display');
const subscriptionJsonDisplay = document.getElementById('subscription-json');

let swRegistration = null;
let currentSubscription = null;
let vapidPublicKey = null;

/**
 * 1. Helper Function: Konversi VAPID Public Key dari Base64 URL-Safe ke Uint8Array
 * Diperlukan oleh `pushManager.subscribe({ applicationServerKey: ... })`
 * @param {string} base64String 
 * @returns {Uint8Array}
 */
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

/**
 * Inisialisasi Aplikasi saat DOM selesai di-load
 */
document.addEventListener('DOMContentLoaded', async () => {
  // A. Cek dukungan Service Worker & Push API di browser
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
    swStatusEl.textContent = 'Tidak Didukung';
    swStatusEl.className = 'status-badge badge-danger';
    alert('Maaf, browser Anda tidak mendukung Service Worker atau Push API!');
    return;
  }

  // B. Ambil VAPID Public Key dari Backend Server
  await fetchVapidPublicKey();

  // C. Daftarkan Service Worker
  await registerServiceWorker();

  // D. Cek status permission & subscription saat ini
  await updateUIStatus();
});

/**
 * Mengambil VAPID Public Key dari API backend
 */
async function fetchVapidPublicKey() {
  try {
    const response = await fetch('/api/vapid-public-key');
    const data = await response.json();

    if (data.publicKey) {
      vapidPublicKey = data.publicKey;
      vapidKeyDisplay.textContent = vapidPublicKey;
    } else {
      vapidKeyDisplay.textContent = 'Gagal memuat key dari server';
    }
  } catch (error) {
    console.error('Error fetching VAPID Public Key:', error);
    vapidKeyDisplay.textContent = 'Error koneksi ke server';
  }
}

/**
 * Mendaftarkan Service Worker (`sw.js`)
 */
async function registerServiceWorker() {
  try {
    swRegistration = await navigator.serviceWorker.register('/sw.js', {
      scope: '/'
    });
    console.log('[App] Service Worker terdaftar dengan scope:', swRegistration.scope);

    swStatusEl.textContent = 'Terdaftar & Aktif';
    swStatusEl.className = 'status-badge badge-success';
  } catch (error) {
    console.error('[App] Gagal mendaftarkan Service Worker:', error);
    swStatusEl.textContent = 'Gagal Registrasi';
    swStatusEl.className = 'status-badge badge-danger';
  }
}

/**
 * Memperbarui Tampilan UI berdasarkan status permission & subscription
 */
async function updateUIStatus() {
  // Update status Izin (Permission)
  const permission = Notification.permission;
  permissionStatusEl.textContent = permission.toUpperCase();

  if (permission === 'granted') {
    permissionStatusEl.className = 'status-badge badge-success';
  } else if (permission === 'denied') {
    permissionStatusEl.className = 'status-badge badge-danger';
  } else {
    permissionStatusEl.className = 'status-badge badge-warning';
  }

  // Cek apakah ada subscription yang aktif
  if (swRegistration) {
    currentSubscription = await swRegistration.pushManager.getSubscription();
  }

  if (currentSubscription) {
    subscriptionStatusEl.textContent = 'Subscribed';
    subscriptionStatusEl.className = 'status-badge badge-success';
    
    subscriptionJsonDisplay.textContent = JSON.stringify(currentSubscription, null, 2);

    btnSubscribe.disabled = true;
    btnUnsubscribe.disabled = false;
    btnSend.disabled = false;
  } else {
    subscriptionStatusEl.textContent = 'Belum Subscribed';
    subscriptionStatusEl.className = 'status-badge badge-neutral';

    subscriptionJsonDisplay.textContent = 'Belum ada subscription aktif.';

    btnSubscribe.disabled = permission === 'denied';
    btnUnsubscribe.disabled = true;
    btnSend.disabled = true;
  }
}

/**
 * Logika Meminta Izin & Melakukan Subscribe ke Push Manager
 */
btnSubscribe.addEventListener('click', async () => {
  if (!vapidPublicKey) {
    alert('VAPID Public Key belum dimuat dari server. Pastikan server running.');
    return;
  }

  try {
    btnSubscribe.disabled = true;

    // 1. Minta Izin Notifikasi dari User
    const permission = await Notification.requestPermission();
    if (permission !== 'granted') {
      alert('Izin notifikasi ditolak oleh pengguna.');
      await updateUIStatus();
      return;
    }

    // 2. Konversi VAPID Public Key ke Uint8Array
    const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

    // 3. Minta PushSubscription dari Browser Push Manager
    console.log('[App] Mengambil PushSubscription...');
    currentSubscription = await swRegistration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: convertedVapidKey
    });

    console.log('[App] PushSubscription berhasil dibuat:', currentSubscription);

    // 4. Kirim Objek Subscription ke Backend Express Server
    const response = await fetch('/api/subscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(currentSubscription)
    });

    const result = await response.json();
    if (response.ok) {
      alert('Berhasil terdaftar untuk Web Push Notification!');
    } else {
      alert('Gagal menyimpan subscription di server: ' + result.error);
    }

    await updateUIStatus();
  } catch (error) {
    console.error('[App] Error saat subscribe:', error);
    alert('Terjadi kesalahan saat melakukan subscribe: ' + error.message);
    await updateUIStatus();
  }
});

/**
 * Logika Unsubscribe Notifikasi
 */
btnUnsubscribe.addEventListener('click', async () => {
  if (!currentSubscription) return;

  try {
    btnUnsubscribe.disabled = true;

    // 1. Hapus dari Push Manager browser
    const endpoint = currentSubscription.endpoint;
    await currentSubscription.unsubscribe();

    // 2. Beritahu Backend untuk menghapus dari memori
    await fetch('/api/unsubscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ endpoint })
    });

    currentSubscription = null;
    alert('Berhasil unsubscribe notifikasi.');
    await updateUIStatus();
  } catch (error) {
    console.error('[App] Error saat unsubscribe:', error);
    alert('Gagal unsubscribe: ' + error.message);
    await updateUIStatus();
  }
});

/**
 * Logika Mengirim Test Notifikasi dari Form UI
 */
formSendNotif.addEventListener('submit', async (e) => {
  e.preventDefault();

  const title = document.getElementById('notif-title').value;
  const body = document.getElementById('notif-body').value;
  const url = document.getElementById('notif-url').value;

  try {
    btnSend.disabled = true;

    const response = await fetch('/api/send-notification', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        title,
        body,
        url,
        icon: 'https://cdn-icons-png.flaticon.com/512/3602/3602145.png',
        badge: 'https://cdn-icons-png.flaticon.com/512/3602/3602145.png'
      })
    });

    const result = await response.json();

    if (response.ok) {
      console.log('[App] Hasil pengiriman:', result);
      alert(`Notifikasi dipicu! Terkirim ke ${result.successCount} subscriber.`);
    } else {
      alert('Gagal mengirim notifikasi: ' + (result.error || result.message));
    }
  } catch (error) {
    console.error('[App] Error sending push notification:', error);
    alert('Terjadi kesalahan saat memicu notifikasi: ' + error.message);
  } finally {
    btnSend.disabled = false;
  }
});
