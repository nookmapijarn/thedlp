importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Initialize the Firebase app in the service worker
const firebaseConfig = {
    apiKey: "tRy6n8XtiIXsfQF_AtWgMg02NH4MaTkIPKiZ6LGXrK0",
    authDomain: "olis-b1bc2.firebaseapp.com",
    projectId: "olis-b1bc2",
    storageBucket: "olis-b1bc2.appspot.com",
    messagingSenderId: "975237631927",
    appId: "1:975237631927:web:2237efa9d4e9a6099b2232"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Handle background messages (Firebase SDK displays them natively based on FCM payload notification block)
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
});

// Handle notification click to redirect users to the target action page
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const redirectUrl = event.notification.data ? event.notification.data.url : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
            // Focus if a window with our domain is already open
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                if (client.url.indexOf(self.location.origin) === 0 && 'focus' in client) {
                    return client.navigate(redirectUrl).then(c => c.focus());
                }
            }
            // If no window is open, open a new tab
            if (clients.openWindow) {
                return clients.openWindow(redirectUrl);
            }
        })
    );
});
