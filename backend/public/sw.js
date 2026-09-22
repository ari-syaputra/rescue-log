const CACHE_NAME = 'sigap-subposko-cache-v5';

const ASSETS_TO_CACHE = [
    '/lapangan/dashboard',
    '/lapangan/pengungsi',
    '/lapangan/pengajuan',
    '/lapangan/distribusi',
    '/lapangan/pengiriman',
    '/lapangan/ambulans',
    '/img/Rescue-log.png',
    '/favicon.png',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[PWA SW] Installing & Caching Assets...');
            // Menggunakan Promise.allSettled agar jika 1 URL 404, file lainnya TETAP ter-cache!
            return Promise.allSettled(
                ASSETS_TO_CACHE.map(url => 
                    fetch(url).then(response => {
                        if (response.ok) return cache.put(url, response);
                    }).catch(err => console.warn('[PWA SW] Skip cache for:', url))
                )
            );
        }).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
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

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

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
            }).catch(() => {
                if (event.request.headers.get('accept')?.includes('text/html')) {
                    return caches.match('/lapangan/dashboard');
                }
            });
        })
    );
});