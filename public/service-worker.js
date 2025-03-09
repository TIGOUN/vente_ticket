// service-worker.js

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open("scanner-pwa-cache").then((cache) => {
            return cache.addAll([
                "/", // Page d'accueil
                "/index.html",
                "/styles.css", // Ajoutez tous les fichiers statiques ici (CSS, JS, images)
                "/images/icons/icon-192x192.png",
                "/images/icons/icon-512x512.png",
            ]);
        })
    );
});

self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            return cachedResponse || fetch(event.request);
        })
    );
});
