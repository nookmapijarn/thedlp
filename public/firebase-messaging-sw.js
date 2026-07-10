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

// Force immediate activation of the updated service worker
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
});

// Handle notification click to redirect users to the target action page
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    // Retrieve the target URL or fallback to ticket query parameter
    let redirectUrl = '/';
    let ticketId = null;

    if (event.notification.data) {
        if (event.notification.data.url) {
            redirectUrl = event.notification.data.url;
        }
        if (event.notification.data.ticket) {
            ticketId = event.notification.data.ticket;
        }

        // Check inside nested FCM_MSG parameter object structure
        if (event.notification.data.FCM_MSG && event.notification.data.FCM_MSG.data) {
            const fcmData = event.notification.data.FCM_MSG.data;
            if (fcmData.url) {
                redirectUrl = fcmData.url;
            }
            if (fcmData.ticket) {
                ticketId = fcmData.ticket;
            }
        }
    }

    // Dynamic fallback URL for raw ticket notifications
    if (redirectUrl === '/' && ticketId) {
        redirectUrl = '/ศูนย์รับแจ้งปัญหา?ticket=' + ticketId;
    }

    // Force open the redirect URL in a new window/tab for maximum mobile compatibility
    event.waitUntil(
        clients.openWindow(redirectUrl)
    );
});

// Register a fetch handler to satisfy PWA installation criteria in mobile browsers
self.addEventListener('fetch', (event) => {
    // Pass-through request fetch
});
