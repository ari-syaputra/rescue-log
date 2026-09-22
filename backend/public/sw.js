const CACHE_NAME = 'sigap-subposko-cache-v11';

const ASSETS_TO_CACHE = [
    '/',
    '/login',
    '/ping',
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

// 1. Install & Pre-cache
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[PWA SW] Pre-caching assets...');
            return Promise.allSettled(
                ASSETS_TO_CACHE.map(url => 
                    fetch(url).then(response => {
                        if (response.ok) return cache.put(url, response);
                    }).catch(err => console.warn('[PWA SW] Skip cache:', url))
                )
            );
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
                        console.log('[PWA SW] Deleting old cache:', key);
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// 3. Fetch Strategy: Network First (Abaikan Request Ping Check agar tidak di-cache)
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    // ABAIKAN REQUEST PING CHECK AGAR TEMBUS LANGSUNG KE NETWORK
    if (event.request.url.includes('check=')) {
        return; // Biarkan browser menangani secara langsung ke jaringan
    }

    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                }
                return networkResponse;
            })
            .catch(() => {
                return caches.match(event.request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    if (event.request.headers.get('accept')?.includes('text/html')) {
                        return caches.match('/lapangan/dashboard') || caches.match('/login');
                    }
                });
            })
    );
});