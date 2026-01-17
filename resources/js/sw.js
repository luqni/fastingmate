import { cleanupOutdatedCaches, precacheAndRoute } from 'workbox-precaching';

// 1. Clean up old caches and precache files
cleanupOutdatedCaches();
precacheAndRoute(self.__WB_MANIFEST);

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