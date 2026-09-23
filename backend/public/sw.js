const CACHE_NAME = 'sigap-subposko-cache-v13';

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

// 1. Install & Pre-cache Seluruh Aset Inti Menu Lapangan
self.addEventListener('install', (event) => {
    console.log('[PWA SW Lapangan] Pre-caching core assets...');
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE).catch((err) => {
                console.warn('[PWA SW Lapangan] Warning pre-caching non-fatal:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// 2. Activate & Clean Old Caches
self.addEventListener('activate', (event) => {
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

    // Abaikan request non-GET atau request ping/check
    if (event.request.method !== 'GET' || url.pathname.includes('/ping') || url.search.includes('check=')) {
        return;
    }

    // A. JIKA REQUEST ADALAH HALAMAN HTML (NAVIGASI MENU)
    if (event.request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                // JIKA KONEKSI ONLINE: Fetch dari jaringan dan perbarui cache
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
                        // Jika offline dan tidak ada di cache, baru kembalikan dashboard
                        return cachedResponse || caches.match('/lapangan/dashboard');
                    });

                // Jika offline dan halaman sudah ada di cache -> Kembalikan langsung dari Cache
                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // B. JIKA REQUEST ADALAH ASET STATIS (CSS, JS, IMAGES, FONTS)
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