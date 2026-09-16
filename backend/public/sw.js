const CACHE_NAME = 'sigap-subposko-cache-v3';
const ASSETS_TO_CACHE = [
    '/lapangan/dashboard',
    '/lapangan/stok',
    '/lapangan/pengajuan',
    '/favicon.png',
    '/models/model_logistik.onnx', // MODEL ONNX DI-CACHE AGAR RUNTIME AI OFFLINE BISA DIPANGGIL
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
    'https://cdn.jsdelivr.net/npm/localforage@1.10.0/dist/localforage.min.js',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://cdn.jsdelivr.net/npm/onnxruntime-web/dist/ort.min.js',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
];

// Install Service Worker & Cache Assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[PWA SW] Caching app shell, ONNX model & static assets...');
            return cache.addAll(ASSETS_TO_CACHE);
        }).then(() => self.skipWaiting())
    );
});

// Activate & Hapus Cache Lama
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

// Serve Cached Content saat Offline (Network First dengan Cache Fallback)
self.addEventListener('fetch', (event) => {
    // Hanya tangani request GET untuk caching UI & Model Assets
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                // Jika sukses online, perbarui cache secara dinamis
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            })
            .catch(() => {
                // Jika offline / gagal fetch, ambil dari Cache Storage
                return caches.match(event.request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Fallback jika rute HTML tidak ada di cache
                    if (event.request.headers.get('accept')?.includes('text/html')) {
                        return caches.match('/lapangan/dashboard');
                    }
                });
            })
    );
});