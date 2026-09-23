const CACHE_NAME = 'sigap-subposko-cache-v14';

const ASSETS_TO_CACHE = [
    '/',
    '/login',
    '/ping',
    '/lapangan/dashboard',
    '/lapangan/pengungsi',
    '/lapangan/pengajuan',
    '/lapangan/penyaluran',
    '/lapangan/stok',
    '/lapangan/ambulans',
    '/img/Rescue-log.png',
    '/favicon.png',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
];

// 1. Install & Pre-cache Aset Inti Lapangan (Toleran terhadap 404/Network failure)
self.addEventListener('install', (event) => {
    console.log('[PWA SW Lapangan] Pre-caching core assets v14...');
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            // Menggunakan Promise.allSettled agar jika 1 URL 404/gagal, URL lain TETAP ter-cache
            return Promise.allSettled(
                ASSETS_TO_CACHE.map((url) => {
                    return cache.add(url).catch((err) => {
                        console.warn('[PWA SW Cache Warning] Gagal me-load asset:', url, err);
                    });
                })
            );
        }).then(() => self.skipWaiting())
    );
});

// 2. Activate & Hapus Cache Versi Lama
self.addEventListener('activate', (event) => {
    console.log('[PWA SW Lapangan] Activating & Clearing Old Caches...');
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

// 3. Fetch Strategy: Smart Cache First / Network Fallback untuk Navigasi Lapangan
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Abaikan request non-GET atau request ping/check koneksi
    if (event.request.method !== 'GET' || url.pathname.includes('/ping') || url.search.includes('check=')) {
        return;
    }

    // A. JIKA REQUEST ADALAH HALAMAN HTML (NAVIGASI MENU)
    if (event.request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                // JIKA ONLINE: Lakukan fetch dari server untuk revalidate/update cache
                const fetchPromise = fetch(event.request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            const responseClone = networkResponse.clone();
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(event.request, responseClone);
                            });
                        }
                        return networkResponse;
                    })
                    .catch(() => {
                        // Jika Offline dan URL spesifik tidak ada di cache, gunakan dashboard/login sebagai fallback
                        return cachedResponse || caches.match('/lapangan/dashboard') || caches.match('/login');
                    });

                // Mengutamakan data dari Cache jika tersedia (mencegah layar berputar/lama saat jaringan buruk)
                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // B. JIKA REQUEST ADALAH ASET STATIS (CSS, JS, IMAGES, FONTS, CDN)
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