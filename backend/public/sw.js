const CACHE_NAME = 'sigap-subposko-cache-v17';

// Hanya cache asset statis utama saat install
const ASSETS_TO_CACHE = [
    '/img/Rescue-log.png',
    '/favicon.png',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

// 1. Install Event: Cepat dan Ringan
self.addEventListener('install', (event) => {
    console.log('[PWA SW Lapangan] Installing v17...');
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return Promise.allSettled(
                ASSETS_TO_CACHE.map((url) => cache.add(url).catch(() => {}))
            );
        }).then(() => self.skipWaiting())
    );
});

// 2. Activate Event: Bersihkan Cache Lama
self.addEventListener('activate', (event) => {
    console.log('[PWA SW Lapangan] Activating v17...');
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// 3. Fetch Strategy: Cache-First dengan Runtime Caching Otomatis
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Abaikan request non-GET atau ping heartbeat
    if (event.request.method !== 'GET' || url.pathname.includes('/ping') || url.search.includes('check=')) {
        return;
    }

    // A. UNTUK HALAMAN HTML (NAVIGASI MENU)
    if (event.request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                // Ambil dari Cache dulu agar BUKA SECEPAT KILAT (< 10ms)
                const fetchPromise = fetch(event.request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                            const responseClone = networkResponse.clone();
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(event.request, responseClone);
                            });
                        }
                        return networkResponse;
                    })
                    .catch(() => {
                        return cachedResponse || caches.match('/lapangan/dashboard');
                    });

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // B. UNTUK ASET STATIS (CSS, JS, IMAGES)
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            });
        })
    );
});