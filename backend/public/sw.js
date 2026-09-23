const CACHE_NAME = 'sigap-subposko-cache-v20';

// DAFTAR RUTE & ASSET KHUSUS MODUL LAPANGAN YANG WAJIB DI-CACHE
const ASSETS_TO_CACHE = [
    '/lapangan/dashboard',
    '/lapangan/pengungsi',
    '/lapangan/pengajuan',
    '/lapangan/penyaluran',
    '/lapangan/stok',
    '/lapangan/ambulans',
    '/img/Rescue-log.png',
    '/favicon.png',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

// 1. INSTALL EVENT: PRE-CACHE SELURUH RUTE LAPANGAN SAAT ONLINE
self.addEventListener('install', (event) => {
    console.log('[PWA SW Lapangan] Installing Production Cache v20...');
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return Promise.allSettled(
                ASSETS_TO_CACHE.map((url) => {
                    return cache.add(url).catch((err) => {
                        console.warn('[PWA SW Cache Warning] Skip URL:', url, err);
                    });
                })
            );
        }).then(() => self.skipWaiting())
    );
});

// 2. ACTIVATE EVENT: BERSIHKAN CACHE VERA LAMA & AMBIL KONTROL SEGERA
self.addEventListener('activate', (event) => {
    console.log('[PWA SW Lapangan] Activating Production Cache v20...');
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        console.log('[PWA SW Lapangan] Deleting old cache:', key);
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// 3. FETCH EVENT: SERVE DARI CACHE DULU (PENGUNGSI, PENGAJUAN, DLL)
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Abaikan request non-GET atau ping heartbeat
    if (event.request.method !== 'GET' || url.pathname.includes('/ping') || url.search.includes('check=')) {
        return;
    }

    // A. NAVIGASI HALAMAN HTML
    if (event.request.mode === 'navigate' || event.request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                // JIKA HALAMAN SUDAH ADA DI CACHE: Langsung tampilkan (Akses Offline Instan)
                if (cachedResponse) {
                    // Update cache di background jika sedang Online
                    if (navigator.onLine) {
                        fetch(event.request).then((networkResponse) => {
                            if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, networkResponse));
                            }
                        }).catch(() => {});
                    }
                    return cachedResponse;
                }

                // JIKA TIDAK ADA DI CACHE: Coba ambil dari Network
                return fetch(event.request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                            const responseClone = networkResponse.clone();
                            caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                        }
                        return networkResponse;
                    })
                    .catch(() => {
                        // Fallback ke Dashboard jika rute spesifik belum pernah di-cache
                        return caches.match('/lapangan/dashboard');
                    });
            })
        );
        return;
    }

    // B. ASET STATIS (CSS, JS, IMAGES, FONTS)
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                }
                return networkResponse;
            });
        })
    );
});