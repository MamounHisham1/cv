/* eslint-disable no-restricted-globals */

self.addEventListener('push', function (event) {
    const options = {
        body: event.data ? event.data.text() : 'New notification',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: '1',
        },
        actions: [
            { action: 'view', title: 'View' },
            { action: 'close', title: 'Close' },
        ],
    };

    event.waitUntil(
        self.registration.showNotification('SeratyAI', options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.action === 'view') {
        event.waitUntil(
            clients.matchAll({ type: 'window' }).then(function (clientList) {
                for (let i = 0; i < clientList.length; i++) {
                    const client = clientList[i];
                    if (client.url.includes('/evaluator') || client.url.includes('/referrals') || client.url.includes('/credits')) {
                        return client.focus();
                    }
                }
                return clients.openWindow('/dashboard');
            })
        );
    }
});

self.addEventListener('pushsubscriptionchange', function (event) {
    const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";

    event.waitUntil(
        self.registration.pushManager.subscribe(
            {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            }
        ).then(function (subscription) {
            return fetch('/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                    keys: subscription.toJSON().keys,
                }),
            });
        })
    );
});

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');
    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
