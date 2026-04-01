<div>
    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                    return;
                }

                const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";

                function urlBase64ToUint8Array(base64String) {
                    const padding = '='.repeat((4 - base64String.length % 4) % 4);
                    const base64 = (base64String + padding)
                        .replace(/-/g, '+')
                        .replace(/_/g, '/');
                    const rawData = window.atob(base64);
                    const outputArray = new Uint8Array(rawData.length);
                    for (let i = 0; i < rawData.length; ++i) {
                        outputArray[i] = rawData.charCodeAt(i);
                    }
                    return outputArray;
                }

                function sendSubscriptionToServer(subscription) {
                    const endpoint = subscription.endpoint;
                    const keys = subscription.toJSON().keys;

                    fetch('/push/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            endpoint: endpoint,
                            keys: keys,
                        }),
                    }).catch(function (error) {
                        console.error('Failed to send subscription to server:', error);
                    });
                }

                navigator.serviceWorker.register('/service-worker.js')
                    .then(function (registration) {
                        return registration.pushManager.getSubscription()
                            .then(function (existingSubscription) {
                                if (existingSubscription) {
                                    return existingSubscription;
                                }

                                return registration.pushManager.subscribe({
                                    userVisibleOnly: true,
                                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
                                });
                            })
                            .then(function (subscription) {
                                sendSubscriptionToServer(subscription);
                            })
                            .catch(function (error) {
                                console.error('Failed to subscribe to push notifications:', error);
                            });
                    })
                    .catch(function (error) {
                        console.error('Service Worker registration failed:', error);
                    });
            });
        </script>
    @endauth
</div>
