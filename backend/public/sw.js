const CACHE_NAME = 'sigap-subposko-cache-v4';

// Masukkan SELURUH route menu lapangan yang ada di UI ke sini!
const ASSETS_TO_CACHE = [
    '/lapangan/dashboard',
    '/lapangan/pengungsi',
    '/lapangan/pengajuan',
    '/lapangan/distribusi',
    '/lapangan/pengiriman',
    '/lapangan/ambulans',
    '/lapangan/stok',
    '/favicon.png',
    '/models/model_logistik.onnx',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
    'https://cdn.jsdelivr.net/npm/localforage@1.10.0/dist/localforage.min.js',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://cdn.jsdelivr.net/npm/onnxruntime-web/dist/ort.min.js',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
];

// 1. Install Service Worker & Pre-cache Semua Route Menu & Aset
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[PWA SW] Pre-caching all sub-posko routes & assets...');
            // Gunakan catch agar jika 1 URL eksternal gagal, proses install tidak membatalkan cache lainnya
            return Promise.allSettled(
                ASSETS_TO_CACHE.map((url) => cache.add(url).catch((err) => console.warn('[PWA SW] Failed to cache:', url, err)))
            );
        }).then(() => self.skipWaiting())
    );
});

// 2. Activate & Hapus Cache Versi Lama
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        console.log('[PWA SW] Removing old cache:', key);
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// 3. Strategy: Cache First dengan Network Fallback untuk Navigasi Cepat
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            // Jika ada di Cache, langsung tampilkan dari Cache! (Cepat & Bekerja 100% Offline)
            if (cachedResponse) {
                // Sambil memberikan dari cache, perbarui cache secara background jika sedang online
                fetch(event.request).then((networkResponse) => {
                    if (networkResponse && (networkResponse.status === 200 || networkResponse.type === 'opaque')) {
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, networkResponse));
                    }
                }).catch(() => {/* Ignore offline error in background fetch */});

                return cachedResponse;
            }

            // Jika tidak ada di cache, coba ambil dari jaringan
            return fetch(event.request)
                .then((networkResponse) => {
                    if (networkResponse && (networkResponse.status === 200 || networkResponse.type === 'opaque')) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                    }
                    return networkResponse;
                })
                .catch(() => {
                    // Fallback jika offline & halaman HTML belum ter-cache
                    if (event.request.headers.get('accept')?.includes('text/html')) {
                        return caches.match('/lapangan/dashboard');
                    }
                });
        })
    );
});