const CACHE_NAME = 'subposko-cache-v1';
const ASSETS_TO_CACHE = [
  '/sub-posko/dashboard',
  '/sub-posko/pengajuan/create',
  '/css/app.css',
  '/js/app.js',
  'https://cdn.jsdelivr.net/npm/localforage@1.10.0/dist/localforage.min.js'
];

// Install Service Worker & Cache Assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS_TO_CACHE))
  );
});

// Serve Cached Content when Offline
self.addEventListener('fetch', (event) => {
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request);
    })
  );
});