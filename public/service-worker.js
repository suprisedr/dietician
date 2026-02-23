self.addEventListener('install', event => {
    event.waitUntil(
        caches.open('dietician-cache-v1').then(cache => {
            return cache.addAll([
                '/',
                '/css/app.css',
                '/js/app.js',
                '/manifest.json'
            ]);
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(r => r || fetch(event.request))
    );
});
