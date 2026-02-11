import { cleanupOutdatedCaches, precacheAndRoute } from 'workbox-precaching';
import { registerRoute } from 'workbox-routing';
import { StaleWhileRevalidate, CacheFirst, NetworkFirst } from 'workbox-strategies';
import { ExpirationPlugin } from 'workbox-expiration';
import { CacheableResponsePlugin } from 'workbox-cacheable-response';

// 1. Clean up old caches and precache files
cleanupOutdatedCaches();
precacheAndRoute(self.__WB_MANIFEST);

// 2. Runtime Caching

// Cache Google Fonts
registerRoute(
    ({ url }) => url.origin === 'https://fonts.googleapis.com' || url.origin === 'https://fonts.gstatic.com',
    new StaleWhileRevalidate({
        cacheName: 'google-fonts-cache',
        plugins: [
            new ExpirationPlugin({ maxEntries: 20 }),
        ],
    })
);

// Cache Quran API/Pages (Offline Support)
registerRoute(
    ({ url }) => url.pathname.startsWith('/quran'),
    new StaleWhileRevalidate({
        cacheName: 'quran-cache',
        plugins: [
            new CacheableResponsePlugin({
                statuses: [0, 200],
            }),
            new ExpirationPlugin({ maxEntries: 200, maxAgeSeconds: 30 * 24 * 60 * 60 }), // 30 Days
        ],
    })
);

// Cache Dzikir Pages (Offline Support)
registerRoute(
    ({ url }) => url.pathname.startsWith('/ibadah'),
    new StaleWhileRevalidate({
        cacheName: 'ibadah-cache',
        plugins: [
            new CacheableResponsePlugin({ statuses: [0, 200] }),
            new ExpirationPlugin({ maxEntries: 20, maxAgeSeconds: 7 * 24 * 60 * 60 }),
        ]
    })
);

// Cache Prayer Times API (Offline Support)
registerRoute(
    ({ url }) => url.pathname.startsWith('/api/prayer-times'),
    new StaleWhileRevalidate({
        cacheName: 'prayer-times-cache',
        plugins: [
            new CacheableResponsePlugin({ statuses: [0, 200] }),
            new ExpirationPlugin({ maxEntries: 50, maxAgeSeconds: 7 * 24 * 60 * 60 }),
        ]
    })
);

// Cache Blog (Official & Unlocked)
registerRoute(
    ({ url }) => url.pathname.startsWith('/blog'),
    new StaleWhileRevalidate({
        cacheName: 'blog-cache',
        plugins: [
            new CacheableResponsePlugin({ statuses: [0, 200] }),
            new ExpirationPlugin({ maxEntries: 50, maxAgeSeconds: 7 * 24 * 60 * 60 }),
        ]
    })
);

// 2. FORCE ACTIVATION: skipWaiting on install
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

// 3. FORCE CONTROL: claim clients on activate
self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// 4. Handle Push Notification
self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const payload = event.data ? event.data.json() : {};

    event.waitUntil(
        self.registration.showNotification(payload.title, {
            body: payload.body,
            icon: payload.icon,
            actions: payload.actions,
            data: payload.data,
            vibrate: [100, 50, 100],
        })
    );
});

// 5. Handle Notification Click
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((windowClients) => {
            // Check if there is already a window/tab open with the target URL
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            // If not, open a new window
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});