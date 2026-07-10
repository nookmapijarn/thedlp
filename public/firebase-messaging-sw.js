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

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
});

// Handle notification click to redirect users to the target action page
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    // Retrieve the target URL from the data payload (supporting both manual and Firebase SDK auto-display payloads)
    let redirectUrl = '/';
    if (event.notification.data) {
        if (event.notification.data.url) {
            redirectUrl = event.notification.data.url;
        } else if (event.notification.data.FCM_MSG && event.notification.data.FCM_MSG.data && event.notification.data.FCM_MSG.data.url) {
            redirectUrl = event.notification.data.FCM_MSG.data.url;
        }
    }

    // Force open the redirect URL in a new window/tab for maximum mobile compatibility
    event.waitUntil(
        clients.openWindow(redirectUrl)
    );
});
